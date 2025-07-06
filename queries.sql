-- Добавление существующего списка категорий
INSERT INTO categories (name, symbol_code)
VALUES ('Доски и лыжи', 'boards'),
       ('Крепления', 'attachment'),
       ('Ботинки', 'boots'),
       ('Одежда', 'clothing'),
       ('Инструменты', 'tools'),
       ('Разное', 'other');

-- Добавление пользователей
INSERT INTO users (email, name, password, message)
VALUES ('test@yeticave.ru', 'Петр', '$2y$10$qSbAVpjvxewzDSspCzBoGOPqSZS7Nww.7F68c.NVyHTIdQyrEus76', 'Пользователь 1'),
       ('example@yeticave.ru', 'Екатерина', '$2y$10$UKq1J13eEhojujHFFSsfB.sLyAOv1N7DaA70bnSTSyhotTls2H0m6', 'Пользователь 2');

-- Добавление существующего списка объявлений
INSERT INTO lots (name, category_id, description, img, start_price, date_end, bet_step, user_id)
VALUES ('2014 Rossignol District Snowboard', 1, '', 'img/lot-1.jpg', 10999, '2025-06-02', 100, 2),
       ('DC Ply Mens 2016/2017 Snowboard', 1, '', 'img/lot-2.jpg', 15999, '2025-06-03', 200, 1),
       ('Крепления Union Contact Pro 2015 года размер L/XL', 2, '', 'img/lot-3.jpg', 8000, '2025-06-04', 300, 2),
       ('Ботинки для сноуборда DC Mutiny Charocal', 3, '', 'img/lot-4.jpg', 10999, '2025-06-05', 400, 1),
       ('Куртка для сноуборда DC Mutiny Charocal', 4, '', 'img/lot-5.jpg', 7500, '2025-06-06', 500, 2),
       ('Маска Oakley Canopy', 6, '', 'img/lot-6.jpg', 5400, '2025-06-07', 600, 1);

-- Добавление ставок для любого объявления
INSERT INTO bets (price, user_id, lot_id)
VALUES (10000, 2, 4),
       (15000, 1, 3);

-- Получить все категории
SELECT * FROM categories;

-- Получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории;
SELECT l.name, l.start_price, l.img, b.price, c.name as category_name FROM lots l
JOIN categories c ON l.category_id = c.id
LEFT JOIN bets b ON l.id = b.lot_id
ORDER BY l.created_at DESC;

-- Показать лот по его ID. Получите также название категории, к которой принадлежит лот;
SELECT l.*, c.name as category_name FROM lots l
JOIN categories c ON l.category_id = c.id
WHERE l.id = 1;

-- Обновить название лота по его идентификатору;
UPDATE lots SET name = 'Новый лот' WHERE id = 1;

-- Получить список ставок для лота по его идентификатору с сортировкой по дате.
SELECT b.* FROM bets b
JOIN lots l ON b.lot_id = l.id
WHERE l.id = 3
ORDER BY created_at DESC;
