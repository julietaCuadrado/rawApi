INSERT INTO `raw_api`.`person`
(`name`, `email`)
VALUES
('Juan Lillo', 'juan@email.com'),
('Irene Van Der Flush', 'rosa.van.der.flush@email.com'),
('Manuel Alvaro Martínez', 'manumar@email.com')
;
INSERT INTO `raw_api`.`feature`
(`name`)
VALUES
('color de ojos'),
('color del coche'),
('color de la casa')
;

INSERT INTO `raw_api`.`feature_values`
(`person_id`, `feature_id`, `feature_value`)
VALUES
(1,1,'azul claro'),
(1,2,'azul claro'),
(2,1,'azulados'),
(2,2,'azul'),
(2,3,'rojo'),
(3,3,'naranja')
;