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
  `thn_ajaran_budget` DECIMAL NULL DEFAULT 0 ,
  PRIMARY KEY (`thn_ajaran_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `kelas`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `kelas` (
  `kelas_id` INT NOT NULL AUTO_INCREMENT ,
  `kelas_ket` VARCHAR(45) NULL ,
  `kelas_tarif` DECIMAL NULL ,
  PRIMARY KEY (`kelas_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siswa`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `siswa` (
  `siswa_id` INT NOT NULL AUTO_INCREMENT ,
  `siswa_nis` VARCHAR(45) NULL ,
  `siswa_password` VARCHAR(45) NULL ,
  `siswa_nama` VARCHAR(255) NULL ,
  `kelas_kelas_id` INT NULL ,
  `siswa_tmpt_lhr` VARCHAR(45) NULL ,
  `siswa_tgl_lhr` DATE NULL ,
  `siswa_status` TINYINT(1) NULL DEFAULT 0 ,
  `siswa_input_date` TIMESTAMP NULL ,
  `siswa_last_update` TIMESTAMP NULL ,
  PRIMARY KEY (`siswa_id`) ,
  INDEX `fk_siswa_kelas1_idx` (`kelas_kelas_id` ASC) ,
  CONSTRAINT `fk_siswa_kelas1`
    FOREIGN KEY (`kelas_kelas_id` )
    REFERENCES `kelas` (`kelas_id` )
    ON DELETE SET NULL
    ON UPDATE SET NULL)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `jns_byr`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `jns_byr` (
  `jns_byr_id` INT NOT NULL AUTO_INCREMENT ,
  `jns_byr_ket` VARCHAR(45) NULL ,
  `jns_byr_tarif` DECIMAL NULL ,
  PRIMARY KEY (`jns_byr_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `trx_spp`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `trx_spp` (
  `trx_spp_id` INT NOT NULL AUTO_INCREMENT ,
  `thn_ajaran_thn_ajaran_id` INT NULL ,
  `kelas_kelas_id` INT NULL ,
  `siswa_siswa_id` INT NULL ,
  `trx_spp_jul` DECIMAL NULL ,
  `trx_spp_ags` DECIMAL NULL ,
  `trx_spp_sep` DECIMAL NULL ,
  `trx_spp_okt` DECIMAL NULL ,
  `trx_spp_nov` DECIMAL NULL ,
  `trx_spp_des` DECIMAL NULL ,
  `trx_spp_jan` DECIMAL NULL ,
  `trx_spp_feb` DECIMAL NULL ,
  `trx_spp_mar` DECIMAL NULL ,
  `trx_spp_apr` DECIMAL NULL ,
  `trx_spp_mei` DECIMAL NULL ,
  `trx_spp_jun` DECIMAL NULL ,
  `user_user_id` INT NULL ,
  `trx_spp_input_date` TIMESTAMP NULL ,
  `trx_spp_last_update` TIMESTAMP NULL ,
  PRIMARY KEY (`trx_spp_id`) ,
  INDEX `fk_trx_spp_siswa1_idx` (`siswa_siswa_id` ASC) ,
  INDEX `fk_trx_spp_thn_ajaran1_idx` (`thn_ajaran_thn_ajaran_id` ASC) ,
  INDEX `fk_trx_spp_kelas1_idx` (`kelas_kelas_id` ASC) ,
  CONSTRAINT `fk_trx_spp_siswa1`
    FOREIGN KEY (`siswa_siswa_id` )
    REFERENCES `siswa` (`siswa_id` )
    ON DELETE SET NULL
    ON UPDATE SET NULL,
  CONSTRAINT `fk_trx_spp_thn_ajaran1`
    FOREIGN KEY (`thn_ajaran_thn_ajaran_id` )
    REFERENCES `thn_ajaran` (`thn_ajaran_id` )
    ON DELETE SET NULL
    ON UPDATE SET NULL,
  CONSTRAINT `fk_trx_spp_kelas1`
    FOREIGN KEY (`kelas_kelas_id` )
    REFERENCES `kelas` (`kelas_id` )
    ON DELETE SET NULL
    ON UPDATE SET NULL)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `trx_bebas`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `trx_bebas` (
  `trx_bebas_id` INT NOT NULL AUTO_INCREMENT ,
  `thn_ajaran_thn_ajaran_id` INT NULL ,
  `siswa_siswa_id` INT NULL ,
  `jns_byr_jns_byr_id` INT NULL ,
  `trx_bebas_cicil` DECIMAL NULL DEFAULT 0 ,
  `user_user_id` INT NULL ,
  `trx_bebas_input_date` TIMESTAMP NULL ,
  `trx_bebas_last_update` TIMESTAMP NULL ,
  PRIMARY KEY (`trx_bebas_id`) ,
  INDEX `fk_trx_bebas_thn_ajaran1_idx` (`thn_ajaran_thn_ajaran_id` ASC) ,
  INDEX `fk_trx_bebas_siswa1_idx` (`siswa_siswa_id` ASC) ,
  INDEX `fk_trx_bebas_jns_byr1_idx` (`jns_byr_jns_byr_id` ASC) ,
  CONSTRAINT `fk_trx_bebas_thn_ajaran1`
    FOREIGN KEY (`thn_ajaran_thn_ajaran_id` )
    REFERENCES `thn_ajaran` (`thn_ajaran_id` )
    ON DELETE SET NULL
    ON UPDATE SET NULL,
  CONSTRAINT `fk_trx_bebas_siswa1`
    FOREIGN KEY (`siswa_siswa_id` )
    REFERENCES `siswa` (`siswa_id` )
    ON DELETE SET NULL
    ON UPDATE SET NULL,
  CONSTRAINT `fk_trx_bebas_jns_byr1`
    FOREIGN KEY (`jns_byr_jns_byr_id` )
    REFERENCES `jns_byr` (`jns_byr_id` )
    ON DELETE SET NULL
    ON UPDATE SET NULL)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
