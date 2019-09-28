-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-09-2019 a las 18:00:46
-- Versión del servidor: 10.4.6-MariaDB
-- Versión de PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inventariostroystone`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

CREATE TABLE `articulos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idcategoria` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `terminado` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `largo` decimal(4,2) NOT NULL,
  `ancho` decimal(4,2) NOT NULL,
  `metros_cuadrados` decimal(4,2) NOT NULL,
  `espesor` decimal(4,2) NOT NULL,
  `ubicacion` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int(11) NOT NULL,
  `descripcion` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observacion` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `origen` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_llegada` date DEFAULT NULL,
  `file` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `articulos`
--

INSERT INTO `articulos` (`id`, `idcategoria`, `codigo`, `sku`, `nombre`, `terminado`, `largo`, `ancho`, `metros_cuadrados`, `espesor`, `ubicacion`, `stock`, `descripcion`, `observacion`, `origen`, `fecha_llegada`, `file`, `condicion`, `created_at`, `updated_at`) VALUES
(1, 1, '819197179', 'TSGBCKSTORM19', 'Black Storm', 'Pulido - Flameado', '2.00', '3.00', '5.89', '0.80', 'Escultores', 5, 'Granito BalckStorm Exotico Negro Elegante', NULL, 'Brazil Contenedor 19AS910', '2019-09-10', '5k4iTvByDpkTaFMj.jpg', 1, '2019-09-24 21:10:44', '2019-09-24 21:10:44'),
(2, 1, '9081908219', 'TSGSNGABN19', 'San Gabriel', 'Pulido - Leather', '2.00', '4.00', '5.60', '1.00', 'Músico', 6, 'Granito San Gabriel Negro Elegante Economico', NULL, 'India CASRT1', '2019-09-17', 'Xj7agv33CcnOifO1.jpg', 0, '2019-09-24 21:13:58', '2019-09-24 21:18:02'),
(3, 1, '9817917', 'TSGFUSSI19', 'Fussion', 'Pulido - Flameado', '1.98', '2.70', '4.50', '1.20', 'Escultores', 3, 'GranitoFussion Blanco Elegante', NULL, 'España CKJHA1', '2019-09-10', '5kfJaPzEnlJnGOuK.jpg', 1, '2019-09-24 21:17:16', '2019-09-24 21:17:16'),
(4, 1, '8971718908', 'TSGKOZ19', 'Kozmuz', 'Pulido', '1.79', '2.80', '5.40', '1.50', 'Escultores', 7, 'Granito Kozmuz Exotico Red Elegante', NULL, 'India', '2019-09-17', 'PeqommnZIV1JSDhC.jpg', 1, '2019-09-24 21:21:43', '2019-09-24 21:21:43'),
(5, 1, '617862187', 'TSGLENN19', 'Lennon', 'Pulido - Leather', '1.97', '3.00', '5.90', '0.76', 'Músico', 4, 'Granito Lennon Economico Blanco', NULL, 'India', NULL, 'BzEgmlg7EebRTMsF.jpg', 1, '2019-09-24 21:24:26', '2019-09-24 21:24:26'),
(6, 1, '9871098109', 'TSGMANHTTBCK1', 'Manhattan Black', 'Pulido', '1.70', '2.80', '4.90', '1.20', 'Escultores', 1, 'Granito Manhattan Black Negro Elegante Exotico', 'Descuadre en esquinas izquierda y derecha', 'Brazil', '2019-09-19', 'xgfOaWEVPqtADNFq.jpg', 1, '2019-09-24 21:31:07', '2019-09-24 21:31:07'),
(7, 1, '9871899879', 'TSGMasKay', 'MasKayruz', 'Leather - Pulido', '1.70', '3.20', '5.39', '1.20', 'Músico', 5, 'Granito MasKayruz Beige Economico Café', NULL, 'India', NULL, 'jgqG3KPABlf72M0B.jpg', 0, '2019-09-24 21:34:02', '2019-09-24 21:37:06'),
(8, 1, '76178160999', 'TSGMETALLI18', 'Metallicus', 'Pulido - Leather', '2.00', '3.00', '6.00', '1.60', 'Escultores', 4, 'Granito Metallicus Exotico Negro Brillante', NULL, 'Brazil C70AH1', '2019-09-13', 'ITh0MqxQ96flNKNt.jpg', 1, '2019-09-24 21:36:25', '2019-09-24 21:36:25'),
(9, 1, '8971982798', 'TSGMETEOU1', 'Meteorus', 'Pulido', '2.80', '2.70', '5.20', '1.50', 'Escultores', 9, 'Granito Meteorus Exotico Elegante Negro Red', NULL, 'Brazil A19SOA', NULL, 'K52j7foav4GQu8Fs.jpg', 1, '2019-09-24 21:39:29', '2019-09-24 21:39:29'),
(10, 1, '781689290', 'TSGBCOITAU1', 'Blanco Itaunas', 'Pulido - Flameado', '1.00', '2.20', '2.40', '1.00', 'Escultores', 6, 'Granito Blanco Itaunas MediaPlaca', NULL, 'España AHSD91', '2019-09-17', 'XbwrbwXCIsgX45Tm.jpg', 1, '2019-09-24 21:41:42', '2019-09-24 21:41:42'),
(11, 1, '189720209', 'TSGBCOPOLA1', 'Blanco Polar', 'Pulido', '0.90', '2.00', '1.90', '0.80', 'Escultores', 8, 'Granito Blanco Polar Economico MediaPlaca', NULL, 'India', '2019-09-11', 'yqO3qvj4pUZNSILb.jpg', 1, '2019-09-24 21:43:26', '2019-09-24 21:43:26'),
(12, 1, '190810911', 'TSGSNCECILN', 'Santa Cecilia', 'Pulido', '1.70', '2.60', '5.10', '1.00', 'Músico', 4, 'Granito Santa Cecilia Beige Elegante', NULL, 'Brazil', '2019-09-17', 'pgGglDuLYMgOhUp3.jpg', 1, '2019-09-25 01:42:36', '2019-09-25 02:23:37'),
(13, 1, '691761976', 'TSGALAMOSCAZ', 'Ala de mosca azul', 'Pulido - Leather', '1.70', '2.80', '5.80', '2.00', 'Escultores', 1, 'Granito AlaMosca Azul Economico Exotico', NULL, 'Brazil CGYSA1', '2019-09-03', 'ofw9Q1eQx3R3PZjl.jpg', 0, '2019-09-25 01:47:50', '2019-09-25 03:18:01'),
(14, 1, '11111188', 'TSGALAMOCVR1', 'Ala Mosca Verde', 'Pulido', '1.80', '2.30', '4.10', '2.00', 'Escultores', 2, 'Granito AlaMosca Verde Economico', NULL, 'Brazil CQJ1A', '2019-09-07', 'lmg1pHygxULLBEjI.jpg', 1, NULL, '2019-09-25 02:13:12'),
(15, 2, '6526722178', 'TSCARMUSTANG1', 'Mustang', 'Pulido', '1.80', '2.30', '4.20', '2.00', 'Músico', 2, 'Cuarcita Beige Mustang Exotico', NULL, 'Brazil CHGAG1', '2019-09-17', 'uph3LLN9NPpbBjVi.jpg', 1, '2019-09-25 04:07:06', '2019-09-25 04:07:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `condicion`, `created_at`, `updated_at`) VALUES
(1, 'Granito', 'Todo tipo de granito', 1, '2019-09-24 21:09:01', '2019-09-24 21:09:01'),
(2, 'Cuarcita', 'Todo tipo de cuarcita', 1, '2019-09-25 01:45:03', '2019-09-25 01:45:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ingresos`
--

CREATE TABLE `detalle_ingresos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idingreso` bigint(20) UNSIGNED NOT NULL,
  `idarticulo` bigint(20) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_compra` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos`
--

CREATE TABLE `ingresos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idproveedor` bigint(20) UNSIGNED NOT NULL,
  `idusuario` bigint(20) UNSIGNED NOT NULL,
  `tipo_comprobante` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `num_comprobante` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_hora` datetime NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `total` decimal(11,2) NOT NULL,
  `estado` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(11, '2019_09_18_144300_create_categorias_table', 1),
(12, '2019_09_19_154422_create_articulos_table', 1),
(13, '2014_10_12_100000_create_password_resets_table', 2),
(14, '2019_09_25_155934_create_personas_table', 2),
(15, '2019_09_25_173000_create_proveedores_table', 2),
(16, '2019_09_25_220413_create_roles_table', 2),
(17, '2019_09_26_000000_create_users_table', 2),
(18, '2019_09_26_183330_create_ingresos_table', 2),
(19, '2019_09_26_183401_create_detalle_ingresos_table', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_documento` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_documento` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ciudad` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `domicilio` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rfc` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id`, `nombre`, `tipo_documento`, `num_documento`, `ciudad`, `domicilio`, `telefono`, `email`, `rfc`, `created_at`, `updated_at`) VALUES
(1, 'Diego Alberto Domínguez', NULL, NULL, 'Guadalajara Jalisco', NULL, '3441053274', 'sistemas@troystone.com.mx', 'DOHD951219JKS', NULL, NULL),
(2, 'Fulano Almacenero', NULL, NULL, 'GDL', 'Conocido', '3333333333', 'email-prueba@troystone.com.mx', NULL, NULL, NULL),
(3, 'CIMSTONE', NULL, NULL, 'Sao Paulo Brazil', 'Av. Hausc 43212', '18181818181', 'export@cimstone.com.mx', 'CBSA12334DFA', '2019-09-28 20:45:07', '2019-09-28 20:45:07'),
(4, 'Miriam Chavez', 'Pasaporte', '123233554332', 'Guadalajara Jalisco', 'Concocido 6412 Col. Centro', '3310331033', 'recepcion@troystone.com.mx', 'CHLM920218YYH', '2019-09-28 20:59:47', '2019-09-28 20:59:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contacto` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono_contacto` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `contacto`, `telefono_contacto`) VALUES
(3, 'Edgar Lopez', '3321234565');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `condicion`) VALUES
(1, 'Administrador', 'Administradores de área', 1),
(2, 'Vendedor', 'Vendedor área ventas', 1),
(3, 'Almacenero', 'Almacenero área compras', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT 1,
  `idrol` bigint(20) UNSIGNED NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `usuario`, `password`, `condicion`, `idrol`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '$2y$12$m5jIyAfPKxM4qn5dM.XKb.RBjDDL9pNQMxk/5jCnmUjMBFsubHI8K', 1, 1, NULL, NULL, NULL),
(2, 'Almacenero', '54321', 1, 3, NULL, NULL, NULL),
(4, 'RecepcionTroy', '$2y$10$n8ptyx1FpDxWJYifZDMlq..NyE0bRnNtiPNgrJak6ibRv5b1b1CpG', 1, 2, NULL, '2019-09-28 20:59:47', '2019-09-28 20:59:47');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `articulos_codigo_unique` (`codigo`),
  ADD UNIQUE KEY `articulos_sku_unique` (`sku`),
  ADD KEY `articulos_idcategoria_foreign` (`idcategoria`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_ingresos`
--
ALTER TABLE `detalle_ingresos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_ingresos_idingreso_foreign` (`idingreso`),
  ADD KEY `detalle_ingresos_idarticulo_foreign` (`idarticulo`);

--
-- Indices de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ingresos_idproveedor_foreign` (`idproveedor`),
  ADD KEY `ingresos_idusuario_foreign` (`idusuario`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personas_rfc_unique` (`rfc`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD KEY `proveedores_id_foreign` (`id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_nombre_unique` (`nombre`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `users_usuario_unique` (`usuario`),
  ADD KEY `users_id_foreign` (`id`),
  ADD KEY `users_idrol_foreign` (`idrol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articulos`
--
ALTER TABLE `articulos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalle_ingresos`
--
ALTER TABLE `detalle_ingresos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD CONSTRAINT `articulos_idcategoria_foreign` FOREIGN KEY (`idcategoria`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `detalle_ingresos`
--
ALTER TABLE `detalle_ingresos`
  ADD CONSTRAINT `detalle_ingresos_idarticulo_foreign` FOREIGN KEY (`idarticulo`) REFERENCES `articulos` (`id`),
  ADD CONSTRAINT `detalle_ingresos_idingreso_foreign` FOREIGN KEY (`idingreso`) REFERENCES `ingresos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD CONSTRAINT `ingresos_idproveedor_foreign` FOREIGN KEY (`idproveedor`) REFERENCES `proveedores` (`id`),
  ADD CONSTRAINT `ingresos_idusuario_foreign` FOREIGN KEY (`idusuario`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD CONSTRAINT `proveedores_id_foreign` FOREIGN KEY (`id`) REFERENCES `personas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_id_foreign` FOREIGN KEY (`id`) REFERENCES `personas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_idrol_foreign` FOREIGN KEY (`idrol`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
