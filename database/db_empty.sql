-- CREATE DATABASE `bluewinds`;

CREATE TABLE `author` (
  `author_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT UNIQUE,
  `author_name` VARCHAR(64) NOT NULL,
  `author_bio` VARCHAR(255) NOT NULL,
  `author_pfp` VARCHAR(255) NOT NULL,
  `author_tiny_pfp` VARCHAR(255) NOT NULL,
  `author_medium_pfp` VARCHAR(255) NOT NULL,
  `author_password` VARCHAR(255) NOT NULL,
  `author_is_mod` BOOLEAN DEFAULT FALSE,
  `author_joined` DATETIME DEFAULT CURRENT_TIMESTAMP()
) ENGINE=InnoDB CHARSET=utf8mb4;

CREATE TABLE `category`(
  `category_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT UNIQUE,
  `category_name` VARCHAR(128) NOT NULL,
  `category_icon` VARCHAR(128) NOT NULL,
  `category_img` VARCHAR(255) NOT NULL
) ENGINE=InnoDB CHARSET=utf8mb4;

CREATE TABLE `article`(
  `article_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `article_name` VARCHAR(255) NOT NULL,
  `article_cover` VARCHAR(255) NOT NULL,
  `article_cover_tiny` VARCHAR(255) NOT NULL,
  `article_cover_medium` VARCHAR(255) NOT NULL,
  `article_more_url` VARCHAR(128) NOT NULL,
  `article_is_video` BOOLEAN DEFAULT FALSE,
  `article_view` INT DEFAULT 0,
  `article_ip` VARBINARY(16) NOT NULL,
  `article_copyright` VARCHAR(255) DEFAULT NULL,
  `article_content` TEXT NOT NULL,
  `article_created` DATETIME DEFAULT CURRENT_TIMESTAMP(),
  `article_category` INT NOT NULL,
  `article_author` INT NOT NULL,
  FOREIGN KEY(`article_category`) REFERENCES `category` (`category_id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY(`article_author`) REFERENCES `author` (`author_id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE
) ENGINE=InnoDB CHARSET=utf8mb4;

CREATE TABLE `comment`(
  `comment_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT UNIQUE,
  `comment_ip` VARBINARY(16) NOT NULL,
  `comment_author` VARCHAR(64) NOT NULL,
  `comment_content` VARCHAR(255) NOT NULL,
  `comment_article_id` INT NOT NULL,
  FOREIGN KEY(`comment_article_id`) REFERENCES `article` (`article_id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE
) ENGINE=InnoDB CHARSET=utf8mb4;