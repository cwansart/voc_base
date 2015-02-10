<?php

$db = [
    'host' => 'localhost',
    'dbname' => 'vokabeln',
    'user' => 'vokabeln',
    'password' => 'vokabeln'
];

$word = getParam('term');
$language = strtolower(stripSpaces(getParam('sprache')));
$language2 = strtolower(stripSpaces(getParam('sprache2')));

switch($language) {
    case 'englisch':
        break;
    //case 'französisch':
    //    $language = 'franzoesisch';
    //    break;
    default:
        http_response_code(400); // Bad Request
        exit(1);
}

$dbh = null;
try {
    $dbh = new PDO('mysql:host='.$db['host'].';dbname='.$db['dbname'].';charset=utf8', $db['user'], $db['password']);
    // Bei zu vielen Einträgen, sollten wir diesen Code anpassen.
    $sql = "SELECT $language2 FROM deutsch_$language WHERE $language2 LIKE concat('%', ?, '%')";
    $sth = $dbh->prepare($sql);
    $sth->bindParam(1, $word);
    if(!$sth->execute()) {
        http_response_code(400); // Bad Request
        exit(1);
    }
    $result = $sth->fetchAll(PDO::FETCH_COLUMN);

    header('Content-Type: text/html; charset=UTF-8');
    echo json_encode($result);
    //print_r($result);
}
catch(PDOException $e) {
    // Kann im Client abgefragt und so festgestellt werden,
    // ob es Serverprobleme gibt.
    http_response_code(500); // Internal Server Error
    // Nur zum Debuggen nutzen!!
    //echo 'Connection failed: ' . $e->getMessage();
    exit(1);
}

// Funktion zum Prüfen, ob der Parameter auch wirklich gesetzt wurde
// und nicht leer ist.
function getParam($param) {
    if(!isset($_GET[$param]) || empty($_GET[$param])) {
        http_response_code(400); // Bad Request
        exit(1);
    }

    return $_GET[$param];
}

// Entfernt jegliche Leerzeichen am Anfang, am Ende und auch im
// String.
function stripSpaces($str) {
    $s = str_replace(0x20, '', $str);  // Leerzeichen
    $s = str_replace(0x09, '', $s);    // Tabulator \t
    $s = str_replace(0x0A, '', $s);    // Line Feed
    $s = str_replace(0x0D, '', $s);    // Carriage-Return
    $s = str_replace(0x00, '', $s);    // Null-Byte \0
    $s = str_replace(0x0B, '', $s);    // vertikaler Tabulator
    return trim($s);
}

