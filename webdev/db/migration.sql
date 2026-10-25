START TRANSACTION;
INSERT INTO `user` (`login`, `name`, `pwd_hash`)
VALUES (
    'DI',
    'Ильин Дмитрий Олегович',
    'AAAAAAA1.'
);

INSERT INTO `operation` (`login`, `operation`, `x`, `y`, `z`)
VALUES
    ('DI', 'plus', 10, 5, 15),
    ('DI', 'minus', 20, 8, 12),
    ('DI', 'multiply', 7, 6, 42);

COMMIT;