CREATE TABLE logger.otm (
  id int(11) NOT NULL AUTO_INCREMENT,
  otm varchar(255) DEFAULT NULL,
  object varchar(255) DEFAULT NULL,
  control_criterion varchar(255) NOT NULL,
  start_date date DEFAULT NULL,
  end_date date DEFAULT NULL,
  active tinyint(1) DEFAULT NULL,
  timestamp timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 2,
AVG_ROW_LENGTH = 16384,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

CREATE TABLE logger.data_text (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_otm int(11) DEFAULT NULL,
  text varchar(255) DEFAULT NULL,
  timestamp varchar(255) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

ALTER TABLE logger.data_text
ADD CONSTRAINT FK_data_text_id_otm FOREIGN KEY (id_otm)
REFERENCES logger.otm (id) ON DELETE NO ACTION;

CREATE TABLE logger.data_audio (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_otm int(11) DEFAULT NULL,
  audio varchar(255) DEFAULT NULL,
  timestamp varchar(255) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

ALTER TABLE logger.data_audio
ADD CONSTRAINT FK_data_audio_id_otm FOREIGN KEY (id_otm)
REFERENCES logger.otm (id) ON DELETE NO ACTION;

CREATE TABLE logger.data_images (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_otm int(11) DEFAULT NULL,
  images varchar(255) DEFAULT NULL,
  timestamp varchar(255) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

ALTER TABLE logger.data_images
ADD CONSTRAINT FK_data_images_id_otm FOREIGN KEY (id_otm)
REFERENCES logger.otm (id) ON DELETE NO ACTION;

CREATE TABLE logger.data_db_skype (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_otm int(11) DEFAULT NULL,
  db_skype varchar(255) DEFAULT NULL,
  timestamp varchar(255) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

ALTER TABLE logger.data_db_skype
ADD CONSTRAINT FK_data_db_skype_id_otm FOREIGN KEY (id_otm)
REFERENCES logger.otm (id) ON DELETE NO ACTION;

CREATE TABLE logger.data_db_viber (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_otm int(11) DEFAULT NULL,
  db_viber varchar(255) DEFAULT NULL,
  timestamp varchar(255) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

ALTER TABLE logger.data_db_viber
ADD CONSTRAINT FK_data_db_viber_id_otm FOREIGN KEY (id_otm)
REFERENCES logger.otm (id) ON DELETE NO ACTION;