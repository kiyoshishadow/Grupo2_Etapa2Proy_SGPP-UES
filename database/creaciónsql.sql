-- Base de datos: `sgpp`
-- Script extendido con tablas y datos mínimos

-- ===========================
-- Tablas base existentes
-- ===========================

-- rol
CREATE TABLE IF NOT EXISTS `rol` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `rol` (`id`, `nombre`) VALUES
(1, 'Admin') ON DUPLICATE KEY UPDATE nombre=VALUES(nombre);
INSERT INTO `rol` (`id`, `nombre`) VALUES
(2, 'Docente') ON DUPLICATE KEY UPDATE nombre=VALUES(nombre);
INSERT INTO `rol` (`id`, `nombre`) VALUES
(3, 'Estudiante') ON DUPLICATE KEY UPDATE nombre=VALUES(nombre);

-- usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_usuario_nombre` (`nombre_usuario`),
  KEY `idx_usuario_rol` (`rol_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- NOTA: contraseñas en texto plano para compatibilidad con el login actual.
-- Se recomienda migrar a password_hash() posteriormente.
INSERT INTO `usuario` (`id`, `nombre_usuario`, `contrasena`, `rol_id`) VALUES
(1, 'admin', 'admin123', 1)
ON DUPLICATE KEY UPDATE nombre_usuario=VALUES(nombre_usuario), contrasena=VALUES(contrasena), rol_id=VALUES(rol_id);
INSERT INTO `usuario` (`id`, `nombre_usuario`, `contrasena`, `rol_id`) VALUES
(2, 'docente', 'docente123', 2)
ON DUPLICATE KEY UPDATE nombre_usuario=VALUES(nombre_usuario), contrasena=VALUES(contrasena), rol_id=VALUES(rol_id);
INSERT INTO `usuario` (`id`, `nombre_usuario`, `contrasena`, `rol_id`) VALUES
(3, 'estudiante', 'estudiante123', 3)
ON DUPLICATE KEY UPDATE nombre_usuario=VALUES(nombre_usuario), contrasena=VALUES(contrasena), rol_id=VALUES(rol_id);

-- estudiante
CREATE TABLE IF NOT EXISTS `estudiante` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carnet` varchar(10) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `carrera` varchar(100) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_estudiante_carnet` (`carnet`),
  UNIQUE KEY `uq_estudiante_usuario` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===========================
-- Nuevas tablas
-- ===========================

-- docente
CREATE TABLE IF NOT EXISTS `docente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(120) NOT NULL,
  `departamento` varchar(120) DEFAULT NULL,
  `usuario_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_docente_usuario` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- administrador
CREATE TABLE IF NOT EXISTS `administrador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(120) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_admin_usuario` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- practica
CREATE TABLE IF NOT EXISTS `practica` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `cupo` int(11) DEFAULT NULL,
  `estado` enum('activa','cerrada') NOT NULL DEFAULT 'activa',
  `docente_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_practica_docente` (`docente_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- registro_practica
CREATE TABLE IF NOT EXISTS `registro_practica` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `practica_id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `fecha_postulacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('postulada','aceptada','rechazada','finalizada') NOT NULL DEFAULT 'postulada',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_registro_unico` (`practica_id`,`estudiante_id`),
  KEY `idx_registro_estudiante` (`estudiante_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- resultado
CREATE TABLE IF NOT EXISTS `resultado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registro_id` int(11) NOT NULL,
  `nota` decimal(5,2) DEFAULT NULL,
  `observaciones` text,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_resultado_registro` (`registro_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===========================
-- Foreign Keys
-- ===========================

ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `estudiante`
  ADD CONSTRAINT `fk_estudiante_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `docente`
  ADD CONSTRAINT `fk_docente_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `administrador`
  ADD CONSTRAINT `fk_admin_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `practica`
  ADD CONSTRAINT `fk_practica_docente` FOREIGN KEY (`docente_id`) REFERENCES `docente` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `registro_practica`
  ADD CONSTRAINT `fk_registro_practica` FOREIGN KEY (`practica_id`) REFERENCES `practica` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_registro_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiante` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `resultado`
  ADD CONSTRAINT `fk_resultado_registro` FOREIGN KEY (`registro_id`) REFERENCES `registro_practica` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- ===========================
-- Seeds mínimos adicionales
-- ===========================

-- estudiante (vincular al usuario 3)
INSERT INTO `estudiante` (`id`,`carnet`,`nombre_completo`,`carrera`,`usuario_id`) VALUES
(1,'AB12345','Estudiante Demo','Ing. Sistemas',3)
ON DUPLICATE KEY UPDATE carnet=VALUES(carnet), nombre_completo=VALUES(nombre_completo), carrera=VALUES(carrera), usuario_id=VALUES(usuario_id);

-- docente (vincular al usuario 2)
INSERT INTO `docente` (`id`,`nombre_completo`,`departamento`,`usuario_id`) VALUES
(1,'Docente Demo','Departamento de Informática',2)
ON DUPLICATE KEY UPDATE nombre_completo=VALUES(nombre_completo), departamento=VALUES(departamento), usuario_id=VALUES(usuario_id);

-- administrador (vincular al usuario 1)
INSERT INTO `administrador` (`id`,`nombre_completo`,`usuario_id`) VALUES
(1,'Administrador General',1)
ON DUPLICATE KEY UPDATE nombre_completo=VALUES(nombre_completo), usuario_id=VALUES(usuario_id);

-- practica ejemplo
INSERT INTO `practica` (`id`,`titulo`,`descripcion`,`fecha_inicio`,`fecha_fin`,`cupo`,`estado`,`docente_id`) VALUES
(1,'Práctica Profesional I','Apoyo en desarrollo web con PHP y MySQL','2025-02-01','2025-05-31',10,'activa',1)
ON DUPLICATE KEY UPDATE titulo=VALUES(titulo), descripcion=VALUES(descripcion), fecha_inicio=VALUES(fecha_inicio), fecha_fin=VALUES(fecha_fin), cupo=VALUES(cupo), estado=VALUES(estado), docente_id=VALUES(docente_id);

-- registro del estudiante a la práctica
INSERT INTO `registro_practica` (`id`,`practica_id`,`estudiante_id`,`fecha_postulacion`,`estado`) VALUES
(1,1,1, NOW(),'postulada')
ON DUPLICATE KEY UPDATE practica_id=VALUES(practica_id), estudiante_id=VALUES(estudiante_id), fecha_postulacion=VALUES(fecha_postulacion), estado=VALUES(estado);

-- resultado de evaluación
INSERT INTO `resultado` (`id`,`registro_id`,`nota`,`observaciones`,`fecha`) VALUES
(1,1,9.5,'Excelente desempeño', NOW())
ON DUPLICATE KEY UPDATE registro_id=VALUES(registro_id), nota=VALUES(nota), observaciones=VALUES(observaciones), fecha=VALUES(fecha);
