CREATE TABLE `warecare`.`transaction_category` (`id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(100) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB; 
ALTER TABLE `transaction` CHANGE `category` `category` SMALLINT NOT NULL; 

INSERT INTO `transaction_category` (`id`, `name`) VALUES ('1', 'Category 1'), ('2', 'Category 2');
INSERT INTO `transaction_status` (`id`, `name`) VALUES ('2', 'Unpaid');

ALTER TABLE `transaction` CHANGE `currency` `currency` VARCHAR(10) NOT NULL; 