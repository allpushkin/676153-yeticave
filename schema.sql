CREATE DATABASE `yeticave`
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE `yeticave`;

CREATE TABLE `users` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `add_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `email` VARCHAR(128) NOT NULL,
  `username` VARCHAR(64) NOT NULL,
  `password` VARCHAR(64) NOT NULL,
  `avatar` VARCHAR(255),
  `contacts` TEXT NOT NULL
);

CREATE TABLE `categories` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(128) NOT NULL UNIQUE
);

CREATE TABLE `lots` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `creation_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `author_id` INT UNSIGNED NOT NULL,
  `category_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `picture` VARCHAR(255) NOT NULL,
  `start_price` INT UNSIGNED NOT NULL,
  `completion_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `step` INT UNSIGNED NOT NULL,
  `winner_id` INT UNSIGNED
);

CREATE TABLE `bets` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `add_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `lot_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `bet_amount` INT UNSIGNED NOT NULL
);

CREATE UNIQUE INDEX user_email ON users(`email`);
CREATE INDEX cat_title ON categories(`title`);
CREATE INDEX lot_category ON lots(`category_id`);
CREATE INDEX lot_title ON lots(`title`);

