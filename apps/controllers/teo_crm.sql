
CREATE SCHEMA IF NOT EXISTS `teo_crm` DEFAULT CHARACTER SET utf8 ;
USE `teo_crm` ;

-- -----------------------------------------------------
-- Table `teo_crm`.`companies`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teo_crm`.`companies` (
  `company_id` INT NOT NULL AUTO_INCREMENT,
  `company_name` VARCHAR(60) NOT NULL,
  `company_zipcode` INT(5) NOT NULL,
  `city` VARCHAR(45) NOT NULL,
  `activity_area` VARCHAR(45) NULL,
  `company_type` VARCHAR(45) NULL,
  `code_naf` VARCHAR(45) NULL,
  PRIMARY KEY (`company_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teo_crm`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teo_crm`.`users` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `firstname` VARCHAR(45) NULL,
  `lastname` VARCHAR(45) NULL,
  `login` VARCHAR(45) NULL,
  `email` VARCHAR(60) NULL,
  `password` VARCHAR(45) NULL,
  `role_id` INT NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC)  )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teo_crm`.`contacts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teo_crm`.`contacts` (
  `contact_id` INT NOT NULL AUTO_INCREMENT,
  `contact_firstname` VARCHAR(60) NOT NULL,
  `contact_lastname` VARCHAR(60) NOT NULL,
  `contact_email` VARCHAR(60) NOT NULL,
  `phone_number` VARCHAR(15) NULL,
  `function` VARCHAR(60) NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 0,
  `Active` TINYINT(1) NULL DEFAULT 0,
  `interest_level` ENUM("interested", "non_interested", "customer") NULL,
  `company_id` INT NOT NULL,
  `is_manager` TINYINT(1) NULL DEFAULT 0,
  `created_by` DATETIME NULL,
  `created_date` TINYINT(1) NULL,
  `update_date` VARCHAR(45) NULL,
  PRIMARY KEY (`contact_id`, `company_id`),
  INDEX `fk_contacts_companies1_idx` (`company_id` ASC)  ,
  CONSTRAINT `fk_contacts_companies1`
    FOREIGN KEY (`company_id`)
    REFERENCES `teo_crm`.`companies` (`company_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teo_crm`.`desired_services`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teo_crm`.`desired_services` (
  `service_id` INT NOT NULL AUTO_INCREMENT,
  `libelle_service` VARCHAR(45) NULL,
  PRIMARY KEY (`service_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teo_crm`.`roles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teo_crm`.`roles` (
  `role_id` INT NOT NULL AUTO_INCREMENT,
  `role_label` VARCHAR(45) NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`role_id`),
  INDEX `fk_roles_users1_idx` (`user_id` ASC)  ,
  CONSTRAINT `fk_roles_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `teo_crm`.`users` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teo_crm`.`actions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teo_crm`.`actions` (
  `action_id` INT NOT NULL AUTO_INCREMENT,
  `action_label` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`action_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teo_crm`.`roles_has_actions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teo_crm`.`roles_has_actions` (
  `role_id` INT NOT NULL,
  `action_id` INT NOT NULL,
  PRIMARY KEY (`role_id`, `action_id`),
  INDEX `fk_roles_has_actions_actions1_idx` (`action_id` ASC)  ,
  INDEX `fk_roles_has_actions_roles1_idx` (`role_id` ASC)  ,
  CONSTRAINT `fk_roles_has_actions_roles1`
    FOREIGN KEY (`role_id`)
    REFERENCES `teo_crm`.`roles` (`role_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_roles_has_actions_actions1`
    FOREIGN KEY (`action_id`)
    REFERENCES `teo_crm`.`actions` (`action_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teo_crm`.`contacts_desired_services`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teo_crm`.`contacts_desired_services` (
  `contact_id` INT NOT NULL,
  `service_id` INT NOT NULL,
  PRIMARY KEY (`contact_id`, `service_id`),
  INDEX `fk_contacts_has_desired_services_desired_services1_idx` (`service_id` ASC)  ,
  INDEX `fk_contacts_has_desired_services_contacts1_idx` (`contact_id` ASC)  ,
  CONSTRAINT `fk_contacts_has_desired_services_contacts1`
    FOREIGN KEY (`contact_id`)
    REFERENCES `teo_crm`.`contacts` (`contact_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contacts_has_desired_services_desired_services1`
    FOREIGN KEY (`service_id`)
    REFERENCES `teo_crm`.`desired_services` (`service_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teo_crm`.`missions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teo_crm`.`missions` (
  `mission_id` INT NOT NULL AUTO_INCREMENT,
  `mission_label` VARCHAR(45) NULL,
  `contact_id` INT NOT NULL,
  `company_id` INT NOT NULL,
  `mission_date` DATETIME NULL,
  `contacts_contact_id` INT NOT NULL,
  `contacts_company_id` INT NOT NULL,
  PRIMARY KEY (`mission_id`, `contact_id`, `company_id`),
  INDEX `fk_missions_contacts1_idx` (`contact_id` ASC, `company_id` ASC)  ,
  INDEX `fk_missions_contacts2_idx` (`contacts_contact_id` ASC, `contacts_company_id` ASC)  ,
  CONSTRAINT `fk_missions_contacts1`
    FOREIGN KEY (`contact_id` , `company_id`)
    REFERENCES `teo_crm`.`contacts` (`contact_id` , `company_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_missions_contacts2`
    FOREIGN KEY (`contacts_contact_id` , `contacts_company_id`)
    REFERENCES `teo_crm`.`contacts` (`contact_id` , `company_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teo_crm`.`addresses`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teo_crm`.`addresses` (
  `addresse_id` INT NOT NULL AUTO_INCREMENT,
  `addresse_label` VARCHAR(255) NULL,
  `company_id` INT NOT NULL,
  PRIMARY KEY (`addresse_id`, `company_id`),
  INDEX `fk_addresses_companies1_idx` (`company_id` ASC)  ,
  CONSTRAINT `fk_addresses_companies1`
    FOREIGN KEY (`company_id`)
    REFERENCES `teo_crm`.`companies` (`company_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teo_crm`.`mail_templates`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teo_crm`.`mail_templates` (
  `mail_template_id` INT NOT NULL AUTO_INCREMENT,
  `subject` VARCHAR(100) NULL,
  `content` VARCHAR(255) NULL,
  PRIMARY KEY (`mail_template_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teo_crm`.`users_comments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teo_crm`.`users_comments` (
  `user_id` INT NOT NULL,
  `company_id` INT NOT NULL,
  `comment` VARCHAR(255) NULL,
  PRIMARY KEY (`user_id`, `company_id`),
  INDEX `fk_users_has_companies_companies1_idx` (`company_id` ASC)  ,
  INDEX `fk_users_has_companies_users1_idx` (`user_id` ASC)  ,
  CONSTRAINT `fk_users_has_companies_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `teo_crm`.`users` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_has_companies_companies1`
    FOREIGN KEY (`company_id`)
    REFERENCES `teo_crm`.`companies` (`company_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teo_crm`.`crm_logs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teo_crm`.`crm_logs` (
  `log_id` INT NOT NULL AUTO_INCREMENT,
  `hystory_label` VARCHAR(255) NULL,
  `created_by` INT NULL,
  `update_datetime` DATETIME NULL,
  PRIMARY KEY (`log_id`))
ENGINE = InnoDB;

