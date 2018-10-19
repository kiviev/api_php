
INSERT INTO `persons` ( `name`, `created_at`) VALUES
('Juan', now()),
('Irene',  now()),
('Manuel',  now());



INSERT INTO `properties` ( `id`, `name`, `created_at`) VALUES
(1,'color de los ojos', now()),
(2,'color del coche', now()),
(3,'color de la casa', now());



INSERT INTO `properties_values` ( `id`, `value`, `created_at`) VALUES
(1,'Azul claro', now()),
(2,'Azulado', now()),
(3,'Azul', now()),
(4,'Rojo', now()),
(5,'Naranja', now());



INSERT INTO `persons_properties` ( `iduser`, `idproperty`, `idvalue`, `created_at`) VALUES
(1,1,1,	now()),
(1,2,1, now()),
(2,1,2, now()),
(2,2,3, now()),
(2,3,4, now()),
(3,3,5, now());
