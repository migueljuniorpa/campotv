Instalação
1 - Crie um banco de dados com nome "campotv".
2 - Crie a tabela dentro do banco criando.
  CREATE TABLE `campotv`.`news` (
  `id` INT NOT NULL AUTO_INCREMEN,
  `title` VARCHAR(255) NULL,
  `description` VARCHAR(255) NULL,
  `image` VARCHAR(255) NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`));
  
