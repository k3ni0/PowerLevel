-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 29 mai 2025 à 13:38
-- Version du serveur : 10.11.10-MariaDB-log
-- Version de PHP : 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `u629142522_PowerLevel`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password_hash`) VALUES
(4, 'admin', '$2y$10$zJne17aIvLq8VQzh4/dMLOTI9XjIeIu1FNjSAlptLE26E4wyB75MG');

-- --------------------------------------------------------

--
-- Structure de la table `badges`
--

CREATE TABLE `badges` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `level_required` int(11) DEFAULT NULL,
  `conditions_json` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `badges`
--

INSERT INTO `badges` (`id`, `name`, `description`, `icon`, `level_required`, `conditions_json`) VALUES
(3, 'Warrior', 'Avoir atteint le niveau 5', 'https://png.pngtree.com/png-vector/20240202/ourmid/pngtree-bodybuilding-badge-weightlifter-png-image_11585473.png', 5, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `penalties`
--

CREATE TABLE `penalties` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `date_applied` date DEFAULT NULL,
  `level_penalty` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `prestige_styles`
--

CREATE TABLE `prestige_styles` (
  `id` int(11) NOT NULL,
  `code` char(2) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `icon_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `prestige_styles`
--

INSERT INTO `prestige_styles` (`id`, `code`, `name`, `icon_url`) VALUES
(1, 'E', NULL, 'https://powerlevel.guillaume-foucher.fr/assets/img/ornement_rang_e.png'),
(2, 'D', NULL, 'https://powerlevel.guillaume-foucher.fr/assets/img/ornement_rang_d.png'),
(3, 'C', NULL, 'https://powerlevel.guillaume-foucher.fr/assets/img/ornement_rang_c.png'),
(4, 'B', NULL, 'https://powerlevel.guillaume-foucher.fr/assets/img/ornement_rang_b.png'),
(5, 'A', NULL, 'https://powerlevel.guillaume-foucher.fr/assets/img/ornement_rang_a.png'),
(6, 'S', NULL, 'https://powerlevel.guillaume-foucher.fr/assets/img/ornement_rang_s.png'),
(7, 'N', 'Nation', 'https://powerlevel.guillaume-foucher.fr/assets/img/test-.png');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `level` int(11) DEFAULT 1,
  `experience` int(11) DEFAULT 0,
  `prestige` char(1) DEFAULT 'E',
  `profile_type` enum('débutant','amateur') NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `theme_color` varchar(7) DEFAULT '#3498db',
  `bio` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `rest_day_1` enum('lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche') DEFAULT NULL,
  `rest_day_2` enum('lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche') DEFAULT NULL,
  `last_prestige_offer` datetime DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT 0,
  `public_token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `level`, `experience`, `prestige`, `profile_type`, `avatar`, `theme_color`, `bio`, `created_at`, `rest_day_1`, `rest_day_2`, `last_prestige_offer`, `age`, `is_public`, `public_token`) VALUES
(1, 'K3NII0', 'gfoucher.creation@gmail.com', '$2y$10$D6WXnaBxBDhIXrPck1cvzOznThnant2nUw2qCB9g7n2/hgqpIAjUe', 2, 100, 'E', 'amateur', 'https://www.lesaventuresludiques.com/wp-content/uploads/2025/03/nouveau-jeu-solo-leveling.jpg', '#3498db', NULL, '2025-03-21 22:00:57', 'lundi', 'vendredi', '2025-03-21 23:13:46', 28, 1, 'k3nii0-c611e662'),
(2, 'Dragoutoon', 'dragoutoon.twitch@gmail.com', '$2y$10$1QPOBe9HMRp3nzr1osXjyOJTG16cI.d1fbkpeZYx6DaeR0bP/NijG', 7, 600, 'E', 'amateur', 'https://media2.giphy.com/media/v1.Y2lkPTc5MGI3NjExNmQ3b2tiZXFsZzRqZnpxMDZjYXUyZGlyYnFnZjd3YW9nOWF5NmJmdCZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/ySvhFxq6Z4LrbqaikJ/giphy.gif', '#3498db', NULL, '2025-03-22 10:01:37', 'mercredi', 'samedi', NULL, 26, 1, 'dragoutoon-bafe0b12');

-- --------------------------------------------------------

--
-- Structure de la table `user_badges`
--

CREATE TABLE `user_badges` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `badge_id` int(11) DEFAULT NULL,
  `date_obtained` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_badges`
--

INSERT INTO `user_badges` (`id`, `user_id`, `badge_id`, `date_obtained`) VALUES
(19, 2, 3, '2025-03-28');

-- --------------------------------------------------------

--
-- Structure de la table `user_stats`
--

CREATE TABLE `user_stats` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_workouts` int(11) DEFAULT 0,
  `weekly_streak` int(11) DEFAULT 0,
  `total_penalties` int(11) DEFAULT 0,
  `total_badges` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_workouts`
--

CREATE TABLE `user_workouts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `workout_id` int(11) DEFAULT NULL,
  `date_performed` date DEFAULT NULL,
  `success` tinyint(1) DEFAULT NULL,
  `penalty_applied` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_workouts`
--

INSERT INTO `user_workouts` (`id`, `user_id`, `workout_id`, `date_performed`, `success`, `penalty_applied`) VALUES
(108, 2, 10, '2025-03-23', 1, 0),
(111, 2, 10, '2025-03-24', 1, 0),
(112, 2, 10, '2025-03-27', 1, 0),
(113, 2, 10, '2025-03-28', 1, 0),
(115, 2, 10, '2025-04-06', 1, 0),
(116, 2, 12, '2025-04-08', 1, 0),
(121, 1, 10, '2025-05-29', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `workouts`
--

CREATE TABLE `workouts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level_required` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `workout_blocks`
--

CREATE TABLE `workout_blocks` (
  `id` int(11) NOT NULL,
  `level_min` int(11) NOT NULL,
  `level_max` int(11) NOT NULL,
  `duration_minutes` int(11) NOT NULL,
  `prestige_required` char(1) DEFAULT 'E',
  `profile_type` enum('débutant','amateur') DEFAULT 'débutant'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `workout_blocks`
--

INSERT INTO `workout_blocks` (`id`, `level_min`, `level_max`, `duration_minutes`, `prestige_required`, `profile_type`) VALUES
(9, 1, 5, 8, 'E', 'débutant'),
(10, 1, 5, 10, 'E', 'amateur'),
(11, 6, 10, 10, 'E', 'débutant'),
(12, 6, 10, 10, 'E', 'amateur');

-- --------------------------------------------------------

--
-- Structure de la table `workout_exercises`
--

CREATE TABLE `workout_exercises` (
  `id` int(11) NOT NULL,
  `block_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `repetitions` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `workout_exercises`
--

INSERT INTO `workout_exercises` (`id`, `block_id`, `name`, `repetitions`) VALUES
(38, 9, 'x3 Pompes classique', 'x2'),
(39, 9, 'x5 Squats', 'x2'),
(40, 9, 'x5 Crunchs', 'x2'),
(41, 9, 'Gainage', '5s'),
(46, 11, 'x4 Pompes classique', 'x2'),
(47, 11, 'x7 Squats', 'x2'),
(48, 11, 'x7 Crunchs', 'x2'),
(49, 11, 'Gainage', '10s'),
(50, 10, 'x5 Pompes classique', 'x2'),
(51, 10, 'x10 Squats', 'x2'),
(52, 10, 'x10 Crunchs', 'x2'),
(53, 10, 'Gainage', '10s'),
(54, 12, 'x6 Pompes classique', 'x2'),
(55, 12, 'x13 Squats', 'x2'),
(56, 12, 'x1 3 Crunchs', 'x2'),
(57, 12, 'Gainage', '15s');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Index pour la table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `penalties`
--
ALTER TABLE `penalties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `prestige_styles`
--
ALTER TABLE `prestige_styles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `user_badges`
--
ALTER TABLE `user_badges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `badge_id` (`badge_id`);

--
-- Index pour la table `user_stats`
--
ALTER TABLE `user_stats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `user_workouts`
--
ALTER TABLE `user_workouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_workout_block` (`workout_id`);

--
-- Index pour la table `workouts`
--
ALTER TABLE `workouts`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `workout_blocks`
--
ALTER TABLE `workout_blocks`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `workout_exercises`
--
ALTER TABLE `workout_exercises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `block_id` (`block_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `badges`
--
ALTER TABLE `badges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `penalties`
--
ALTER TABLE `penalties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `prestige_styles`
--
ALTER TABLE `prestige_styles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `user_badges`
--
ALTER TABLE `user_badges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `user_stats`
--
ALTER TABLE `user_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user_workouts`
--
ALTER TABLE `user_workouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT pour la table `workouts`
--
ALTER TABLE `workouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `workout_blocks`
--
ALTER TABLE `workout_blocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `workout_exercises`
--
ALTER TABLE `workout_exercises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `penalties`
--
ALTER TABLE `penalties`
  ADD CONSTRAINT `penalties_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `user_badges`
--
ALTER TABLE `user_badges`
  ADD CONSTRAINT `user_badges_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_badges_ibfk_2` FOREIGN KEY (`badge_id`) REFERENCES `badges` (`id`);

--
-- Contraintes pour la table `user_stats`
--
ALTER TABLE `user_stats`
  ADD CONSTRAINT `user_stats_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `user_workouts`
--
ALTER TABLE `user_workouts`
  ADD CONSTRAINT `fk_workout_block` FOREIGN KEY (`workout_id`) REFERENCES `workout_blocks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_workouts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `workout_exercises`
--
ALTER TABLE `workout_exercises`
  ADD CONSTRAINT `workout_exercises_ibfk_1` FOREIGN KEY (`block_id`) REFERENCES `workout_blocks` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
