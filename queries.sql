/*Заполнение списка категорий*/
INSERT INTO categories (`title`) VALUES ('Доски и лыжи'), ('Крепления'), ('Ботинки'), ('Одежда'), ('Инструменты'), ('Разное');

/*Добавление новых пользователей*/
INSERT INTO users (`add_date`, `email`, `username`, `password`, `avatar`, `contacts`)
VALUES ('2018-11-05 10:00:01', 'pupkin_ya@gmail.com', 'Василий', '123qwerty', 'img/avatar.jpg', 'тел.89161234567, город Екатеринбург');
INSERT INTO users (`add_date`, `email`, `username`, `password`, `avatar`, `contacts`)
VALUES ('2018-11-10 07:15:04', 'itsmyemail@mail.ru', 'Родион', 'qazqaz1', 'img/avatar.jpg', 'тел. 89514444444');
INSERT INTO users (`add_date`, `email`, `username`, `password`, `contacts`)
VALUES ('2018-11-20 20:24:00', 'shurochka@yandex.ru', 'Александра', 'mishka2018', 'WhatsApp 89619998877, город Москва' );

/*Заполнение списка объявлений*/
INSERT INTO lots (`creation_date`, `author_id`, `category_id`, `title`, `desc`, `picture`, `start_price`, `completion_date`, `step`)
VALUES ('2018-11-20 00:00:00', '1', '6', 'Маска Oakley Canopy', 'Описание маски', 'img/lot-6.jpg', '5400', '2018-12-20 00:00:00', '1');
INSERT INTO lots (`creation_date`, `author_id`, `category_id`, `title`, `desc`, `picture`, `start_price`, `completion_date`, `step`)
VALUES ('2018-11-21 00:00:00', '3', '4', 'Куртка для сноуборда DC Mutiny Charocal', 'Описание куртки', 'img/lot-5.jpg', '7500', '2018-12-21 00:00:00', '1');
INSERT INTO lots (`creation_date`, `author_id`, `category_id`, `title`, `desc`, `picture`, `start_price`, `completion_date`, `step`)
VALUES ('2018-11-22 00:00:00', '3', '3', 'Ботинки для сноуборда DC Mutiny Charocal', 'Описание ботинок', 'img/lot-4.jpg', '10999', '2018-12-22 00:00:00', '100');
INSERT INTO lots (`creation_date`, `author_id`, `category_id`, `title`, `desc`, `picture`, `start_price`, `completion_date`, `step`)
VALUES ('2018-11-23 00:00:00', '2', '2', 'Крепления Union Contact Pro 2015 года размер L/XL', 'Описание креплений', 'img/lot-3.jpg', '8000', '2018-12-23 00:00:00', '5');
INSERT INTO lots (`creation_date`, `author_id`, `category_id`, `title`, `desc`, `picture`, `start_price`, `completion_date`, `step`)
VALUES ('2018-11-24 00:00:00', '2', '1', 'DC Ply Mens 2016/2017 Snowboard', 'Описание доски', 'img/lot-2.jpg', '159999', '2018-12-24 00:00:00', '1');
INSERT INTO lots (`creation_date`, `author_id`, `category_id`, `title`, `desc`, `picture`, `start_price`, `completion_date`, `step`)
VALUES ('2018-11-25 00:00:00', '1', '1', '2014 Rossignol District Snowboard', 'Описание доски', 'img/lot-1.jpg', '10999', '2018-12-25 00:00:00', '10');

/*Добавление ставок*/
INSERT INTO bets (`add_date`, `lot_id`, `user_id`, `bet_amount`)
VALUES ('2018-11-25 17:34:00', '6', '2', '11010');
INSERT INTO bets (`add_date`, `lot_id`, `user_id`, `bet_amount`)
VALUES ('2018-11-25 19:45:00', '6', '3', '11500');
INSERT INTO bets (`add_date`, `lot_id`, `user_id`, `bet_amount`)
VALUES ('2018-11-21 12:25:00', '2', '1', '7600');

/*Получить все категории*/
SELECT `id`, `title` FROM categories;

/*Получить самые новые, открытые лоты*/
SELECT lots.`id`, lots.`title`, `start_price`, `picture`, MAX(`bet_amount`), categories.`title` FROM lots
LEFT JOIN bets ON lots.id = bets.lot_id
INNER JOIN categories ON lots.category_id = categories.id
WHERE `winner_id` IS NULL
GROUP BY lots.`id`
ORDER BY lots.`creation_date` DESC;

/*Показать лот по его id, получить название категории, к которой принадлежит лот*/
SELECT lots.`id`, lots.`title`, categories.`title` FROM lots
INNER JOIN categories ON lots.category_id = categories.id
WHERE lots.`id` = '5';

/*Обновить название лота по его идентификатору*/
UPDATE lots SET `title` = 'Очень крутые ботинки для сноуборда DC Mutiny Charocal'
WHERE `id` = '4';

/*Получить список самых свежих ставок для лота по его идентификатору*/
SELECT lots.`title`, `bet_amount`, bets.`add_date` FROM bets
INNER JOIN lots ON bets.lot_id = lots.id
WHERE `lot_id` = '1'
ORDER BY `add_date` DESC;




