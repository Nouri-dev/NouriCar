

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `MVC-1`
--

-- --------------------------------------------------------

--
-- Structure de la table `annonces`
--

CREATE TABLE `annonces` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `prix` decimal(10,0) NOT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `annonces`
--

INSERT INTO `annonces` (`id`, `titre`, `description`, `prix`, `actif`, `created_at`, `users_id`) VALUES
(1, 'Lorem ipsum dolor sit', 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Eos laborum molestias odit? Dignissimos dolor neque voluptatem, laudantium tempora recusandae iusto non ullam, ex aliquid repellat? Porro odit rerum molestiae velit?', '190000', 1, '2023-05-22 03:02:46', 5),
(2, 'Lorem ipsum dolor sit', 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Eos laborum molestias odit? Dignissimos dolor neque voluptatem, laudantium tempora recusandae iusto non ullam, ex aliquid repellat? Porro odit rerum molestiae velit?', '70000', 1, '2023-05-22 03:04:53', 5),
(3, 'Lorem ipsum dolor sit', 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Eos laborum molestias odit? Dignissimos dolor neque voluptatem, laudantium tempora recusandae iusto non ullam, ex aliquid repellat? Porro odit rerum molestiae velit?', '30000', 1, '2023-05-22 03:06:15', 5),
(4, 'Lorem ipsum dolor sit', 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Eos laborum molestias odit? Dignissimos dolor neque voluptatem, laudantium tempora recusandae iusto non ullam, ex aliquid repellat? Porro odit rerum molestiae velit?', '23000', 1, '2023-05-22 03:07:20', 5),
(5, 'Lorem ipsum dolor sit', 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Eos laborum molestias odit? Dignissimos dolor neque voluptatem, laudantium tempora recusandae iusto non ullam, ex aliquid repellat? Porro odit rerum molestiae velit?', '25000', 1, '2023-05-22 03:08:05', 5),
(6, 'Lorem ipsum dolor sit', 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Eos laborum molestias odit? Dignissimos dolor neque voluptatem, laudantium tempora recusandae iusto non ullam, ex aliquid repellat? Porro odit rerum molestiae velit?', '20000', 1, '2023-05-22 03:09:07', 5);

-- --------------------------------------------------------

--
-- Structure de la table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `annonces_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `images`
--

INSERT INTO `images` (`id`, `nom`, `annonces_id`) VALUES
(1, '646abf32ef57e4.17492161.jpg', 1),
(2, '646abf342473a1.17335659.jpg', 1),
(3, '646abf35475a27.70790228.jpg', 1),
(4, '646abfb1bf98e3.86385643.jpg', 2),
(5, '646abfb3251339.10480394.jpg', 2),
(6, '646abfb48200b4.77751972.jpg', 2),
(7, '646ac004c982b6.78514626.jpg', 3),
(8, '646ac0059ddbe4.40278238.jpg', 3),
(9, '646ac006a35ab0.23808732.jpg', 3),
(10, '646ac0463dcdf3.18915847.jpg', 4),
(11, '646ac0471b0f80.77753505.jpg', 4),
(12, '646ac04773f3f3.04770126.jpg', 4),
(13, '646ac073b4d315.73754890.jpg', 5),
(14, '646ac0747411e0.80471317.jpg', 5),
(15, '646ac0752a6628.54050797.jpg', 5),
(16, '646ac0b044aff5.26731653.jpg', 6),
(17, '646ac0b1451077.89000230.jpg', 6),
(18, '646ac0b240f0e0.42322903.jpg', 6);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `roles` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `pseudo`, `roles`) VALUES
(5, 'Wayne@Corp.fr', '$argon2i$v=19$m=65536,t=4,p=1$N2o3b3R6MTJ1N3ZMZjYyTA$hHbHLIE0kvsH50DC0AxsdNu2oDK6ZMitChIl9gdOEUU', 'Batman', '[\"ROLE_ADMIN\"]'),
(21, 'Jason@Todd.fr', '$argon2i$v=19$m=65536,t=4,p=1$dEE1T2JhU0VpWFVFbHBlTg$+dyGyRzZEsavBHTSzNJ9ruok/93DLOhRdHb7xj7mchc', 'Robin', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `annonces`
--
ALTER TABLE `annonces`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`users_id`);

--
-- Index pour la table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `annonces_id` (`annonces_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `annonces`
--
ALTER TABLE `annonces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `annonces`
--
ALTER TABLE `annonces`
  ADD CONSTRAINT `annonces_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_users_id` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`annonces_id`) REFERENCES `annonces` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
