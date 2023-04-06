INSERT INTO projects (user_id, name)
VALUES
    (1, "Входящие"),
    (1, "Учеба"),
    (1, "Работа"),
    (1, "Домашние дела"),
    (1, "Авто"),
    (2, "Путешествия");

INSERT INTO users (email, name, password)
VALUES
    ("antrembatch@yandex.ru", "sol", "123456"),
    ("abcdefg@ya.ru", "katya", "9876");

INSERT INTO tasks (user_id, project_id, title, status, date_finish)
VALUES
    (1, 3, "Собеседование в IT компании", 0, "2023-01-12"),
    (1, 3, "Выполнить тестовое задание", 0, "2019-12-25"),
    (1, 3, "Сделать задание первого раздела", 1, "2019-12-21"),
    (1, 1, "Встреча с другом", 0, "2019-12-22"),
    (1, 4, "Купить корм для кота", 0, null),
    (1, 4, "Заказать пиццу", 0, null);

SELECT * FROM projects WHERE user_id=1;

SELECT * FROM tasks WHERE project_id=3;

UPDATE tasks SET status=1 WHERE title="Заказать пиццу";

UPDATE tasks SET title="meeting with a friend" WHERE id=4;

SELECT * from tasks