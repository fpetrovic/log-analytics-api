use `log-analytics-api`;

CREATE TABLE `log` (
  `id` BIGINT AUTO_INCREMENT NOT NULL,
  `service_name` VARCHAR(255) NOT NULL,
  `request_method` VARCHAR(7) NOT NULL,
  `uri` VARCHAR(255) NOT NULL,
  `status_code` INT NOT NULL,
  `created_at` DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY(`id`)
  ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
  ;

ALTER TABLE `log` ADD INDEX `service_name_index` (`service_name`);
ALTER TABLE `log` ADD INDEX `created_at_index` (`created_at`);
ALTER TABLE `log` ADD INDEX `response_code_index` (`status_code`);
