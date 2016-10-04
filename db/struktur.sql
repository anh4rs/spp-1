SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Table `user_role`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `user_role` (
  `role_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `role_name` VARCHAR(100) NULL DEFAULT NULL ,
  PRIMARY KEY (`role_id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `user` (
  `user_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_name` VARCHAR(100) NULL DEFAULT NULL ,
  `user_full_name` VARCHAR(255) NULL DEFAULT NULL ,
  `user_password` VARCHAR(45) NULL DEFAULT NULL ,
  `user_email` VARCHAR(45) NULL DEFAULT NULL ,
  `user_image` VARCHAR(255) NULL DEFAULT NULL ,
  `user_description` TEXT NULL DEFAULT NULL ,
  `user_role_role_id` INT(11) NULL ,
  `user_is_deleted` TINYINT(1) NULL DEFAULT 0 ,
  `user_input_date` TIMESTAMP NULL DEFAULT NULL ,
  `user_last_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`user_id`) ,
  INDEX `fk_user_user_role1_idx` (`user_role_role_id` ASC) ,
  CONSTRAINT `fk_user_user_role1`
    FOREIGN KEY (`user_role_role_id` )
    REFERENCES `user_role` (`role_id` )
    ON DELETE SET NULL
    ON UPDATE SET NULL)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `activity_log`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `activity_log` (
  `log_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `log_date` TIMESTAMP NULL DEFAULT NULL ,
  `log_action` VARCHAR(45) NULL DEFAULT NULL ,
  `log_module` VARCHAR(45) NULL DEFAULT NULL ,
  `log_info` TEXT NULL DEFAULT NULL ,
  `user_id` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`log_id`) ,
  INDEX `fk_g_activity_log_g_user1_idx` (`user_id` ASC) ,
  CONSTRAINT `fk_g_activity_log_g_user1`
    FOREIGN KEY (`user_id` )
    REFERENCES `user` (`user_id` )
    ON DELETE SET NULL
    ON UPDATE SET NULL)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `ci_sessions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ci_sessions` (
  `id` VARCHAR(40) NOT NULL ,
  `ip_address` VARCHAR(45) NOT NULL ,
  `timestamp` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  `data` BLOB NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `ci_sessions_timestamp` (`timestamp` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `thn_ajaran`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `thn_ajaran` (
  `thn_ajaran_id` INT NOT NULL AUTO_INCREMENT ,
  `thn_ajaran_ket` VARCHAR(45) NULL ,
  `thn_ajaran_status` TINYINT(1) NULL DEFAULT 0 ,
  PRIMARY KEY (`thn_ajaran_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siswa`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `siswa` (
  `siswa_id` INT NOT NULL AUTO_INCREMENT ,
  `siswa_nis` VARCHAR(45) NULL ,
  `siswa_password` VARCHAR(45) NULL ,
  `siswa_nama` VARCHAR(255) NULL ,
  `siswa_tmpt_lhr` VARCHAR(45) NULL ,
  `siswa_tgl_lhr` DATE NULL ,
  `siswa_status` TINYINT(1) NULL DEFAULT 0 ,
  `siswa_input_date` TIMESTAMP NULL ,
  `siswa_last_update` TIMESTAMP NULL ,
  PRIMARY KEY (`siswa_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `jns_byr`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `jns_byr` (
  `jns_byr_id` INT NOT NULL AUTO_INCREMENT ,
  `jns_byr_kategori` VARCHAR(45) NULL ,
  `jns_byr_ket` VARCHAR(100) NULL ,
  PRIMARY KEY (`jns_byr_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bulan`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `bulan` (
  `bulan_id` INT NOT NULL AUTO_INCREMENT ,
  `bulan_nama` VARCHAR(100) NULL ,
  PRIMARY KEY (`bulan_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tarif_byr`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `tarif_byr` (
  `tarif_byr_id` INT NOT NULL AUTO_INCREMENT ,
  `thn_ajaran_thn_ajaran_id` INT NULL ,
  `bulan_bulan_id` INT NULL ,
  `jns_byr_jns_byr_id` INT NULL ,
  `tarif_byr_kategori` DECIMAL NULL ,
  PRIMARY KEY (`tarif_byr_id`) ,
  INDEX `fk_tarif_byr_thn_ajaran1_idx` (`thn_ajaran_thn_ajaran_id` ASC) ,
  INDEX `fk_tarif_byr_bulan1_idx` (`bulan_bulan_id` ASC) ,
  INDEX `fk_tarif_byr_jns_byr1_idx` (`jns_byr_jns_byr_id` ASC) ,
  CONSTRAINT `fk_tarif_byr_thn_ajaran1`
    FOREIGN KEY (`thn_ajaran_thn_ajaran_id` )
    REFERENCES `thn_ajaran` (`thn_ajaran_id` )
    ON DELETE SET NULL
    ON UPDATE SET NULL,
  CONSTRAINT `fk_tarif_byr_bulan1`
    FOREIGN KEY (`bulan_bulan_id` )
    REFERENCES `bulan` (`bulan_id` )
    ON DELETE SET NULL
    ON UPDATE SET NULL,
  CONSTRAINT `fk_tarif_byr_jns_byr1`
    FOREIGN KEY (`jns_byr_jns_byr_id` )
    REFERENCES `jns_byr` (`jns_byr_id` )
    ON DELETE SET NULL
    ON UPDATE SET NULL)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `trx`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `trx` (
  `trx_id` INT NOT NULL AUTO_INCREMENT ,
  `trx_nomor` VARCHAR(45) NULL ,
  `siswa_siswa_id` INT NULL ,
  `thn_ajaran_thn_ajaran_id` INT NULL ,
  `bulan_bulan_id` INT NULL ,
  `jns_byr_jns_byr_id` INT NULL ,
  `tarif_byr_tarif_id` INT NULL ,
  `trx_input_date` TIMESTAMP NULL ,
  `trx_last_update` TIMESTAMP NULL ,
  PRIMARY KEY (`trx_id`) ,
  INDEX `fk_trx_siswa1_idx` (`siswa_siswa_id` ASC) ,
  INDEX `fk_trx_tarif_byr1_idx` (`tarif_byr_tarif_id` ASC) ,
  INDEX `fk_trx_jns_byr1_idx` (`jns_byr_jns_byr_id` ASC) ,
  INDEX `fk_trx_bulan1_idx` (`bulan_bulan_id` ASC) ,
  INDEX `fk_trx_thn_ajaran1_idx` (`thn_ajaran_thn_ajaran_id` ASC) ,
  CONSTRAINT `fk_trx_siswa1`
    FOREIGN KEY (`siswa_siswa_id` )
    REFERENCES `siswa` (`siswa_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_trx_tarif_byr1`
    FOREIGN KEY (`tarif_byr_tarif_id` )
    REFERENCES `tarif_byr` (`tarif_byr_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_trx_jns_byr1`
    FOREIGN KEY (`jns_byr_jns_byr_id` )
    REFERENCES `jns_byr` (`jns_byr_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_trx_bulan1`
    FOREIGN KEY (`bulan_bulan_id` )
    REFERENCES `bulan` (`bulan_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_trx_thn_ajaran1`
    FOREIGN KEY (`thn_ajaran_thn_ajaran_id` )
    REFERENCES `thn_ajaran` (`thn_ajaran_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
