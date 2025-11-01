-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-11-2025 a las 17:26:27
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
-- Base de datos: `task_management`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `codigo` varchar(100) NOT NULL,
  `color` varchar(7) DEFAULT '#6c757d',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `codigo`, `color`, `fecha_creacion`) VALUES
(10, 'Trabajo', 'TRABAJO', 'blue', '2025-10-31 22:00:14'),
(12, 'Estudios', 'ESTUDIOS', 'purple', '2025-10-31 22:01:05'),
(13, 'Salud', 'SALUD', 'yellow', '2025-10-31 22:01:15'),
(14, 'Finanzas', 'FINANZAS', 'orange', '2025-10-31 22:01:20'),
(15, 'Personal', 'PERSONAL', 'green', '2025-11-01 14:16:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` enum('pendiente','completada') DEFAULT 'pendiente',
  `fecha_vencimiento` date DEFAULT NULL,
  `prioridad` enum('baja','media','alta') DEFAULT 'media',
  `categoria_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tareas`
--

INSERT INTO `tareas` (`id`, `titulo`, `descripcion`, `estado`, `fecha_vencimiento`, `prioridad`, `categoria_id`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(30, 'Preparar informe mensual de ventas', 'Revisar las métricas de desempeño del equipo y preparar el informe para la reunión del lunes.', 'completada', '2025-11-05', 'alta', 10, '2025-10-31 22:02:48', '2025-11-01 15:01:48'),
(32, 'Estudiar para el examen de programación', '\"Repasar los temas de PHP, SQL y API REST.', 'completada', '2025-11-03', 'media', 12, '2025-10-31 22:04:05', '2025-11-01 15:01:46'),
(33, 'Ir al gimnasio', 'Entrenamiento de fuerza y cardio por 1 hora.', 'completada', '2025-11-01', 'baja', 13, '2025-10-31 22:04:35', '2025-11-01 15:01:44'),
(34, 'Pagar factura de electricidad', 'Vencimiento el 10 de noviembre. Revisar saldo antes de pagar.', 'pendiente', '2025-11-10', 'alta', 14, '2025-10-31 22:05:28', '2025-11-01 15:03:02');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD CONSTRAINT `tareas_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
