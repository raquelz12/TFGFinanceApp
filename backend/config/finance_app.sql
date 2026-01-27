-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-01-2026 a las 13:40:31
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
-- Base de datos: `finance_app`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `amount` decimal(10,2) DEFAULT 0.00,
  `completed_amount` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categories`
--

INSERT INTO `categories` (`id`, `user_id`, `name`, `amount`, `completed_amount`, `created_at`) VALUES
(1, NULL, 'Alimentación', 0.00, 0.00, '2026-01-22 16:49:23'),
(2, NULL, 'Transporte', 0.00, 0.00, '2026-01-22 16:49:23'),
(3, NULL, 'Ocio', 0.00, 0.00, '2026-01-22 16:49:23'),
(4, NULL, 'Salud', 0.00, 0.00, '2026-01-22 16:49:23'),
(5, NULL, 'Vivienda', 0.00, 0.00, '2026-01-22 16:49:23'),
(6, NULL, 'Otros', 0.00, 0.00, '2026-01-22 16:49:23'),
(13, 1, 'Alimentación', 436.00, 436.00, '2026-01-22 18:21:00'),
(15, 1, 'Salud', 132.00, 32.00, '2026-01-22 18:25:48'),
(16, 1, 'Transporte', 200.00, 0.00, '2026-01-23 11:00:32'),
(17, 1, 'Ocio', 100.00, 50.00, '2026-01-23 11:00:54'),
(18, NULL, 'Educación', 0.00, 0.00, '2026-01-22 16:49:23'),
(19, NULL, 'Belleza', 0.00, 0.00, '2026-01-22 16:49:23'),
(20, NULL, 'Ropa', 0.00, 0.00, '2026-01-22 16:49:23'),
(21, NULL, 'Tecnología', 0.00, 0.00, '2026-01-22 16:49:23'),
(22, NULL, 'Viajes', 0.00, 0.00, '2026-01-22 16:49:23'),
(23, 1, 'Educación', 100.00, 0.00, '2026-01-23 11:49:43'),
(24, 1, 'Belleza', 50.00, 45.00, '2026-01-26 11:09:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `expenses`
--

INSERT INTO `expenses` (`id`, `user_id`, `name`, `category_id`, `amount`, `created_at`) VALUES
(2, 1, 'Mercadona', 13, 100.00, '2026-01-22 18:26:04'),
(3, 1, 'Mercadona', 13, 100.00, '2026-01-22 18:26:31'),
(4, 1, 'Dia', 13, 50.00, '2026-01-22 18:30:28'),
(5, 1, 'Carrefour', 13, 50.00, '2026-01-22 18:34:27'),
(6, 1, 'Cena con amigos', 17, 25.00, '2026-01-23 11:03:59'),
(7, 1, 'Pastillas alergia', 15, 20.00, '2026-01-26 11:04:53'),
(8, 1, 'Sephora', 24, 45.00, '2026-01-26 11:10:00'),
(9, 1, 'hola12', 13, 10.00, '2026-01-26 13:05:27'),
(10, 1, 'Cena con padres', 17, 25.00, '2026-01-26 13:57:49'),
(11, 1, 'Merca', 13, 26.00, '2026-01-27 11:18:42'),
(12, 1, 'Carrefour', 13, 50.00, '2026-01-27 11:19:41'),
(13, 1, 'Carrefour', 13, 50.00, '2026-01-27 11:20:07'),
(14, 1, 'Pastillas', 15, 12.00, '2026-01-27 11:20:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `objectives`
--

CREATE TABLE `objectives` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `completed_amount` decimal(10,2) DEFAULT 0.00,
  `objective_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `objectives`
--

INSERT INTO `objectives` (`id`, `user_id`, `name`, `amount`, `completed_amount`, `objective_date`, `created_at`) VALUES
(2, 1, 'Viaje a Japón', 2200.00, 300.00, '2029-12-20', '2026-01-26 11:48:41'),
(3, 1, 'Viaje a Gran Canaria', 550.00, 210.00, '2026-08-10', '2026-01-26 11:54:44'),
(5, 1, 'Viaje a Fuengirola', 300.00, 100.00, '2026-07-07', '2026-01-26 13:22:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Raquel Arnaiz', 'raquel@gmail.com', '$2y$10$.W.ujVCiLUoJ5SD3Ttt0RuROu.8YP6ccqCFC4WwsJ0YhX3zxJvkvO', '2026-01-22 16:45:57');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `objectives`
--
ALTER TABLE `objectives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `objectives`
--
ALTER TABLE `objectives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `objectives`
--
ALTER TABLE `objectives`
  ADD CONSTRAINT `objectives_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
