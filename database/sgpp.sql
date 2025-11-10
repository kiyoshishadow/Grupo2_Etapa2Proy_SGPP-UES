-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-11-2025 a las 01:05:00
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sgpp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `id` int(11) NOT NULL,
  `nombre_completo` varchar(120) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `avance_expediente`
--

CREATE TABLE `avance_expediente` (
  `id` int(11) NOT NULL,
  `expediente_id` int(11) NOT NULL,
  `tipo` enum('hito','reunion','observacion') NOT NULL DEFAULT 'observacion',
  `descripcion` text DEFAULT NULL,
  `porcentaje_avance` decimal(5,2) DEFAULT NULL,
  `registrado_por` int(11) DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `avance_expediente`
--

INSERT INTO `avance_expediente` (`id`, `expediente_id`, `tipo`, `descripcion`, `porcentaje_avance`, `registrado_por`, `fecha_registro`) VALUES
(2, 17, 'hito', '100%', 10.00, 2, '2025-11-10 00:52:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente`
--

CREATE TABLE `docente` (
  `id` int(11) NOT NULL,
  `nombre_completo` varchar(120) NOT NULL,
  `departamento` varchar(120) DEFAULT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `docente`
--

INSERT INTO `docente` (`id`, `nombre_completo`, `departamento`, `usuario_id`) VALUES
(2, 'Dr. Juan Pérez', 'Departamento de Ingeniería', 12),
(3, 'Lic', 'Informatica', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `giro` varchar(150) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `contacto_principal` varchar(150) DEFAULT NULL,
  `telefono` varchar(25) DEFAULT NULL,
  `correo` varchar(120) DEFAULT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`id`, `nombre`, `giro`, `direccion`, `contacto_principal`, `telefono`, `correo`, `activa`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(8, 'Telecom S.A. de C.V.', 'Telecomunicaciones', 'Av. Sur 123, San Salvador', 'Carlos Méndez', '2222-1111', 'contacto@telecom.sv', 1, '2025-11-09 16:54:03', '2025-11-09 16:54:03'),
(9, 'Constructora del Istmo', 'Construcción', 'Centro Histórico #456, Santa Tecla', 'Ana Rivera', '2222-2222', 'info@constructoraistmo.com', 1, '2025-11-09 16:54:03', '2025-11-09 16:54:03'),
(10, 'Industrias Alimenticias Central', 'Alimentos', 'Zona Franca #789, San Miguel', 'Roberto Castro', '2222-3333', 'ventas@industriascentral.com.sv', 1, '2025-11-09 16:54:03', '2025-11-09 16:54:03'),
(11, 'Banco Centroamericano', 'Servicios Financieros', ' Blvd. del Hipódromo #101, San Salvador', 'Patricia Vargas', '2222-4444', 'rrhh@bancoca.com', 1, '2025-11-09 16:54:04', '2025-11-09 16:54:04'),
(12, 'Hospital Nacional', 'Salud', 'Av. Independencia #202, San Salvador', 'Dr. Luis Hernández', '2222-5555', 'practicas@hospitalnacional.sv', 1, '2025-11-09 16:54:04', '2025-11-09 16:54:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

CREATE TABLE `estudiante` (
  `id` int(11) NOT NULL,
  `carnet` varchar(10) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `carrera` varchar(100) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiante`
--

INSERT INTO `estudiante` (`id`, `carnet`, `nombre_completo`, `carrera`, `usuario_id`) VALUES
(3, 'AB202301', 'María González Rodríguez', 'Ingeniería de Sistemas', 4),
(4, 'AB202302', 'Carlos Antonio Pérez', 'Ingeniería Civil', 5),
(5, 'AB202303', 'Ana Sofía Martínez', 'Ingeniería Industrial', 6),
(6, 'AB202304', 'Luis Fernando Hernández', 'Ingeniería Electrónica', 7),
(7, 'AB202305', 'Patricia Elena López', 'Ingeniería Química', 8),
(8, 'AB202306', 'Roberto Carlos Castro', 'Ingeniería Mecánica', 9),
(9, 'AB202307', 'Sofía Beatriz Rivera', 'Arquitectura', 10),
(10, 'AB202308', 'José Antonio Vargas', 'Ingeniería de Sistemas', 11),
(11, 'KSJDSÑLDS', 'Estudiante', 'Ingeniera en sistemas informáticos', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluacion`
--

CREATE TABLE `evaluacion` (
  `id` int(11) NOT NULL,
  `expediente_id` int(11) NOT NULL,
  `nota_final` decimal(5,2) DEFAULT NULL,
  `comentario_docente` text DEFAULT NULL,
  `docente_id` int(11) NOT NULL COMMENT 'ID del docente que realiza la evaluación',
  `fecha_evaluacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `evaluacion`
--

INSERT INTO `evaluacion` (`id`, `expediente_id`, `nota_final`, `comentario_docente`, `docente_id`, `fecha_evaluacion`) VALUES
(3, 8, 8.50, 'Excelente desempeño en proyectos de arquitectura.', 2, '2025-10-31 23:56:27'),
(4, 3, 9.00, 'Gran capacidad de análisis y programación.', 2, '2025-11-08 23:56:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expediente`
--

CREATE TABLE `expediente` (
  `id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `empresa_id` int(11) DEFAULT NULL,
  `empresa_nombre` varchar(150) DEFAULT NULL,
  `empresa_direccion` varchar(255) DEFAULT NULL,
  `supervisor_externo` varchar(150) DEFAULT NULL,
  `telefono_supervisor` varchar(25) DEFAULT NULL,
  `correo_supervisor` varchar(120) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` enum('planeado','activo','en_pausa','finalizado','cancelado') NOT NULL DEFAULT 'planeado',
  `horas_planificadas` int(11) DEFAULT NULL,
  `horas_cumplidas` int(11) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `expediente`
--

INSERT INTO `expediente` (`id`, `estudiante_id`, `empresa_id`, `empresa_nombre`, `empresa_direccion`, `supervisor_externo`, `telefono_supervisor`, `correo_supervisor`, `fecha_inicio`, `fecha_fin`, `estado`, `horas_planificadas`, `horas_cumplidas`, `observaciones`, `creado_en`, `actualizado_en`) VALUES
(3, 3, 8, NULL, NULL, 'Ing. Carlos Méndez', NULL, NULL, '2025-08-01', '2025-12-15', 'activo', 500, NULL, NULL, '2025-11-09 16:56:06', '2025-11-09 16:56:06'),
(4, 4, 9, NULL, NULL, 'Arq. Ana Rivera', NULL, NULL, '2025-07-15', '2025-11-30', 'activo', 480, NULL, NULL, '2025-11-09 16:56:06', '2025-11-09 16:56:06'),
(5, 5, 10, NULL, NULL, 'Ing. Roberto Castro', NULL, NULL, '2025-08-10', '2025-12-20', 'activo', 520, NULL, NULL, '2025-11-09 16:56:06', '2025-11-09 16:56:06'),
(6, 6, 11, NULL, NULL, 'Lic. Patricia Vargas', NULL, NULL, '2025-06-01', '2025-10-15', 'activo', 450, NULL, NULL, '2025-11-09 16:56:06', '2025-11-09 16:56:06'),
(7, 7, 12, NULL, NULL, 'Dr. Luis Hernández', NULL, NULL, '2025-09-01', '2026-01-15', 'activo', 600, NULL, NULL, '2025-11-09 16:56:06', '2025-11-09 16:56:06'),
(8, 8, 9, NULL, NULL, 'Arq. Ana Rivera', NULL, NULL, '2025-05-01', '2025-09-30', 'finalizado', 400, NULL, NULL, '2025-11-09 16:56:06', '2025-11-09 16:56:06'),
(9, 9, 8, NULL, NULL, 'Ing. Carlos Méndez', NULL, NULL, '2025-07-20', '2025-12-01', 'activo', 500, NULL, NULL, '2025-11-09 16:56:06', '2025-11-09 16:56:06'),
(10, 3, 8, NULL, NULL, 'Ing. Carlos Méndez', NULL, NULL, '2025-08-01', '2025-12-15', 'activo', 500, NULL, NULL, '2025-11-09 16:56:27', '2025-11-09 16:56:27'),
(11, 4, 9, NULL, NULL, 'Arq. Ana Rivera', NULL, NULL, '2025-07-15', '2025-11-30', 'activo', 480, NULL, NULL, '2025-11-09 16:56:27', '2025-11-09 16:56:27'),
(12, 5, 10, NULL, NULL, 'Ing. Roberto Castro', NULL, NULL, '2025-08-10', '2025-12-20', 'activo', 520, NULL, NULL, '2025-11-09 16:56:27', '2025-11-09 16:56:27'),
(13, 6, 11, NULL, NULL, 'Lic. Patricia Vargas', NULL, NULL, '2025-06-01', '2025-10-15', 'activo', 450, NULL, NULL, '2025-11-09 16:56:27', '2025-11-09 16:56:27'),
(14, 7, 12, NULL, NULL, 'Dr. Luis Hernández', NULL, NULL, '2025-09-01', '2026-01-15', 'activo', 600, NULL, NULL, '2025-11-09 16:56:27', '2025-11-09 16:56:27'),
(15, 8, 9, NULL, NULL, 'Arq. Ana Rivera', NULL, NULL, '2025-05-01', '2025-09-30', 'finalizado', 400, NULL, NULL, '2025-11-09 16:56:27', '2025-11-09 16:56:27'),
(16, 9, 8, NULL, NULL, 'Ing. Carlos Méndez', NULL, NULL, '2025-07-20', '2025-12-01', 'activo', 500, NULL, NULL, '2025-11-09 16:56:27', '2025-11-09 16:56:27'),
(17, 5, 12, 'Empresa x', 'empresax', 'EMPRESA X', '231212', 'supervisor1@gmail.com', '2025-11-09', '2025-11-11', 'activo', 500, 10, '', '2025-11-09 17:51:32', '2025-11-09 17:51:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informe_mensual`
--

CREATE TABLE `informe_mensual` (
  `id` int(11) NOT NULL,
  `expediente_id` int(11) NOT NULL,
  `periodo` date NOT NULL,
  `ruta_archivo` varchar(255) DEFAULT NULL,
  `comentario_estudiante` text DEFAULT NULL,
  `estado_revision` enum('pendiente','recibido','observado','aprobado') NOT NULL DEFAULT 'pendiente',
  `comentario_revisor` text DEFAULT NULL,
  `revisor_id` int(11) DEFAULT NULL,
  `fecha_subida` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_revision` datetime DEFAULT NULL,
  `hash_archivo` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `informe_mensual`
--

INSERT INTO `informe_mensual` (`id`, `expediente_id`, `periodo`, `ruta_archivo`, `comentario_estudiante`, `estado_revision`, `comentario_revisor`, `revisor_id`, `fecha_subida`, `fecha_revision`, `hash_archivo`) VALUES
(3, 3, '2025-08-01', '/uploads/informe_maria_agosto.pdf', 'Actividades de análisis y desarrollo de software.', 'aprobado', NULL, 2, '2025-10-22 23:56:27', NULL, NULL),
(4, 4, '2025-07-01', '/uploads/informe_carlos_julio.pdf', 'Trabajo en obra de construcción civil.', 'pendiente', NULL, 2, '2025-11-07 23:56:27', NULL, NULL),
(5, 5, '2025-08-01', '/uploads/informe_ana_agosto.pdf', 'Procesos industriales y control de calidad.', '', '', 2, '2025-11-05 23:56:27', '2025-11-10 00:53:15', NULL),
(6, 6, '2025-06-01', '/uploads/informe_luis_junio.pdf', 'Mantenimiento de sistemas electrónicos.', 'aprobado', NULL, 2, '2025-10-13 23:56:27', NULL, NULL),
(7, 7, '2025-09-01', '/uploads/informe_patricia_septiembre.pdf', 'Análisis financieros y contabilidad.', 'pendiente', NULL, 2, '2025-10-28 23:56:27', NULL, NULL),
(8, 10, '0000-00-00', '/uploads/informe_3_2025-11-10_00-03-53_Etapa 2 TOO115 2025 (1).pdf', '', 'pendiente', NULL, NULL, '2025-11-10 00:03:53', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id`, `nombre`) VALUES
(1, 'Admin'),
(2, 'Docente'),
(3, 'Estudiante');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre_usuario`, `contrasena`, `rol_id`, `activo`) VALUES
(1, 'admin', 'admin123', 1, 1),
(2, 'docente', '$2y$10$103SEuV3OwZVKo4BNZkPfe1G1C3dVAUuJ6KOnExM/LG.jj4XNKPsm', 2, 1),
(3, 'estudiante', '$2y$10$LqUN7WFFq9ooZHrGf/QIROKy9JeXRKcz08SrLxH9WfNHasYGAU7eO', 3, 1),
(4, 'estudiante4', 'est4123', 3, 1),
(5, 'estudiante5', 'est5123', 3, 1),
(6, 'estudiante6', 'est6123', 3, 1),
(7, 'estudiante7', 'est7123', 3, 1),
(8, 'estudiante8', 'est8123', 3, 1),
(9, 'estudiante9', 'est9123', 3, 1),
(10, 'estudiante10', 'est10123', 3, 1),
(11, 'estudiante11', 'est11123', 3, 1),
(12, 'docentedemo', 'doc123', 2, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_admin_usuario` (`usuario_id`);

--
-- Indices de la tabla `avance_expediente`
--
ALTER TABLE `avance_expediente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_avance_expediente` (`expediente_id`),
  ADD KEY `idx_avance_tipo` (`tipo`),
  ADD KEY `fk_avance_registrado` (`registrado_por`);

--
-- Indices de la tabla `docente`
--
ALTER TABLE `docente`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_docente_usuario` (`usuario_id`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_empresa_nombre` (`nombre`);

--
-- Indices de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_estudiante_carnet` (`carnet`),
  ADD UNIQUE KEY `uq_estudiante_usuario` (`usuario_id`);

--
-- Indices de la tabla `evaluacion`
--
ALTER TABLE `evaluacion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_evaluacion_expediente` (`expediente_id`),
  ADD KEY `idx_evaluacion_docente` (`docente_id`);

--
-- Indices de la tabla `expediente`
--
ALTER TABLE `expediente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_expediente_estudiante` (`estudiante_id`),
  ADD KEY `idx_expediente_empresa` (`empresa_id`),
  ADD KEY `idx_expediente_estado` (`estado`);

--
-- Indices de la tabla `informe_mensual`
--
ALTER TABLE `informe_mensual`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_informe_periodo` (`expediente_id`,`periodo`),
  ADD KEY `idx_informe_estado` (`estado_revision`),
  ADD KEY `fk_informe_revisor` (`revisor_id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_usuario_nombre` (`nombre_usuario`),
  ADD KEY `idx_usuario_rol` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `avance_expediente`
--
ALTER TABLE `avance_expediente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `docente`
--
ALTER TABLE `docente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `evaluacion`
--
ALTER TABLE `evaluacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `expediente`
--
ALTER TABLE `expediente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `informe_mensual`
--
ALTER TABLE `informe_mensual`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD CONSTRAINT `fk_admin_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `avance_expediente`
--
ALTER TABLE `avance_expediente`
  ADD CONSTRAINT `fk_avance_expediente` FOREIGN KEY (`expediente_id`) REFERENCES `expediente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_avance_registrado` FOREIGN KEY (`registrado_por`) REFERENCES `docente` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `docente`
--
ALTER TABLE `docente`
  ADD CONSTRAINT `fk_docente_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD CONSTRAINT `fk_estudiante_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `evaluacion`
--
ALTER TABLE `evaluacion`
  ADD CONSTRAINT `fk_evaluacion_docente` FOREIGN KEY (`docente_id`) REFERENCES `docente` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_evaluacion_expediente` FOREIGN KEY (`expediente_id`) REFERENCES `expediente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `expediente`
--
ALTER TABLE `expediente`
  ADD CONSTRAINT `fk_expediente_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_expediente_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiante` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `informe_mensual`
--
ALTER TABLE `informe_mensual`
  ADD CONSTRAINT `fk_informe_expediente` FOREIGN KEY (`expediente_id`) REFERENCES `expediente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_informe_revisor` FOREIGN KEY (`revisor_id`) REFERENCES `docente` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
