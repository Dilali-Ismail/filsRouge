-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : mysql
-- Généré le : mar. 29 avr. 2025 à 15:24
-- Version du serveur : 8.0.40
-- Version de PHP : 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `Alfmabrok`
--

-- --------------------------------------------------------

--
-- Structure de la table `amariyas`
--

CREATE TABLE `amariyas` (
  `id` bigint UNSIGNED NOT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `amariyas`
--

INSERT INTO `amariyas` (`id`, `service_id`, `name`, `description`, `price`, `photo`, `created_at`, `updated_at`) VALUES
(2, 10, 'Amariya Royale en bois sculpté', 'Fabriquée à la main en bois noble avec des motifs marocains traditionnels. Garnie de coussins en velours brodés et finitions dorées.', 4000.00, 'amariyas/OPSIEb9ZHaV355VgPpDOgjH6KT1xUBEVHdHWSWke.jpg', '2025-04-14 13:37:16', '2025-04-14 13:37:16'),
(3, 10, 'Amariya Moderne en plexiglas transparent', 'Design élégant et contemporain, souvent ornée de lumières LED. Parfaite pour un mariage chic et tendance.', 4500.00, 'amariyas/FMGu6l50DpxIw7dPTruvadWxuifWR5HCywhOsqZm.jpg', '2025-04-14 13:52:34', '2025-04-14 13:54:29'),
(4, 10, 'Amariya Amazigh', 'Style berbère authentique, décorée avec des tissus colorés, perles et motifs ethniques.', 3500.00, 'amariyas/4ahFMSswXSVGGbwrx7VecV8FtqaWB7WsYe6GON06.jpg', '2025-04-14 13:57:06', '2025-04-14 13:57:06');

-- --------------------------------------------------------

--
-- Structure de la table `animations`
--

CREATE TABLE `animations` (
  `id` bigint UNSIGNED NOT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('chaabi','dakka','andalouse','orchestre') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `animations`
--

INSERT INTO `animations` (`id`, `service_id`, `name`, `description`, `price`, `photo`, `type`, `created_at`, `updated_at`) VALUES
(1, 14, 'atika', 'd9a assidi', 2000.00, 'animations/y1vzk8f7cGjVThP5vo5LO5a7epuZACkemtRv5I6J.jpg', 'dakka', '2025-04-15 13:13:19', '2025-04-15 13:13:19'),
(2, 14, 'Bochra Lana', 'fik fik', 3453.00, 'animations/qvZXN2Z3jprSaaRCencJZhjrGOlzO903D7ylYVut.jpg', 'andalouse', '2025-04-15 13:14:52', '2025-04-15 13:14:52');

-- --------------------------------------------------------

--
-- Structure de la table `clothing_items`
--

CREATE TABLE `clothing_items` (
  `id` bigint UNSIGNED NOT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` enum('robe_mariee','costume_homme') COLLATE utf8mb4_unicode_ci NOT NULL,
  `style` enum('traditionnel','moderne') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `clothing_items`
--

INSERT INTO `clothing_items` (`id`, `service_id`, `name`, `description`, `price`, `photo`, `category`, `style`, `created_at`, `updated_at`) VALUES
(3, 6, 'JJ', 'JJJ', 78.00, 'clothing-items/nqPU0dPKb0uUxj2xmQgQyfGwMIqeoQNx1Q6n8VWF.png', 'costume_homme', 'traditionnel', '2025-04-12 14:11:43', '2025-04-12 14:11:43'),
(6, 6, 'gg', 'TRTRTvbv', 456.00, 'clothing-items/3tePfaUnQTuvkoRxR7S07Cq3JAQObEMW6NfLQR6j.png', 'robe_mariee', 'moderne', '2025-04-12 14:34:30', '2025-04-12 14:40:36'),
(9, 6, 'Caftan moderne', 'Mélange de tradition et de modernité, souvent porté pour la Henné ou la réception.', 4000.00, 'clothing-items/BqGRf4kdglL6glOjtVjNf9te1SMlEEy3LdG9psD6.jpg', 'robe_mariee', 'traditionnel', '2025-04-14 13:23:33', '2025-04-19 18:24:26'),
(10, 6, 'hbhb', 'ddkdkdj', 2500.00, 'clothing-items/ab40cmUAV535HhQ49Y81V5i5HO8QnHNKYsaVno2r.jpg', 'robe_mariee', 'traditionnel', '2025-04-15 09:23:31', '2025-04-15 09:23:31'),
(11, 6, 'bbbdd', 'kdkddjfj', 2000.00, 'clothing-items/uqZeHnVU45utT359TNfMjnmiwPOhu219u83Uzwrk.jpg', 'costume_homme', 'traditionnel', '2025-04-15 09:24:54', '2025-04-15 09:24:54');

-- --------------------------------------------------------

--
-- Structure de la table `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint UNSIGNED NOT NULL,
  `mariee_id` bigint UNSIGNED NOT NULL,
  `traiteur_id` bigint UNSIGNED NOT NULL,
  `last_message_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `conversations`
--

INSERT INTO `conversations` (`id`, `mariee_id`, `traiteur_id`, `last_message_at`, `created_at`, `updated_at`) VALUES
(1, 4, 1, '2025-04-26 21:34:46', '2025-04-25 11:52:12', '2025-04-26 21:34:46');

-- --------------------------------------------------------

--
-- Structure de la table `decorations`
--

CREATE TABLE `decorations` (
  `id` bigint UNSIGNED NOT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `decorations`
--

INSERT INTO `decorations` (`id`, `service_id`, `name`, `description`, `price`, `photo`, `created_at`, `updated_at`) VALUES
(1, 12, 'Thème Royal Fassi', 'Un décor inspiré des palais de Fès : colonnes, zelliges en impression, tissus brodés, assises en velours rouge et doré. Atmosphère majestueuse garantie.', 2000.00, 'decorations/MeRys7H4NIkxXrr4Yi65KFO8nhda1S9OFU7Fpn1A.jpg', '2025-04-14 15:39:26', '2025-04-14 15:39:26'),
(2, 12, 'Thème Mille et Une Nuits', 'Un décor féérique inspiré de l’Orient avec des lanternes en métal, des tapis rouges, des voilages dorés, et des coussins brodés. Idéal pour une ambiance de conte marocain.', 2500.00, 'decorations/WDpacYW0mtLSooK3KC7UQ6TcpyOeNWrd7KT9m5g6.jpg', '2025-04-14 15:46:08', '2025-04-14 15:46:08'),
(3, 12, 'Thème Bohème Chic', 'Ambiance nature et douceur : macramé, fleurs séchées, guirlandes en rotin, tapis berbères clairs, lanternes blanches. Style très tendance pour mariages intimistes.', 5000.00, 'decorations/RVKcRxoJrnfyP2SLaHcAJbW2thzC2e8okAoEt5d9.jpg', '2025-04-14 15:53:06', '2025-04-14 15:53:06');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `makeups`
--

CREATE TABLE `makeups` (
  `id` bigint UNSIGNED NOT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `experience` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `makeups`
--

INSERT INTO `makeups` (`id`, `service_id`, `name`, `description`, `price`, `photo`, `experience`, `created_at`, `updated_at`) VALUES
(1, 9, 'nn', 'JJJ', 67.00, 'makeups/7TzYlg4xWxYr3GrrRTqfxvmhRkTbgLiqYQ91Qj9m.jpg', '009', '2025-04-13 17:17:20', '2025-04-13 17:17:20'),
(2, 9, 'hemza', 'jjdhdhd', 30000.00, 'makeups/sRQJU4uVX7PyxHB8UOHBBk1PH4YZj3N3OSFQy6IE.jpg', '6', '2025-04-15 09:26:49', '2025-04-15 09:26:49');

-- --------------------------------------------------------

--
-- Structure de la table `makeup_portfolio_items`
--

CREATE TABLE `makeup_portfolio_items` (
  `id` bigint UNSIGNED NOT NULL,
  `makeup_id` bigint UNSIGNED NOT NULL,
  `type` enum('image','video') COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `makeup_portfolio_items`
--

INSERT INTO `makeup_portfolio_items` (`id`, `makeup_id`, `type`, `file_path`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'image', 'makeup-portfolio/JefvpKQHPxIbtBo561IU2SKJwdJjC8kQkYxyzbJw.jpg', 'service1', 'dhdhdddyv', '2025-04-15 09:29:11', '2025-04-15 09:29:11'),
(2, 1, 'image', 'makeup-portfolio/tsJ3BiVt47X5IUYB2sKc7Kl2BJ5VTDcYoU9Cb7jc.jpg', 'service2', 'hdhdh', '2025-04-15 09:30:38', '2025-04-15 09:30:38');

-- --------------------------------------------------------

--
-- Structure de la table `mariees`
--

CREATE TABLE `mariees` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `groom_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bride_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `budget` decimal(10,2) DEFAULT NULL,
  `wedding_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `mariees`
--

INSERT INTO `mariees` (`id`, `user_id`, `groom_name`, `bride_name`, `city`, `budget`, `wedding_date`, `created_at`, `updated_at`) VALUES
(4, 6, 'Ali', 'Marwa', 'Marrakech', NULL, NULL, '2025-04-06 12:18:01', '2025-04-06 12:18:01');

-- --------------------------------------------------------

--
-- Structure de la table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` bigint UNSIGNED NOT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` enum('boisson','entree','plat','dessert') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `menu_items`
--

INSERT INTO `menu_items` (`id`, `service_id`, `name`, `description`, `price`, `photo`, `category`, `created_at`, `updated_at`) VALUES
(9, 11, 'Thé à la menthe royal', 'Thé vert avec menthe fraîche, servi dans un service traditionnel doré.', 15.00, 'menu-items/TE8iCLyxQL1t313JLImXGDlYN8KhrxhLiT2u3Q73.jpg', 'boisson', '2025-04-14 09:56:37', '2025-04-14 09:56:37'),
(10, 11, 'Café noir ou au lait', 'Café fraîchement moulu, servi chaud avec ou sans lait.', 20.00, 'menu-items/VVeisPxXgmY30QYDSI7pJFnfichCL49eikFYLpgE.jpg', 'boisson', '2025-04-14 09:59:16', '2025-04-14 09:59:16'),
(11, 11, 'Jus d’amande maison', 'Boisson douce à base d’amandes, eau de fleur d’oranger et miel.', 25.00, 'menu-items/7bSDoQ0jPjr5HmwchyLxGpqE5F462glq8WSG2xdy.jpg', 'boisson', '2025-04-14 10:02:15', '2025-04-14 10:02:15'),
(12, 11, 'Cocktail de fruits exotiques', 'Mélange de jus frais : mangue, ananas, fraise et orange.', 30.00, 'menu-items/cb1m1bvGnoxxxwlbD7b95CM7REdxgJ7nIvkxX8rM.jpg', 'boisson', '2025-04-14 10:06:39', '2025-04-14 10:06:39'),
(14, 11, 'Jus d’orange pressé', 'Frais et 100% naturel, parfait pour une réception estivale.', 25.00, 'menu-items/ElrgeVGdNb5MZXAH2wFABACvqWbqM5MlOBy3HleL.jpg', 'boisson', '2025-04-14 10:14:24', '2025-04-14 10:14:24'),
(15, 11, 'Jus de fraise-banane', 'Mélange onctueux et sucré, très apprécié par les invités.', 30.00, 'menu-items/d6ZeCn7qwZap1O523NS1AKqqFwU1o1gxVtV3gQII.jpg', 'boisson', '2025-04-14 10:16:46', '2025-04-14 10:16:46'),
(16, 11, 'Eau plate Sidi Ali', 'Eau minérale naturelle du Maroc.', 15.00, 'menu-items/Y1h0IKhV5T7WsBPWOSx2x1ivZvXKzanK49RKzxZH.jpg', 'boisson', '2025-04-14 10:20:23', '2025-04-14 10:20:23'),
(17, 11, 'Eau gazeuse Oulmes', 'Eau pétillante marocaine, parfaite pour accompagner les repas.', 15.00, 'menu-items/9eBhFz9VtY7GZoyHZFmNTMrrNwLBMmmPhQRiBerQ.jpg', 'boisson', '2025-04-14 10:35:01', '2025-04-14 10:35:01'),
(18, 11, 'Coca-Cola / Coca Zéro', 'Incontournable lors des cérémonies.', 15.00, 'menu-items/Bp1DpoG15kDgCnXNK5MjDLB0FaNhvpllWmAP3aMJ.jpg', 'boisson', '2025-04-14 10:40:04', '2025-04-14 10:40:04'),
(19, 11, 'Briouates aux fruits de mer', 'Feuilletés croustillants garnis de crevettes et calamar, parfumés à la coriandre', 30.00, 'menu-items/cSbiYL5hpn03bFV2StRPzckykW0BswXZscAWQEYp.jpg', 'entree', '2025-04-14 11:35:43', '2025-04-14 11:35:43'),
(20, 11, 'Mini-pastillas au poulet', 'Petites pastillas individuelles au poulet, amandes et cannelle.', 35.00, 'menu-items/GRrjbGybwH6SBep9pi456P6fv5pusyu5mdmL6M78.jpg', 'entree', '2025-04-14 11:46:36', '2025-04-14 11:46:36'),
(21, 11, 'Feuilletés au fromage et épices douces', 'Gâteaux salés au fromage fondant et herbes marocaines.', 25.00, 'menu-items/6ZRJD6ZlZGyjsb6J1BSl1n63HmfRTACDSPEF9GHk.jpg', 'entree', '2025-04-14 11:53:31', '2025-04-14 11:53:31'),
(22, 11, 'Cigares à la viande hachée', 'Pâte croustillante roulée et farcie de viande hachée épicée.', 30.00, 'menu-items/UbeaEaLkUJCIbWbjIy5atd2Lu6Q4EKcqnprg4fYZ.jpg', 'entree', '2025-04-14 11:56:48', '2025-04-14 11:56:48'),
(23, 11, 'Méchoui d’agneau', 'Épaule ou gigot d’agneau rôti lentement, servi avec sel, cumin et pain traditionnel.', 250.00, 'menu-items/iqInB01CZkST5UWCMCxid0alIzhoNGxFB0bkXPQV.jpg', 'plat', '2025-04-14 11:59:43', '2025-04-14 11:59:43'),
(24, 11, 'Pastilla au pigeon', 'Délicate pastilla sucrée-salée à base de pigeon, amandes, œufs, et pâte fine croustillante.', 260.00, 'menu-items/hc7heXBTU9UdalNAS8f3N3gq7KYpYtgfwFXcTNFE.jpg', 'plat', '2025-04-14 12:12:17', '2025-04-14 12:12:17'),
(25, 11, 'Tajine de veau aux pruneaux', 'Morceaux de veau fondants, pruneaux caramélisés, amandes et cannelle.', 230.00, 'menu-items/54Q03xiKg3owut1KjGXvDh1IzAmQMhKFL77dH4Dy.jpg', 'plat', '2025-04-14 12:15:14', '2025-04-14 12:15:14'),
(26, 11, 'Tajine d’agneau aux fruits secs', 'Tajine raffiné avec abricots secs, figues, dattes et amandes grillées.', 240.00, 'menu-items/cnkQ4zomwPrt3Dwtle9SPkHiAlbDdzuv4b4I1KWM.jpg', 'plat', '2025-04-14 12:19:15', '2025-04-14 12:19:15'),
(27, 11, 'Plateau de pâtisseries marocaines', 'Cornes de gazelle, ghriba, fekkas, briouates au miel...', 40.00, 'menu-items/KnwP7MxZWEbfjksER0mkbS4XCGMEARgSzSGG2Mpk.jpg', 'dessert', '2025-04-14 12:22:07', '2025-04-14 12:22:07'),
(28, 11, 'Verrines de mousse à la fleur d’oranger', 'Mousse légère parfumée à l’eau de fleur d’oranger.', 30.00, 'menu-items/p9bqvkDLDILqzkkmL9Nz3A81X8ZpUkQgeeL1YpH2.jpg', 'dessert', '2025-04-14 12:26:28', '2025-04-14 12:26:28'),
(29, 11, 'Fruits frais joliment dressés', 'Plateau décoratif de fruits de saison tranchés (pastèque, melon, raisin, fraises).', 45.00, 'menu-items/loiExUKh8cXxCCWRIgA2qtholP8ypRfJpk5k5P03.jpg', 'dessert', '2025-04-14 12:30:14', '2025-04-14 12:30:14');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` bigint UNSIGNED NOT NULL,
  `conversation_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `conversation_id`, `user_id`, `content`, `read`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 'bonjour', 1, '2025-04-25 14:14:51', '2025-04-25 14:15:51'),
(2, 1, 13, 'HI', 1, '2025-04-25 14:20:23', '2025-04-25 14:20:39'),
(3, 1, 6, 'HI', 1, '2025-04-25 14:21:39', '2025-04-25 14:24:39'),
(4, 1, 6, 'salut', 1, '2025-04-25 15:34:24', '2025-04-25 15:34:48'),
(5, 1, 6, 'chi amariya tma walo ??', 1, '2025-04-26 15:51:18', '2025-04-26 15:52:25'),
(6, 1, 6, 'hi', 1, '2025-04-26 15:52:42', '2025-04-26 15:52:52'),
(7, 1, 13, 'yo', 1, '2025-04-26 19:55:54', '2025-04-26 21:31:14'),
(8, 1, 6, 'ccc', 1, '2025-04-26 19:56:06', '2025-04-26 21:25:26'),
(9, 1, 13, 'cc', 1, '2025-04-26 19:56:19', '2025-04-26 21:31:14'),
(10, 1, 6, 'ccc', 1, '2025-04-26 19:56:34', '2025-04-26 21:25:26'),
(11, 1, 6, 'ccc', 1, '2025-04-26 21:25:08', '2025-04-26 21:25:26'),
(12, 1, 6, 'CC', 0, '2025-04-26 21:34:46', '2025-04-26 21:34:46');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_100000_create_password_resets_table', 1),
(2, '2019_08_19_000000_create_failed_jobs_table', 1),
(3, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(4, '2025_04_04_175744_create_roles_table', 1),
(5, '2025_04_04_180021_create_users_table', 1),
(6, '2025_04_04_180329_create_mariees_table', 1),
(7, '2025_04_04_180810_create_traiteurs_table', 1),
(8, '2025_04_07_095744_create_service_categories_table', 2),
(9, '2025_04_07_095911_create_services_table', 2),
(10, '2025_04_07_100313_create_menu_items_table', 2),
(11, '2025_04_11_004231_create_clothing_items_table', 3),
(12, '2025_04_12_151948_create_negafas_table', 4),
(13, '2025_04_12_152758_create_negafa_portfolio_items_table', 4),
(14, '2025_04_13_012208_create_makeups_table', 5),
(15, '2025_04_13_012402_create_makeup_portfolio_items_table', 5),
(16, '2025_04_13_012605_create_photographers_table', 5),
(17, '2025_04_13_012629_create_photographer_portfolio_items_table', 5),
(18, '2025_04_14_082533_create_amariyas_table', 6),
(19, '2025_04_14_145036_create_decorations_table', 7),
(20, '2025_04_15_095718_create_salles_table', 8),
(21, '2025_04_15_124825_create_animations_table', 9),
(22, '2025_04_16_163052_create_traiteur_availabilities_table', 10),
(23, '2025_04_17_000947_create_reservations_table', 10),
(24, '2025_04_17_001506_create_reservation_services_table', 10),
(25, '2025_04_17_083413_create_payments_table', 10),
(26, '2025_04_21_104230_add_guests_and_tables_to_reservations_table', 11),
(27, '2025_04_25_083908_create_conversations_table', 12),
(28, '2025_04_25_084142_create_messages_table', 12);

-- --------------------------------------------------------

--
-- Structure de la table `negafas`
--

CREATE TABLE `negafas` (
  `id` bigint UNSIGNED NOT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `experience` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `negafas`
--

INSERT INTO `negafas` (`id`, `service_id`, `name`, `description`, `price`, `photo`, `experience`, `created_at`, `updated_at`) VALUES
(3, 7, 'Negafa Khadija', 'Très réputée à Casablanca. Elle propose des caftans de luxe et une mise en scène artistique de la mariée.', 4000.00, 'negafas/pGpQN9V6rMbwXXes7IHO8LEGq1OlgeiUOAZRCuYi.jpg', '5', '2025-04-14 16:17:35', '2025-04-14 16:17:35'),
(4, 7, 'Negafa Latifa', 'Offre un service simple et raffiné avec 4 tenues personnalisées. Parfait pour les petits mariages.', 40000.00, 'negafas/tX12kwlc1GrHzLyPvLhHnnxoYowfR5qjtFZeEK2j.jpg', '4', '2025-04-15 08:48:24', '2025-04-15 08:48:24');

-- --------------------------------------------------------

--
-- Structure de la table `negafa_portfolio_items`
--

CREATE TABLE `negafa_portfolio_items` (
  `id` bigint UNSIGNED NOT NULL,
  `negafa_id` bigint UNSIGNED NOT NULL,
  `type` enum('image','video') COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `negafa_portfolio_items`
--

INSERT INTO `negafa_portfolio_items` (`id`, `negafa_id`, `type`, `file_path`, `title`, `description`, `created_at`, `updated_at`) VALUES
(2, 3, 'video', 'negafa-portfolio/T6IvyhPb1mJZe40Mo5UKRMc8WQwEfmhzQNQQhD2Q.mp4', 'Défilé à Marrakech', 'Vidéo de notre prestation complète lors d’un mariage en plein air à Marrakech.', '2025-04-15 08:12:38', '2025-04-15 08:12:38'),
(3, 3, 'image', 'negafa-portfolio/C9jOK47R2ilTASr6F9cb8fYNROQRJnYTKvjufHtC.jpg', 'L’entrée majestueuse de la mariée\"', 'La mariée fait son entrée vêtue d’un somptueux caftan vert émeraude brodé d’or, encadrée par son équipe de négafates en tenues traditionnelles. Un moment de grâce et d’élégance, marquant le début des festivités.', '2025-04-15 08:27:58', '2025-04-15 08:27:58'),
(4, 3, 'image', 'negafa-portfolio/6CwVEHaBWI7jzBRTQXn5kI6UenxG18tm66cZi4fG.jpg', 'Trône royal et ambiance florale', 'La mariée rayonne sur un trône magnifiquement décoré, entourée de fleurs éclatantes et de coussins brodés. Cette scène luxueuse illustre parfaitement le raffinement d’une soirée marocaine typique.', '2025-04-15 08:29:22', '2025-04-15 08:29:22'),
(6, 3, 'image', 'negafa-portfolio/CjPFGyPe2msMyQFSDwuNYPmbkRGDDNjoxaB7Utgr.jpg', 'Cérémonie de Henné', 'La mariée rayonne lors de la cérémonie traditionnelle du henné, entourée de symboles de bénédiction et de prospérité. Le henné, appliqué avec soin, marque un moment sacré de préparation au mariage, rempli de joie et d’amour familial.', '2025-04-15 08:35:33', '2025-04-15 08:35:33'),
(7, 4, 'image', 'negafa-portfolio/4IIGGpO7udLZXciODQzeGt4CMjdMno7wNnAjG0Uv.jpg', 'Tradition du Henné', 'Moment précieux où la mariée célèbre la cérémonie du henné', '2025-04-15 08:55:04', '2025-04-15 08:55:04'),
(8, 4, 'video', 'negafa-portfolio/w2OTKbO04fgnb6HbBPxa9iM9I5JjX7NDQ76RIAxl.mp4', 'L’Entrée Royale de la Mariée', 'L’entrée de la mariée dans la salle de fête est un moment magique et émouvant.', '2025-04-15 09:00:35', '2025-04-15 09:00:35'),
(9, 4, 'video', 'negafa-portfolio/qYd9cNT3YXQi2Rwl7sAM9USj6802Qzi4u8QBYAHl.mp4', 'Berza Traditionnelle', 'Habillée de tenues traditionnelles raffinées', '2025-04-15 09:11:24', '2025-04-15 09:11:24');

-- --------------------------------------------------------

--
-- Structure de la table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `reservation_id` bigint UNSIGNED NOT NULL,
  `payment_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','failed','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'stripe',
  `payment_details` json DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `payments`
--

INSERT INTO `payments` (`id`, `reservation_id`, `payment_id`, `amount`, `status`, `payment_method`, `payment_details`, `paid_at`, `created_at`, `updated_at`) VALUES
(8, 2, NULL, 54553.00, 'pending', 'stripe', NULL, NULL, '2025-04-23 00:19:02', '2025-04-23 00:19:02'),
(9, 2, NULL, 54553.00, 'pending', 'stripe', NULL, NULL, '2025-04-23 00:30:34', '2025-04-23 00:30:34'),
(10, 2, NULL, 54553.00, 'pending', 'stripe', NULL, NULL, '2025-04-23 00:33:11', '2025-04-23 00:33:11'),
(11, 3, NULL, 59755.00, 'pending', 'stripe', NULL, NULL, '2025-04-23 09:52:44', '2025-04-23 09:52:44'),
(12, 3, NULL, 59755.00, 'pending', 'stripe', NULL, NULL, '2025-04-23 09:54:31', '2025-04-23 09:54:31'),
(13, 3, NULL, 59755.00, 'pending', 'stripe', NULL, NULL, '2025-04-23 09:55:27', '2025-04-23 09:55:27'),
(14, 3, NULL, 59755.00, 'pending', 'stripe', NULL, NULL, '2025-04-23 10:01:20', '2025-04-23 10:01:20'),
(15, 3, 'pi_3RH0EsP2kkTotZCK0O0qVIPr', 59755.00, 'completed', 'stripe', '\"{\\\"session_id\\\":\\\"cs_test_a1vVdPAObMDmnE3qJsvLYYogelbDxaVr9BvZvvOHyvFzGQ99MAcxEfllRT\\\",\\\"payment_intent\\\":\\\"pi_3RH0EsP2kkTotZCK0O0qVIPr\\\"}\"', '2025-04-23 10:21:20', '2025-04-23 10:06:43', '2025-04-23 10:21:20');

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `photographers`
--

CREATE TABLE `photographers` (
  `id` bigint UNSIGNED NOT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `experience` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `photographers`
--

INSERT INTO `photographers` (`id`, `service_id`, `name`, `description`, `price`, `photo`, `experience`, `created_at`, `updated_at`) VALUES
(4, 8, 'Ali', 'jdjdjdbc', 5677.00, 'photographers/2jfRVnY0efNJEHaeYuR4dPAy4mpFWgE5w8C3aAyK.jpg', '3', '2025-04-15 09:47:46', '2025-04-15 09:47:46'),
(5, 8, 'Adam', 'kkkkk', 2000.00, 'photographers/Ns4BaPd2HF32xahkZhN06LeXut6JUwrhqCaXazTK.jpg', '4', '2025-04-15 09:48:51', '2025-04-15 09:48:51');

-- --------------------------------------------------------

--
-- Structure de la table `photographer_portfolio_items`
--

CREATE TABLE `photographer_portfolio_items` (
  `id` bigint UNSIGNED NOT NULL,
  `photographer_id` bigint UNSIGNED NOT NULL,
  `type` enum('image','video') COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `photographer_portfolio_items`
--

INSERT INTO `photographer_portfolio_items` (`id`, `photographer_id`, `type`, `file_path`, `title`, `description`, `created_at`, `updated_at`) VALUES
(2, 4, 'image', 'photographer-portfolio/uBcIUpGW6ZvIYAvSA0aYJm6k8mtPbaAtFzHdUA7c.jpg', 'lll', 'bhbh', '2025-04-15 09:49:26', '2025-04-15 09:49:26'),
(3, 4, 'image', 'photographer-portfolio/qeXwgQfVwsdWUdxWJqO9EoMDwHAccPLOz59LgcOj.jpg', 'image 2', 'kolchi zine wah nakl b limna wla blisra', '2025-04-15 09:50:36', '2025-04-15 09:50:36');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id` bigint UNSIGNED NOT NULL,
  `mariee_id` bigint UNSIGNED NOT NULL,
  `traiteur_id` bigint UNSIGNED NOT NULL,
  `event_date` date NOT NULL,
  `nombre_invites` int DEFAULT NULL,
  `nombre_tables` int DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','confirmed','cancelled','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id`, `mariee_id`, `traiteur_id`, `event_date`, `nombre_invites`, `nombre_tables`, `total_amount`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 4, 1, '2025-05-05', 34, 3, 56688.00, 'pending', NULL, '2025-04-22 14:24:40', '2025-04-22 14:24:40'),
(2, 4, 1, '2025-05-06', 23, 2, 54553.00, 'pending', NULL, '2025-04-22 23:50:29', '2025-04-22 23:50:29'),
(3, 4, 1, '2025-05-06', 45, 4, 59755.00, 'confirmed', NULL, '2025-04-23 09:51:18', '2025-04-23 10:21:20');

-- --------------------------------------------------------

--
-- Structure de la table `reservation_services`
--

CREATE TABLE `reservation_services` (
  `id` bigint UNSIGNED NOT NULL,
  `reservation_id` bigint UNSIGNED NOT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `service_item_id` bigint UNSIGNED DEFAULT NULL,
  `service_item_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reservation_services`
--

INSERT INTO `reservation_services` (`id`, `reservation_id`, `service_id`, `service_item_id`, `service_item_type`, `price`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 11, 9, 'App\\Models\\MenuItem', 510.00, 1, '2025-04-22 14:24:40', '2025-04-22 14:24:40'),
(2, 1, 11, 19, 'App\\Models\\MenuItem', 1020.00, 1, '2025-04-22 14:24:41', '2025-04-22 14:24:41'),
(3, 1, 11, 20, 'App\\Models\\MenuItem', 1190.00, 1, '2025-04-22 14:24:41', '2025-04-22 14:24:41'),
(4, 1, 11, 23, 'App\\Models\\MenuItem', 750.00, 1, '2025-04-22 14:24:41', '2025-04-22 14:24:41'),
(5, 1, 11, 24, 'App\\Models\\MenuItem', 780.00, 1, '2025-04-22 14:24:41', '2025-04-22 14:24:41'),
(6, 1, 11, 27, 'App\\Models\\MenuItem', 1360.00, 1, '2025-04-22 14:24:41', '2025-04-22 14:24:41'),
(7, 1, 7, 3, 'App\\Models\\Negafa', 4000.00, 1, '2025-04-22 14:24:41', '2025-04-22 14:24:41'),
(8, 1, 6, 9, 'App\\Models\\ClothingItem', 4000.00, 1, '2025-04-22 14:24:41', '2025-04-22 14:24:41'),
(9, 1, 6, 3, 'App\\Models\\ClothingItem', 78.00, 1, '2025-04-22 14:24:41', '2025-04-22 14:24:41'),
(10, 1, 12, 1, 'App\\Models\\Decoration', 2000.00, 1, '2025-04-22 14:24:41', '2025-04-22 14:24:41'),
(11, 1, 14, 1, 'App\\Models\\Animation', 2000.00, 1, '2025-04-22 14:24:41', '2025-04-22 14:24:41'),
(12, 1, 9, 2, 'App\\Models\\Makeup', 30000.00, 1, '2025-04-22 14:24:41', '2025-04-22 14:24:41'),
(13, 1, 10, 2, 'App\\Models\\Amariya', 4000.00, 1, '2025-04-22 14:24:41', '2025-04-22 14:24:41'),
(14, 1, 13, 1, 'App\\Models\\Salle', 3000.00, 1, '2025-04-22 14:24:41', '2025-04-22 14:24:41'),
(15, 1, 8, 5, 'App\\Models\\Photographer', 2000.00, 1, '2025-04-22 14:24:41', '2025-04-22 14:24:41'),
(16, 2, 11, 9, 'App\\Models\\MenuItem', 345.00, 1, '2025-04-22 23:50:29', '2025-04-22 23:50:29'),
(17, 2, 11, 19, 'App\\Models\\MenuItem', 690.00, 1, '2025-04-22 23:50:29', '2025-04-22 23:50:29'),
(18, 2, 11, 23, 'App\\Models\\MenuItem', 500.00, 1, '2025-04-22 23:50:29', '2025-04-22 23:50:29'),
(19, 2, 11, 24, 'App\\Models\\MenuItem', 520.00, 1, '2025-04-22 23:50:29', '2025-04-22 23:50:29'),
(20, 2, 11, 27, 'App\\Models\\MenuItem', 920.00, 1, '2025-04-22 23:50:29', '2025-04-22 23:50:29'),
(21, 2, 6, 9, 'App\\Models\\ClothingItem', 4000.00, 1, '2025-04-22 23:50:29', '2025-04-22 23:50:29'),
(22, 2, 6, 3, 'App\\Models\\ClothingItem', 78.00, 1, '2025-04-22 23:50:29', '2025-04-22 23:50:29'),
(23, 2, 7, 3, 'App\\Models\\Negafa', 4000.00, 1, '2025-04-22 23:50:29', '2025-04-22 23:50:29'),
(24, 2, 9, 2, 'App\\Models\\Makeup', 30000.00, 1, '2025-04-22 23:50:29', '2025-04-22 23:50:29'),
(25, 2, 13, 1, 'App\\Models\\Salle', 3000.00, 1, '2025-04-22 23:50:29', '2025-04-22 23:50:29'),
(26, 2, 12, 2, 'App\\Models\\Decoration', 2500.00, 1, '2025-04-22 23:50:29', '2025-04-22 23:50:29'),
(27, 2, 10, 2, 'App\\Models\\Amariya', 4000.00, 1, '2025-04-22 23:50:29', '2025-04-22 23:50:29'),
(28, 2, 14, 1, 'App\\Models\\Animation', 2000.00, 1, '2025-04-22 23:50:29', '2025-04-22 23:50:29'),
(29, 2, 8, 5, 'App\\Models\\Photographer', 2000.00, 1, '2025-04-22 23:50:29', '2025-04-22 23:50:29'),
(30, 3, 11, 23, 'App\\Models\\MenuItem', 1000.00, 1, '2025-04-23 09:51:18', '2025-04-23 09:51:18'),
(31, 3, 11, 27, 'App\\Models\\MenuItem', 1800.00, 1, '2025-04-23 09:51:18', '2025-04-23 09:51:18'),
(32, 3, 11, 20, 'App\\Models\\MenuItem', 1575.00, 1, '2025-04-23 09:51:18', '2025-04-23 09:51:18'),
(33, 3, 11, 11, 'App\\Models\\MenuItem', 1125.00, 1, '2025-04-23 09:51:18', '2025-04-23 09:51:18'),
(34, 3, 6, 9, 'App\\Models\\ClothingItem', 4000.00, 1, '2025-04-23 09:51:18', '2025-04-23 09:51:18'),
(35, 3, 6, 3, 'App\\Models\\ClothingItem', 78.00, 1, '2025-04-23 09:51:18', '2025-04-23 09:51:18'),
(36, 3, 7, 3, 'App\\Models\\Negafa', 4000.00, 1, '2025-04-23 09:51:19', '2025-04-23 09:51:19'),
(37, 3, 9, 2, 'App\\Models\\Makeup', 30000.00, 1, '2025-04-23 09:51:19', '2025-04-23 09:51:19'),
(38, 3, 13, 1, 'App\\Models\\Salle', 3000.00, 1, '2025-04-23 09:51:19', '2025-04-23 09:51:19'),
(39, 3, 12, 1, 'App\\Models\\Decoration', 2000.00, 1, '2025-04-23 09:51:19', '2025-04-23 09:51:19'),
(40, 3, 10, 4, 'App\\Models\\Amariya', 3500.00, 1, '2025-04-23 09:51:19', '2025-04-23 09:51:19'),
(41, 3, 14, 1, 'App\\Models\\Animation', 2000.00, 1, '2025-04-23 09:51:19', '2025-04-23 09:51:19'),
(42, 3, 8, 4, 'App\\Models\\Photographer', 5677.00, 1, '2025-04-23 09:51:19', '2025-04-23 09:51:19');

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'mariee', '2025-04-05 01:17:56', '2025-04-05 01:17:56'),
(2, 'traiteur', '2025-04-05 01:17:56', '2025-04-05 01:17:56'),
(3, 'admin', '2025-04-05 01:17:56', '2025-04-05 01:17:56');

-- --------------------------------------------------------

--
-- Structure de la table `salles`
--

CREATE TABLE `salles` (
  `id` bigint UNSIGNED NOT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tables_count` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `salles`
--

INSERT INTO `salles` (`id`, `service_id`, `name`, `description`, `price`, `photo`, `location`, `tables_count`, `created_at`, `updated_at`) VALUES
(1, 13, 'Salle1', 'salle Marrakech luxev', 3000.00, 'salles/bvCfTWczEC17TYwDnYs3HISacTsST2Ip4l1C8q4D.jpg', 'Marrakech-', 40, '2025-04-15 10:25:54', '2025-04-15 10:47:24'),
(3, 13, 'Salle Sara', 'best of the best', 2000.00, 'salles/v0qztFbpZRO8OLFs68hPlviGCTL2h5ptU3aOfu9Z.jpg', 'Marrakech Sl', 70, '2025-04-15 11:51:47', '2025-04-15 11:51:47');

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

CREATE TABLE `services` (
  `id` bigint UNSIGNED NOT NULL,
  `traiteur_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `services`
--

INSERT INTO `services` (`id`, `traiteur_id`, `category_id`, `title`, `description`, `created_at`, `updated_at`) VALUES
(6, 1, 2, 'Vêtements Traditionnels', 'Collection de vêtements traditionnels', '2025-04-11 16:06:50', '2025-04-11 16:06:50'),
(7, 1, 3, 'Service de Négafa', 'Services de négafa proposés par notre établissement', '2025-04-12 17:44:33', '2025-04-12 17:44:33'),
(8, 1, 9, 'Service de Photographe', 'Services de photographie proposés par notre établissement', '2025-04-13 16:44:30', '2025-04-13 16:44:30'),
(9, 1, 4, 'Service de Maquillage', 'Services de maquillage proposés par notre établissement', '2025-04-13 17:17:19', '2025-04-13 17:17:19'),
(10, 1, 7, 'Amariya', 'Service d\'amariya pour les mariées', '2025-04-14 08:55:34', '2025-04-14 08:55:34'),
(11, 1, 1, 'Nuit d\'Étoiles', 'Un menu prestigieux conçu spécialement pour les mariages marocains, alliant élégance et saveurs traditionnelles. Chaque plat a été sélectionné pour offrir une expérience inoubliable aux invités.', '2025-04-14 09:50:12', '2025-04-14 09:50:12'),
(12, 1, 6, 'Décoration', 'Service de décoration pour les mariages', '2025-04-14 15:29:56', '2025-04-14 15:29:56'),
(13, 1, 5, 'Salles de Réception', 'Service de location de salles pour les mariages', '2025-04-15 10:21:47', '2025-04-15 10:21:47'),
(14, 1, 8, 'Animation Musicale', 'Service d\'animation musicale pour les mariages', '2025-04-15 13:11:09', '2025-04-15 13:11:09');

-- --------------------------------------------------------

--
-- Structure de la table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `service_categories`
--

INSERT INTO `service_categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'menu', '2025-04-07 10:08:42', '2025-04-07 10:08:42'),
(2, 'vetements', '2025-04-07 10:08:42', '2025-04-07 10:08:42'),
(3, 'negafa', '2025-04-07 10:08:42', '2025-04-07 10:08:42'),
(4, 'maquillage', '2025-04-07 10:08:42', '2025-04-07 10:08:42'),
(5, 'salles', '2025-04-07 10:08:42', '2025-04-07 10:08:42'),
(6, 'decoration', '2025-04-07 10:08:42', '2025-04-07 10:08:42'),
(7, 'amariya', '2025-04-07 10:08:42', '2025-04-07 10:08:42'),
(8, 'animation', '2025-04-07 10:08:42', '2025-04-07 10:08:42'),
(9, 'photographer', '2025-04-13 00:47:51', '2025-04-13 00:47:51');

-- --------------------------------------------------------

--
-- Structure de la table `traiteurs`
--

CREATE TABLE `traiteurs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `manager_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `registration_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `traiteurs`
--

INSERT INTO `traiteurs` (`id`, `user_id`, `manager_name`, `registration_number`, `phone_number`, `city`, `logo`, `is_verified`, `created_at`, `updated_at`) VALUES
(1, 13, 'Rahal', '8783', '06020403', 'Marrakech', NULL, 1, '2025-04-06 13:13:42', '2025-04-24 21:11:20'),
(2, 15, 'hamza', '2233', '0635019022', 'Agadir', NULL, 1, '2025-04-18 11:14:19', '2025-04-24 21:11:44');

-- --------------------------------------------------------

--
-- Structure de la table `traiteur_availabilities`
--

CREATE TABLE `traiteur_availabilities` (
  `id` bigint UNSIGNED NOT NULL,
  `traiteur_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `is_available` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `traiteur_availabilities`
--

INSERT INTO `traiteur_availabilities` (`id`, `traiteur_id`, `date`, `is_available`, `created_at`, `updated_at`) VALUES
(8, 1, '2025-05-16', 0, '2025-04-24 13:09:38', '2025-04-24 13:09:38');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role_id`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin@gmail.com', '$2y$10$YwjH/6I9aUfR3NUlYnoZS.AZ8KeeZBIacpvXv4BCwHsrt4l60DNnm', 3, '2025-04-05 01:17:57', NULL, '2025-04-05 01:17:57', '2025-04-05 01:17:57'),
(6, 'ismaildilali@gmail.com', '$2y$10$H9zOxM5YLp7Oxv.uKfJA8eysxAipXD5MGSiuUlto9VUsGJ.EeojMO', 1, '2025-04-06 12:20:28', 'hpyzFg2gYquueHoi8r2Mg1bmJFlrFERbDpqexNtPdkphcQN53GgllnaohUQQ', '2025-04-06 12:18:01', '2025-04-06 12:20:28'),
(13, 'dilaly08@gmail.com', '$2y$10$c1znpohElX2pVySMEXf8o.FpWmobIMicg5hx/jNcnVa9j1svnHGCC', 2, '2025-04-06 13:14:29', 'qKYuOC7tu0voCegM695XcXWUOKxwlAh9Zehe3MhqSXwJHdTDupyOmfw4YIdw', '2025-04-06 13:13:41', '2025-04-06 13:14:29'),
(15, 'hakroubi@gmail.com', '$2y$10$HMBR27B0gRe7r4OxYkXRoeLArl90hlGEikD0XXxdgnLOZV/foP/tS', 2, '2025-04-18 11:15:44', NULL, '2025-04-18 11:14:19', '2025-04-18 11:15:44');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `amariyas`
--
ALTER TABLE `amariyas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `amariyas_service_id_foreign` (`service_id`);

--
-- Index pour la table `animations`
--
ALTER TABLE `animations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `animations_service_id_foreign` (`service_id`);

--
-- Index pour la table `clothing_items`
--
ALTER TABLE `clothing_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clothing_items_service_id_foreign` (`service_id`);

--
-- Index pour la table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `conversations_mariee_id_traiteur_id_unique` (`mariee_id`,`traiteur_id`),
  ADD KEY `conversations_traiteur_id_foreign` (`traiteur_id`);

--
-- Index pour la table `decorations`
--
ALTER TABLE `decorations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `decorations_service_id_foreign` (`service_id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `makeups`
--
ALTER TABLE `makeups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `makeups_service_id_foreign` (`service_id`);

--
-- Index pour la table `makeup_portfolio_items`
--
ALTER TABLE `makeup_portfolio_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `makeup_portfolio_items_makeup_id_foreign` (`makeup_id`);

--
-- Index pour la table `mariees`
--
ALTER TABLE `mariees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mariees_user_id_foreign` (`user_id`);

--
-- Index pour la table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_items_service_id_foreign` (`service_id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_user_id_foreign` (`user_id`),
  ADD KEY `messages_conversation_id_created_at_index` (`conversation_id`,`created_at`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `negafas`
--
ALTER TABLE `negafas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `negafas_service_id_foreign` (`service_id`);

--
-- Index pour la table `negafa_portfolio_items`
--
ALTER TABLE `negafa_portfolio_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `negafa_portfolio_items_negafa_id_foreign` (`negafa_id`);

--
-- Index pour la table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Index pour la table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_reservation_id_status_index` (`reservation_id`,`status`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Index pour la table `photographers`
--
ALTER TABLE `photographers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `photographers_service_id_foreign` (`service_id`);

--
-- Index pour la table `photographer_portfolio_items`
--
ALTER TABLE `photographer_portfolio_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `photographer_portfolio_items_photographer_id_foreign` (`photographer_id`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservations_traiteur_id_foreign` (`traiteur_id`),
  ADD KEY `reservations_mariee_id_traiteur_id_event_date_index` (`mariee_id`,`traiteur_id`,`event_date`);

--
-- Index pour la table `reservation_services`
--
ALTER TABLE `reservation_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_services_service_id_foreign` (`service_id`),
  ADD KEY `reservation_services_reservation_id_service_id_index` (`reservation_id`,`service_id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `salles`
--
ALTER TABLE `salles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salles_service_id_foreign` (`service_id`);

--
-- Index pour la table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `services_traiteur_id_foreign` (`traiteur_id`),
  ADD KEY `services_category_id_foreign` (`category_id`);

--
-- Index pour la table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `traiteurs`
--
ALTER TABLE `traiteurs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `traiteurs_user_id_foreign` (`user_id`);

--
-- Index pour la table `traiteur_availabilities`
--
ALTER TABLE `traiteur_availabilities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `traiteur_availabilities_traiteur_id_date_unique` (`traiteur_id`,`date`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `amariyas`
--
ALTER TABLE `amariyas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `animations`
--
ALTER TABLE `animations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `clothing_items`
--
ALTER TABLE `clothing_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `decorations`
--
ALTER TABLE `decorations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `makeups`
--
ALTER TABLE `makeups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `makeup_portfolio_items`
--
ALTER TABLE `makeup_portfolio_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `mariees`
--
ALTER TABLE `mariees`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `negafas`
--
ALTER TABLE `negafas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `negafa_portfolio_items`
--
ALTER TABLE `negafa_portfolio_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `photographers`
--
ALTER TABLE `photographers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `photographer_portfolio_items`
--
ALTER TABLE `photographer_portfolio_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `reservation_services`
--
ALTER TABLE `reservation_services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `salles`
--
ALTER TABLE `salles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `traiteurs`
--
ALTER TABLE `traiteurs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `traiteur_availabilities`
--
ALTER TABLE `traiteur_availabilities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `amariyas`
--
ALTER TABLE `amariyas`
  ADD CONSTRAINT `amariyas_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `animations`
--
ALTER TABLE `animations`
  ADD CONSTRAINT `animations_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `clothing_items`
--
ALTER TABLE `clothing_items`
  ADD CONSTRAINT `clothing_items_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_mariee_id_foreign` FOREIGN KEY (`mariee_id`) REFERENCES `mariees` (`id`),
  ADD CONSTRAINT `conversations_traiteur_id_foreign` FOREIGN KEY (`traiteur_id`) REFERENCES `traiteurs` (`id`);

--
-- Contraintes pour la table `decorations`
--
ALTER TABLE `decorations`
  ADD CONSTRAINT `decorations_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `makeups`
--
ALTER TABLE `makeups`
  ADD CONSTRAINT `makeups_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `makeup_portfolio_items`
--
ALTER TABLE `makeup_portfolio_items`
  ADD CONSTRAINT `makeup_portfolio_items_makeup_id_foreign` FOREIGN KEY (`makeup_id`) REFERENCES `makeups` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `mariees`
--
ALTER TABLE `mariees`
  ADD CONSTRAINT `mariees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `negafas`
--
ALTER TABLE `negafas`
  ADD CONSTRAINT `negafas_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `negafa_portfolio_items`
--
ALTER TABLE `negafa_portfolio_items`
  ADD CONSTRAINT `negafa_portfolio_items_negafa_id_foreign` FOREIGN KEY (`negafa_id`) REFERENCES `negafas` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `photographers`
--
ALTER TABLE `photographers`
  ADD CONSTRAINT `photographers_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `photographer_portfolio_items`
--
ALTER TABLE `photographer_portfolio_items`
  ADD CONSTRAINT `photographer_portfolio_items_photographer_id_foreign` FOREIGN KEY (`photographer_id`) REFERENCES `photographers` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_mariee_id_foreign` FOREIGN KEY (`mariee_id`) REFERENCES `mariees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_traiteur_id_foreign` FOREIGN KEY (`traiteur_id`) REFERENCES `traiteurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reservation_services`
--
ALTER TABLE `reservation_services`
  ADD CONSTRAINT `reservation_services_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservation_services_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `salles`
--
ALTER TABLE `salles`
  ADD CONSTRAINT `salles_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`),
  ADD CONSTRAINT `services_traiteur_id_foreign` FOREIGN KEY (`traiteur_id`) REFERENCES `traiteurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `traiteurs`
--
ALTER TABLE `traiteurs`
  ADD CONSTRAINT `traiteurs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `traiteur_availabilities`
--
ALTER TABLE `traiteur_availabilities`
  ADD CONSTRAINT `traiteur_availabilities_traiteur_id_foreign` FOREIGN KEY (`traiteur_id`) REFERENCES `traiteurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
