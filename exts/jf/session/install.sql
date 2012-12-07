CREATE  TABLE IF NOT EXISTS `session` (
  `sid` VARCHAR(255) NOT NULL ,
  `last_actived_request` VARCHAR(255) NULL ,
  `last_actived_time` DATETIME NULL ,
  `data` VARCHAR(4096) NULL ,
  PRIMARY KEY (`sid`) )
ENGINE = MyISAM;