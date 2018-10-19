


CREATE TABLE IF NOT EXISTS `persons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL ,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL ,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `properties_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL ,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `persons_properties` (
  `iduser` int(11) NOT NULL,
  `idproperty` int(11) NOT NULL,
  `idvalue` int(11) NOT NULL,
  `created_at` timestamp NOT NULL ,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`iduser`,`idproperty`),
  FOREIGN KEY (`iduser`) REFERENCES persons(`id`),
  FOREIGN KEY (`idproperty`) REFERENCES properties(`id`),
  FOREIGN KEY (`idvalue`) REFERENCES properties_values(`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
