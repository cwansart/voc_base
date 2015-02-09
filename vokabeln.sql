CREATE TABLE IF NOT EXISTS deutsch_englisch (
    deutsch VARCHAR(100),
    englisch VARCHAR(100),
    PRIMARY KEY(deutsch, englisch)
) CHARSET=utf8;

INSERT INTO deutsch_englisch VALUES
('hallo', 'hello'),
('guten Morgen', 'good morning'),
('Wie heißt du?', 'What\'s your name?'),
('auf Wiedersehen.', 'goodbye'),
('tschüss', 'bye'),
('Wie geht es dir?', 'How are you?');
