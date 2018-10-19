


CREATE TABLE IF NOT EXISTS `persons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL ,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `persons` ( `name`, `created_at`) VALUES
('Juan', now()),
('Irene',  now()),
('Manuel',  now());





CREATE TABLE IF NOT EXISTS `properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL ,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `properties` ( `id`, `name`, `created_at`) VALUES
(1,'color de los ojos', now()),
(2,'color del coche', now()),
(3,'color de la casa', now());






CREATE TABLE IF NOT EXISTS `properties_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL ,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `properties_values` ( `id`, `value`, `created_at`) VALUES
(1,'Azul claro', now()),
(2,'Azulado', now()),
(3,'Azul', now()),
(4,'Rojo', now()),
(5,'Naranja', now());






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

INSERT INTO `persons_properties` ( `iduser`, `idproperty`, `idvalue`, `created_at`) VALUES
(1,1,1,	now()),
(1,2,1, now()),
(2,1,2, now()),
(2,2,3, now()),
(2,3,4, now()),
(3,3,5, now());
