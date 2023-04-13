-- --------------------------------------------------------
-- Hôte :                        127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Listage des données de la table sfsession.categorie : ~3 rows (environ)
/*!40000 ALTER TABLE `categorie` DISABLE KEYS */;
INSERT INTO `categorie` (`id`, `nom_categorie`) VALUES
	(1, 'Webdesign'),
	(2, 'Bureautique'),
	(3, 'Développement Web');
/*!40000 ALTER TABLE `categorie` ENABLE KEYS */;

-- Listage des données de la table sfsession.doctrine_migration_versions : ~6 rows (environ)
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20230411115921', '2023-04-11 11:59:37', 26),
	('DoctrineMigrations\\Version20230411120141', '2023-04-11 12:01:46', 14),
	('DoctrineMigrations\\Version20230411125339', '2023-04-11 12:54:12', 91),
	('DoctrineMigrations\\Version20230411131057', '2023-04-11 13:13:18', 110),
	('DoctrineMigrations\\Version20230413131648', '2023-04-13 13:17:01', 159),
	('DoctrineMigrations\\Version20230413132458', '2023-04-13 13:25:01', 82);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;

-- Listage des données de la table sfsession.formateur : ~2 rows (environ)
/*!40000 ALTER TABLE `formateur` DISABLE KEYS */;
INSERT INTO `formateur` (`id`, `nom`, `prenom`, `date_naissance`, `telephone`, `adresse`) VALUES
	(1, 'SMAIL', 'Stéphane', '1980-04-02', '0632547968', '12 Rue des champs 67100 Strasbourg'),
	(2, 'MURMANN ', 'Mickael', '1985-04-29', '0763259466', '32 Bis rue de l\'Ibiscus 68000 Colmar');
/*!40000 ALTER TABLE `formateur` ENABLE KEYS */;

-- Listage des données de la table sfsession.formation : ~2 rows (environ)
/*!40000 ALTER TABLE `formation` DISABLE KEYS */;
INSERT INTO `formation` (`id`, `nom_formation`) VALUES
	(1, 'Développement Web'),
	(2, 'Webdesign');
/*!40000 ALTER TABLE `formation` ENABLE KEYS */;

-- Listage des données de la table sfsession.messenger_messages : ~0 rows (environ)
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;

-- Listage des données de la table sfsession.modules : ~9 rows (environ)
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` (`id`, `nom_module`, `categorie_id`) VALUES
	(1, 'Word', 2),
	(2, 'Excel', 2),
	(3, 'Figma', 1),
	(4, 'Powerpoint', 2),
	(5, 'HTML', 3),
	(6, 'CSS', 3),
	(7, 'PHP', 3),
	(8, 'SQL', 3),
	(9, 'Photoshop', 1);
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;

-- Listage des données de la table sfsession.programme : ~2 rows (environ)
/*!40000 ALTER TABLE `programme` DISABLE KEYS */;
INSERT INTO `programme` (`id`, `modules_id`, `sessions_id`, `duree`) VALUES
	(1, 3, 1, 3),
	(4, 6, 2, 5);
/*!40000 ALTER TABLE `programme` ENABLE KEYS */;

-- Listage des données de la table sfsession.session : ~3 rows (environ)
/*!40000 ALTER TABLE `session` DISABLE KEYS */;
INSERT INTO `session` (`id`, `nom_session`, `nb_place`, `date_debut`, `date_fin`, `formation_id`, `formateur_id`) VALUES
	(1, 'Plateau numérique', 25, '2023-04-12 08:30:00', '2024-02-21 17:00:00', 1, 1),
	(2, 'Strasbourg - DWWM2', 5, '2023-04-21 09:47:42', '2023-11-29 09:47:42', 1, 1),
	(3, 'Colmar - Webdesign1', 14, '2022-10-11 09:47:42', '2023-02-12 09:47:42', 2, 2);
/*!40000 ALTER TABLE `session` ENABLE KEYS */;

-- Listage des données de la table sfsession.stagiaire : ~3 rows (environ)
/*!40000 ALTER TABLE `stagiaire` DISABLE KEYS */;
INSERT INTO `stagiaire` (`id`, `nom`, `prenom`, `date_naissance`, `telephone`, `adresse`) VALUES
	(3, 'ROBERT', 'Paolo', '2000-08-10', '0624698624', '34 Rue de l\'abreuvoir 68000 Colmar'),
	(4, 'BARNABE', 'Louisa', '1988-09-29', '0645365477', '12 Avenue de Colmar 67100 Strasbourg'),
	(6, 'MESSI', 'Lionel', '1992-12-15', '0647852698', '12 Avenur de Auchan 67100 Strasbourg');
/*!40000 ALTER TABLE `stagiaire` ENABLE KEYS */;

-- Listage des données de la table sfsession.stagiaire_session : ~2 rows (environ)
/*!40000 ALTER TABLE `stagiaire_session` DISABLE KEYS */;
INSERT INTO `stagiaire_session` (`stagiaire_id`, `session_id`) VALUES
	(4, 1),
	(6, 1);
/*!40000 ALTER TABLE `stagiaire_session` ENABLE KEYS */;

-- Listage des données de la table sfsession.user : ~1 rows (environ)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`, `nom`, `prenom`) VALUES
	(1, 'pierre@session.com', '[]', '$2y$13$yT55gaayEeihlSHwHpvlEe4r6D0Mm0QA.RoRHwK2vTDTTfjuylwYK', 1, 'WIETRICH', 'Pierre');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
