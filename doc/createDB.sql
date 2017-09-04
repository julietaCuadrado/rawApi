
CREATE SCHEMA `raw_api`;
USE `raw_api`;
CREATE TABLE `raw_api`.`person` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT
  , `name` VARCHAR(255) NOT NULL
  , `email` VARCHAR(255) NOT NULL
  , PRIMARY KEY `pk_person` (`id`)
  , UNIQUE KEY `uk_person` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `raw_api`.`feature` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT
  , `name` VARCHAR(100) NOT NULL
  , PRIMARY KEY `pk_feature` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `raw_api`.`feature_value` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT
  , `person_id` INT UNSIGNED NOT NULL
  , `feature_id` INT UNSIGNED NOT NULL
  , `feature_value` VARCHAR(30) NOT NULL
  , PRIMARY KEY `pk_feature_value` (`id`)
  , CONSTRAINT `fk_person_id` FOREIGN KEY `idx_feature_value_person_id` (`person_id`) REFERENCES `person` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
  , CONSTRAINT `fk_feature_id` FOREIGN KEY `idx_feature_value_feature_id` (`feature_id`) REFERENCES `feature` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
