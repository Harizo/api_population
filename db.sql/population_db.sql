-- --------------------------------------------------------
-- Hôte :                        127.0.0.1
-- Version du serveur:           10.1.13-MariaDB - mariadb.org binary distribution
-- SE du serveur:                Win32
-- HeidiSQL Version:             9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Export de la structure de la base pour population_db
DROP DATABASE IF EXISTS `population_db`;
CREATE DATABASE IF NOT EXISTS `population_db` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `population_db`;

-- Export de la structure de la table population_db. acteur
DROP TABLE IF EXISTS `acteur`;
CREATE TABLE IF NOT EXISTS `acteur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) DEFAULT NULL,
  `nom` longtext,
  `nif` longtext,
  `representant` varchar(100) DEFAULT '',
  `adresse` varchar(100) DEFAULT '',
  `id_type_acteur` int(11) DEFAULT NULL,
  `stat` varchar(25) DEFAULT '',
  `fonction` varchar(100) DEFAULT '',
  `telephone` varchar(20) DEFAULT '',
  `email` varchar(100) DEFAULT '',
  `rcs` varchar(20) DEFAULT '',
  `id_fokontany` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_acteur_type_acteur` (`id_type_acteur`),
  CONSTRAINT `FK_acteur_type_acteur` FOREIGN KEY (`id_type_acteur`) REFERENCES `type_acteur` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.acteur : ~3 rows (environ)
/*!40000 ALTER TABLE `acteur` DISABLE KEYS */;
INSERT INTO `acteur` (`id`, `code`, `nom`, `nif`, `representant`, `adresse`, `id_type_acteur`, `stat`, `fonction`, `telephone`, `email`, `rcs`, `id_fokontany`) VALUES
	(1, NULL, 'RAISON SOCIALE AGES', 'NIF', 'REPRESENTANT', 'Antsirindraano Ampanihy', 1, 'STAT', 'DG', '034 39 399 39', 'bruno@gmail.com', 'RCS', 1681),
	(2, NULL, 'TANY MEVA.', '', 'RAKOTO Florent', 'Nanisana 101 Antananarivo', 1, '', '', '', '', '', 1678),
	(3, NULL, 'TANY MEVA', '', 'RAVELO Jean Baptiste', 'Ambia Antevamena', 2, '', '', '', '', '', 3009);
/*!40000 ALTER TABLE `acteur` ENABLE KEYS */;

-- Export de la structure de la table population_db. action_strategique
DROP TABLE IF EXISTS `action_strategique`;
CREATE TABLE IF NOT EXISTS `action_strategique` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) DEFAULT '',
  `id_axe_strategique` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `FK_action_strategique_axe_strategique` (`id_axe_strategique`),
  CONSTRAINT `FK_action_strategique_axe_strategique` FOREIGN KEY (`id_axe_strategique`) REFERENCES `axe_strategique` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.action_strategique : ~14 rows (environ)
/*!40000 ALTER TABLE `action_strategique` DISABLE KEYS */;
INSERT INTO `action_strategique` (`id`, `code`, `id_axe_strategique`, `action`) VALUES
	(1, '2.1', 2, 'Lâ€™allÃ¨gement des charges supplÃ©mentaires pour accÃ©der Ã  lâ€™enseignement de base, du cÃ´tÃ© des mÃ©nages extrÃªmement pauvres'),
	(2, '1.1', 1, 'La mise Ã  lâ€™Ã©chelle des transferts sociaux'),
	(3, '2.2', 2, 'Le renforcement de lâ€™accÃ¨s aux soins de santÃ© pour les groupes les plus vulnÃ©rables'),
	(4, '2.3', 2, 'La contribution Ã  la lutte contre la malnutrition (en mettant un accent particulier sur les premiers 1000 jours)'),
	(5, '2.4', 2, 'Le renforcement de lâ€™accÃ¨s aux services sociaux spÃ©cialisÃ©s pour les groupes vulnÃ©rables spÃ©cifiques'),
	(6, '1.2', 1, 'La promotion des programmes de transferts conditionnÃ©s au travail/aux actifs'),
	(7, '1.3', 1, 'Le dÃ©veloppement dâ€™un systÃ¨me national de filets sociaux rÃ©actifs aux chocs'),
	(8, '3.1', 3, 'Lâ€™identification de la meilleure faÃ§on de lier les mÃ©nages aux services productifs (qui peut varier selon la zone dâ€™intervention).'),
	(9, '3.2', 3, '2 La mise en place dâ€™un programme complet comprenant des transferts sociaux pour des mÃ©nages extrÃªmement pauvres et des appuis complÃ©mentaires pour les lier aux services productifs'),
	(10, '3.3', 3, 'La mise en place dâ€™un mÃ©canisme pour rÃ©-Ã©valuer de faÃ§on rÃ©guliÃ¨re lâ€™Ã©ligibilitÃ© des mÃ©nages bÃ©nÃ©ficiaires des programmes de transferts sociaux'),
	(11, '4.1', 4, 'Lâ€™extension de la couverture en santÃ©'),
	(12, '4.2', 4, 'La promotion de la sÃ©curitÃ© sociale :  renforcer la transparence, la redevabilitÃ© et la viabilitÃ© des rÃ©gimes ; et Ã©tendre leur couverture au sein du secteur formel'),
	(13, '4.3', 4, 'La promotion de la sÃ©curitÃ© sociale dans lâ€™Ã©conomie informelle, en se basant sur les conclusions dâ€™une Ã©tude de faisabilitÃ©.'),
	(14, '4.4', 4, 'La promotion de lâ€™accÃ¨s des agriculteurs aux assurances appropriÃ©s');
/*!40000 ALTER TABLE `action_strategique` ENABLE KEYS */;

-- Export de la structure de la table population_db. alimentation
DROP TABLE IF EXISTS `alimentation`;
CREATE TABLE IF NOT EXISTS `alimentation` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_type_aliment` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_alimentation_type_aliment` (`id_type_aliment`),
  KEY `FK_alimentation_menage` (`id_menage`),
  CONSTRAINT `FK_alimentation_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_alimentation_type_aliment` FOREIGN KEY (`id_type_aliment`) REFERENCES `type_aliment` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.alimentation : ~0 rows (environ)
/*!40000 ALTER TABLE `alimentation` DISABLE KEYS */;
/*!40000 ALTER TABLE `alimentation` ENABLE KEYS */;

-- Export de la structure de la table population_db. axe_strategique
DROP TABLE IF EXISTS `axe_strategique`;
CREATE TABLE IF NOT EXISTS `axe_strategique` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `axe` longtext,
  `code` varchar(20) DEFAULT '',
  `objectif` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.axe_strategique : ~4 rows (environ)
/*!40000 ALTER TABLE `axe_strategique` DISABLE KEYS */;
INSERT INTO `axe_strategique` (`id`, `axe`, `code`, `objectif`) VALUES
	(1, 'Transferts sociaux', '1', 'Les mÃ©nages extrÃªmement pauvres auront la capacitÃ© de satisfaire leurs besoins fondamentaux et auront amÃ©liorÃ© leur rÃ©silience'),
	(2, 'AccÃ¨s aux services sociaux de base', '2', 'Il y aura une augmentation de lâ€™accÃ¨s aux services sociaux de base, surtout pour les personnes extrÃªmement pauvres et vulnÃ©rables ; et aux services spÃ©cialisÃ©s pour les groupes marginalisÃ©s spÃ©cifiques.'),
	(3, 'Renforce-ment des moyens de subsistance', '3', 'Des mÃ©nages extrÃªmement pauvres auront augmentÃ© leurs revenus et amÃ©liorÃ© leurs conditions de vie, Ã  travers le renforcement de leurs moyens de subsistance.'),
	(4, 'RÃ©gime contributif', '4', 'Le rÃ©gime contributif sera plus viable, efficace et Ã©quitable et aura une plus grande couverture (y compris une ouverture envers le secteur informel).Le rÃ©gime contributif sera plus viable, efficace et Ã©quitable et aura une plus grande couverture (y compris une ouverture envers le secteur informel).');
/*!40000 ALTER TABLE `axe_strategique` ENABLE KEYS */;

-- Export de la structure de la table population_db. biens_equipements
DROP TABLE IF EXISTS `biens_equipements`;
CREATE TABLE IF NOT EXISTS `biens_equipements` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_biens_equipements` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_biens_equipements_type_bien_equipement` (`id_biens_equipements`),
  KEY `FK_biens_equipements_menage` (`id_menage`),
  CONSTRAINT `FK_biens_equipements_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_biens_equipements_type_bien_equipement` FOREIGN KEY (`id_biens_equipements`) REFERENCES `type_bien_equipement` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.biens_equipements : ~0 rows (environ)
/*!40000 ALTER TABLE `biens_equipements` DISABLE KEYS */;
/*!40000 ALTER TABLE `biens_equipements` ENABLE KEYS */;

-- Export de la structure de la table population_db. combustible
DROP TABLE IF EXISTS `combustible`;
CREATE TABLE IF NOT EXISTS `combustible` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_type_combustible` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_combustible_menage` (`id_menage`),
  KEY `FK_combustible_type_combustible` (`id_type_combustible`),
  CONSTRAINT `FK_combustible_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_combustible_type_combustible` FOREIGN KEY (`id_type_combustible`) REFERENCES `type_combustible` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.combustible : ~0 rows (environ)
/*!40000 ALTER TABLE `combustible` DISABLE KEYS */;
/*!40000 ALTER TABLE `combustible` ENABLE KEYS */;

-- Export de la structure de la table population_db. commune
DROP TABLE IF EXISTS `commune`;
CREATE TABLE IF NOT EXISTS `commune` (
  `code` varchar(10) DEFAULT '',
  `nom` varchar(100) DEFAULT '',
  `district_id` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `commune_district_id_idx` (`district_id`),
  CONSTRAINT `FK_commune_district` FOREIGN KEY (`district_id`) REFERENCES `district` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.commune : ~50 rows (environ)
/*!40000 ALTER TABLE `commune` DISABLE KEYS */;
INSERT INTO `commune` (`code`, `nom`, `district_id`, `id`) VALUES
	('10401', 'Ambohidratrimo', 4, 1),
	('10402', 'Anosiala', 4, 2),
	('10403', 'Talatamaty', 4, 3),
	('10404', 'Antehiroka', 4, 4),
	('10405', 'Iarinarivo', 4, 5),
	('10406', 'Ivato-Firaisana', 4, 6),
	('10407', 'Ivato-Airport', 4, 7),
	('10408', 'Ambohitrimanjaka', 4, 8),
	('10409', 'Mahitsy', 4, 9),
	('10410', 'Merimandroso', 4, 10),
	('10411', 'Ambatolampy', 4, 11),
	('10412', 'Ampangabe', 4, 12),
	('10413', 'Ampanotokana', 4, 13),
	('10414', 'Mananjara', 4, 14),
	('10415', 'Manjakavaradrano', 4, 15),
	('10416', 'Antsahafilo', 4, 16),
	('10417', 'Ambohimanjaka', 4, 17),
	('10418', 'Fiadanana', 4, 18),
	('10419', 'Mahabo', 4, 19),
	('10420', 'Mahereza', 4, 20),
	('10421', 'Antanetibe', 4, 21),
	('10422', 'Ambohipihaonana', 4, 22),
	('10423', 'Ambato', 4, 23),
	('10424', 'Anjanadoria', 4, 24),
	('10425', 'Avaratsena', 4, 25),
	('10801', 'Andramasina', 8, 26),
	('10802', 'Sabotsy-Ambohitromby', 8, 27),
	('10803', 'Andohariana', 8, 28),
	('10804', 'Mandrosoa', 8, 29),
	('10805', 'Alatsinainy-Bakaro', 8, 30),
	('10806', 'Antotohazo', 8, 31),
	('10807', 'Ambohimiadana', 8, 32),
	('10808', 'Tankafatra', 8, 33),
	('10809', 'Alarobia-Vatosola', 8, 34),
	('10810', 'Fitsinjovana-Bakaro', 8, 35),
	('10811', 'Sabotsy-Manjakavahoaka', 8, 36),
	('10812', 'Anosibe-Trimoloharano', 8, 37),
	('10501', 'Ankazobe', 5, 38),
	('10502', 'Talata-Angavo', 5, 39),
	('10503', 'Ambohitromby', 5, 40),
	('10504', 'Antotohazo', 5, 41),
	('10505', 'Marondry', 5, 42),
	('10506', 'Fihaonana', 5, 43),
	('10507', 'Mahavelona', 5, 44),
	('10508', 'Fiadanana', 5, 45),
	('10509', 'Tsaramasoandro', 5, 46),
	('10510', 'Ambolotarakely', 5, 47),
	('10511', 'Antakavana', 5, 48),
	('10512', 'Kiangara', 5, 49),
	('10513', 'Miantso', 5, 50);
/*!40000 ALTER TABLE `commune` ENABLE KEYS */;

-- Export de la structure de la table population_db. culture
DROP TABLE IF EXISTS `culture`;
CREATE TABLE IF NOT EXISTS `culture` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_type_culture` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_culture_type_culture` (`id_type_culture`),
  KEY `FK_culture_menage` (`id_menage`),
  CONSTRAINT `FK_culture_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_culture_type_culture` FOREIGN KEY (`id_type_culture`) REFERENCES `type_culture` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.culture : ~0 rows (environ)
/*!40000 ALTER TABLE `culture` DISABLE KEYS */;
/*!40000 ALTER TABLE `culture` ENABLE KEYS */;

-- Export de la structure de la table population_db. culture_femme
DROP TABLE IF EXISTS `culture_femme`;
CREATE TABLE IF NOT EXISTS `culture_femme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_type_culture` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_culture_femme_type_culture` (`id_type_culture`),
  KEY `FK_culture_femme_menage` (`id_menage`),
  CONSTRAINT `FK_culture_femme_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_culture_femme_type_culture` FOREIGN KEY (`id_type_culture`) REFERENCES `type_culture` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.culture_femme : ~0 rows (environ)
/*!40000 ALTER TABLE `culture_femme` DISABLE KEYS */;
/*!40000 ALTER TABLE `culture_femme` ENABLE KEYS */;

-- Export de la structure de la table population_db. decaissement
DROP TABLE IF EXISTS `decaissement`;
CREATE TABLE IF NOT EXISTS `decaissement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_financement_intervention` int(11) DEFAULT NULL,
  `nom_informateur` varchar(50) DEFAULT '',
  `prenom_informateur` varchar(50) DEFAULT '',
  `telephone_informateur` varchar(20) DEFAULT '',
  `email_informateur` varchar(100) DEFAULT '',
  `id_acteur` int(11) DEFAULT NULL,
  `montant_initial` double DEFAULT NULL,
  `montant_revise` double DEFAULT NULL,
  `date_revision` date DEFAULT NULL,
  `montant_mesure_accompagnement` double DEFAULT NULL,
  `decaissement_prevu` double DEFAULT NULL,
  `decaissement_effectif` double DEFAULT NULL,
  `decaissement_prevu_cumule` double DEFAULT NULL,
  `decaissement_cumule` double DEFAULT NULL,
  `decaissement_effectif_beneficiaire` double DEFAULT NULL,
  `decaissement_effectif_beneficiaire_cumule` double DEFAULT NULL,
  `nombre_beneficiaire` int(11) DEFAULT NULL,
  `nombre_beneficiaire_cumule` int(11) DEFAULT NULL,
  `nombre_beneficiaire_sortant` int(11) DEFAULT NULL,
  `nombre_beneficiaire_sortant_cumule` int(11) DEFAULT NULL,
  `transfert_direct_beneficiaire` double DEFAULT NULL,
  `date_debut_periode` date DEFAULT NULL,
  `date_fin_periode` date DEFAULT NULL,
  `flag_integration_donnees` bit(1) DEFAULT NULL,
  `nouvelle_integration` bit(1) DEFAULT NULL,
  `commentaire` varchar(100) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `FK_decaissement_financement_intervention` (`id_financement_intervention`),
  KEY `FK_decaissement_acteur` (`id_acteur`),
  CONSTRAINT `FK_decaissement_acteur` FOREIGN KEY (`id_acteur`) REFERENCES `acteur` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_decaissement_financement_intervention` FOREIGN KEY (`id_financement_intervention`) REFERENCES `financement_intervention` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.decaissement : ~0 rows (environ)
/*!40000 ALTER TABLE `decaissement` DISABLE KEYS */;
INSERT INTO `decaissement` (`id`, `id_financement_intervention`, `nom_informateur`, `prenom_informateur`, `telephone_informateur`, `email_informateur`, `id_acteur`, `montant_initial`, `montant_revise`, `date_revision`, `montant_mesure_accompagnement`, `decaissement_prevu`, `decaissement_effectif`, `decaissement_prevu_cumule`, `decaissement_cumule`, `decaissement_effectif_beneficiaire`, `decaissement_effectif_beneficiaire_cumule`, `nombre_beneficiaire`, `nombre_beneficiaire_cumule`, `nombre_beneficiaire_sortant`, `nombre_beneficiaire_sortant_cumule`, `transfert_direct_beneficiaire`, `date_debut_periode`, `date_fin_periode`, `flag_integration_donnees`, `nouvelle_integration`, `commentaire`) VALUES
	(1, 1, '', '', '', '', NULL, 12500, 15000, NULL, NULL, 6000, 6000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12345, NULL, NULL, b'0', b'0', '');
/*!40000 ALTER TABLE `decaissement` ENABLE KEYS */;

-- Export de la structure de la table population_db. detail_type_transfert
DROP TABLE IF EXISTS `detail_type_transfert`;
CREATE TABLE IF NOT EXISTS `detail_type_transfert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) DEFAULT '',
  `id_unite_mesure` int(11) DEFAULT NULL,
  `id_type_transfert` int(11) DEFAULT NULL,
  `code` varchar(5) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `FK_detail_type_transfert_unite_mesure` (`id_unite_mesure`),
  KEY `FK_detail_type_transfert_type_transfert` (`id_type_transfert`),
  CONSTRAINT `FK_detail_type_transfert_type_transfert` FOREIGN KEY (`id_type_transfert`) REFERENCES `type_transfert` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_detail_type_transfert_unite_mesure` FOREIGN KEY (`id_unite_mesure`) REFERENCES `unite_mesure` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.detail_type_transfert : ~28 rows (environ)
/*!40000 ALTER TABLE `detail_type_transfert` DISABLE KEYS */;
INSERT INTO `detail_type_transfert` (`id`, `description`, `id_unite_mesure`, `id_type_transfert`, `code`) VALUES
	(1, 'Cash', 1, 2, '1.1'),
	(2, 'Riz', 3, 1, '2.1.1'),
	(3, 'Huile', 2, 1, '2.1.2'),
	(4, 'CÃ©rÃ©ales', 3, 1, '2.1.3'),
	(5, 'Bank Mobil', 1, 2, '1.2'),
	(6, 'Microfinance', 1, 2, '1.3'),
	(7, 'Autres', 1, 2, '1.4'),
	(8, 'LÃ©gumineuses secs', 3, 1, '2.1.4'),
	(9, 'Lait', 2, 1, '2.1.5'),
	(10, 'Farine', 3, 1, '2.1.6'),
	(11, 'Autres', 4, 1, '2.1.7'),
	(12, 'Semences', 3, 1, '2.2.1'),
	(13, 'Animaux vivants', 4, 1, '2.2.2'),
	(14, 'Outils agricoles', 4, 1, '2.2.3'),
	(15, 'Alimentation pour les animaux', 3, 1, '2.2.4'),
	(16, 'Autre', 4, 1, '2.2.5'),
	(17, 'Uniforme scolaire', 4, 1, '2.3.1'),
	(18, 'Kit Scolaire', 4, 1, '2.3.2'),
	(19, 'Autres', 4, 1, '2.3.3'),
	(20, 'Cantine scolaire', 4, 3, '3.1.1'),
	(21, 'Bourse scolaire', 1, 3, '3.1.2'),
	(22, 'Autres', 4, 3, '3.1.3'),
	(23, 'CNSS gratuit', 4, 3, '3.2.1'),
	(24, 'Autre', 4, 3, '3.2.2'),
	(25, '. Sensibilisation sur la nutrition', 4, 3, '3.3.1'),
	(26, 'Formation', 4, 3, '3.3.2'),
	(27, 'Coup de pouce', 4, 3, '3.3.3'),
	(28, 'Autres', 4, 3, '3.3.4');
/*!40000 ALTER TABLE `detail_type_transfert` ENABLE KEYS */;

-- Export de la structure de la table population_db. detail_type_transfert_intervention
DROP TABLE IF EXISTS `detail_type_transfert_intervention`;
CREATE TABLE IF NOT EXISTS `detail_type_transfert_intervention` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_intervention` int(11) DEFAULT NULL,
  `id_detail_type_transfert` int(11) DEFAULT NULL,
  `valeur_quantite` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_detail_type_transfert_intervention_intervention` (`id_intervention`),
  KEY `FK_detail_type_transfert_intervention_detail_type_transfert` (`id_detail_type_transfert`),
  CONSTRAINT `FK_detail_type_transfert_intervention_detail_type_transfert` FOREIGN KEY (`id_detail_type_transfert`) REFERENCES `detail_type_transfert` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_detail_type_transfert_intervention_intervention` FOREIGN KEY (`id_intervention`) REFERENCES `intervention` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.detail_type_transfert_intervention : ~2 rows (environ)
/*!40000 ALTER TABLE `detail_type_transfert_intervention` DISABLE KEYS */;
INSERT INTO `detail_type_transfert_intervention` (`id`, `id_intervention`, `id_detail_type_transfert`, `valeur_quantite`) VALUES
	(40, 1, 2, 10),
	(41, 1, 4, 5);
/*!40000 ALTER TABLE `detail_type_transfert_intervention` ENABLE KEYS */;

-- Export de la structure de la table population_db. devise
DROP TABLE IF EXISTS `devise`;
CREATE TABLE IF NOT EXISTS `devise` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(15) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.devise : ~2 rows (environ)
/*!40000 ALTER TABLE `devise` DISABLE KEYS */;
INSERT INTO `devise` (`id`, `description`) VALUES
	(1, 'USD'),
	(2, 'EURO');
/*!40000 ALTER TABLE `devise` ENABLE KEYS */;

-- Export de la structure de la table population_db. difficultes_alimentaires
DROP TABLE IF EXISTS `difficultes_alimentaires`;
CREATE TABLE IF NOT EXISTS `difficultes_alimentaires` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_difficulte_alimentaire` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_difficultes_alimentaires_type_difficulte_alimentaire` (`id_difficulte_alimentaire`),
  KEY `FK_difficultes_alimentaires_menage` (`id_menage`),
  CONSTRAINT `FK_difficultes_alimentaires_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_difficultes_alimentaires_type_difficulte_alimentaire` FOREIGN KEY (`id_difficulte_alimentaire`) REFERENCES `type_difficulte_alimentaire` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.difficultes_alimentaires : ~0 rows (environ)
/*!40000 ALTER TABLE `difficultes_alimentaires` DISABLE KEYS */;
/*!40000 ALTER TABLE `difficultes_alimentaires` ENABLE KEYS */;

-- Export de la structure de la table population_db. district
DROP TABLE IF EXISTS `district`;
CREATE TABLE IF NOT EXISTS `district` (
  `code` varchar(10) DEFAULT '',
  `nom` varchar(45) DEFAULT '',
  `region_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `district_region_id_idx` (`region_id`),
  CONSTRAINT `FK_district_region` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.district : ~50 rows (environ)
/*!40000 ALTER TABLE `district` DISABLE KEYS */;
INSERT INTO `district` (`code`, `nom`, `region_id`, `id`) VALUES
	('101', 'Antananarivo-renivohitra', 1, 1),
	('102', 'Antananarivo-atsimondrano', 1, 2),
	('103', 'Antananarivo-avaradrano', 1, 3),
	('104', 'Ambohidratrimo', 1, 4),
	('105', 'Ankazobe', 1, 5),
	('106', 'Anjozorobe', 1, 6),
	('107', 'Manjakandrina', 1, 7),
	('108', 'Andramasina', 1, 8),
	('201', 'Arivonimamo', 2, 9),
	('202', 'Miarinarivo', 2, 10),
	('203', 'Soavinandrina', 2, 11),
	('301', 'Tsiroanomandidy', 3, 12),
	('302', 'Fenoarivobe', 3, 13),
	('401', 'Ambatolampy', 4, 14),
	('402', 'Antanifotsy', 4, 15),
	('403', 'Antsirabe-I', 4, 16),
	('404', 'Antsirabe-II', 4, 17),
	('405', 'Betafo', 4, 18),
	('406', 'Faratsiho', 4, 19),
	('407', 'Mandoto', 4, 20),
	('601', 'Ambalavao', 6, 21),
	('602', 'Ambohimahasoa', 6, 22),
	('603', 'Fianarantsoa-I', 6, 23),
	('604', 'Ikalamavony', 6, 24),
	('605', 'Isandra', 6, 25),
	('606', 'Lalangina', 6, 26),
	('607', 'Vohibato', 6, 27),
	('501', 'Ambatofinandrahana', 5, 28),
	('502', 'Ambositra', 5, 29),
	('503', 'Fandriana', 5, 30),
	('504', 'Manandriana', 5, 31),
	('701', 'Befotaka', 7, 32),
	('702', 'Farafangana', 7, 33),
	('703', 'Midongy-Atsimo', 7, 34),
	('704', 'Vangaindrano', 7, 35),
	('705', 'Vondrozo', 7, 36),
	('801', 'Ifanadiana', 8, 37),
	('802', 'Ikongo', 8, 38),
	('803', 'Manakara-Atsimo', 8, 39),
	('804', 'Mananjary', 8, 40),
	('805', 'Nosy-Varika', 8, 41),
	('806', 'Vohipeno', 8, 42),
	('1301', 'Ambanja', 13, 43),
	('1302', 'Ambilobe', 13, 44),
	('1303', 'Antsiranana-I', 13, 45),
	('1304', 'Antsiranana-II', 13, 46),
	('1305', 'Nosy-Be', 13, 47),
	('1401', 'Andapa', 14, 48),
	('1402', 'Antalaha', 14, 49),
	('1403', 'Sambava', 14, 50);
/*!40000 ALTER TABLE `district` ENABLE KEYS */;

-- Export de la structure de la table population_db. eclairage
DROP TABLE IF EXISTS `eclairage`;
CREATE TABLE IF NOT EXISTS `eclairage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_type_eclairage` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_eclairage_menage` (`id_menage`),
  KEY `FK_eclairage_type_eclairage` (`id_type_eclairage`),
  CONSTRAINT `FK_eclairage_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_eclairage_type_eclairage` FOREIGN KEY (`id_type_eclairage`) REFERENCES `type_eclairage` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.eclairage : ~0 rows (environ)
/*!40000 ALTER TABLE `eclairage` DISABLE KEYS */;
/*!40000 ALTER TABLE `eclairage` ENABLE KEYS */;

-- Export de la structure de la table population_db. elevage
DROP TABLE IF EXISTS `elevage`;
CREATE TABLE IF NOT EXISTS `elevage` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_type_elevage` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_elevage_type_elevage` (`id_type_elevage`),
  KEY `FK_elevage_menage` (`id_menage`),
  CONSTRAINT `FK_elevage_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_elevage_type_elevage` FOREIGN KEY (`id_type_elevage`) REFERENCES `type_elevage` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.elevage : ~0 rows (environ)
/*!40000 ALTER TABLE `elevage` DISABLE KEYS */;
/*!40000 ALTER TABLE `elevage` ENABLE KEYS */;

-- Export de la structure de la table population_db. engagement_activite
DROP TABLE IF EXISTS `engagement_activite`;
CREATE TABLE IF NOT EXISTS `engagement_activite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_type_engagement_activite` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_engagement_activite_type_engagement_activite` (`id_type_engagement_activite`),
  KEY `FK_engagement_activite_menage` (`id_menage`),
  CONSTRAINT `FK_engagement_activite_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_engagement_activite_type_engagement_activite` FOREIGN KEY (`id_type_engagement_activite`) REFERENCES `type_engagement_activite` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.engagement_activite : ~0 rows (environ)
/*!40000 ALTER TABLE `engagement_activite` DISABLE KEYS */;
/*!40000 ALTER TABLE `engagement_activite` ENABLE KEYS */;

-- Export de la structure de la table population_db. enquete_individu
DROP TABLE IF EXISTS `enquete_individu`;
CREATE TABLE IF NOT EXISTS `enquete_individu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_individu` int(11) DEFAULT NULL,
  `id_lien_de_parente` int(11) DEFAULT NULL,
  `id_handicap_visuel` int(11) DEFAULT NULL,
  `id_handicap_parole` int(11) DEFAULT NULL,
  `id_handicap_auditif` int(11) DEFAULT NULL,
  `id_handicap_mental` int(11) DEFAULT NULL,
  `id_handicap_moteur` int(11) DEFAULT NULL,
  `id_type_ecole` int(11) DEFAULT NULL,
  `id_niveau_de_classe` int(11) DEFAULT NULL,
  `langue` varchar(255) DEFAULT '',
  `id_groupe_appartenance` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_enquete_individu_individu` (`id_individu`),
  KEY `FK_enquete_individu_liendeparente` (`id_lien_de_parente`),
  KEY `FK_enquete_individu_handicap_visuel` (`id_handicap_visuel`),
  KEY `FK_enquete_individu_handicap_parole` (`id_handicap_parole`),
  KEY `FK_enquete_individu_handicap_auditif` (`id_handicap_auditif`),
  KEY `FK_enquete_individu_handicap_mental` (`id_handicap_mental`),
  KEY `FK_enquete_individu_handicap_moteur` (`id_handicap_moteur`),
  KEY `FK_enquete_individu_type_ecole` (`id_type_ecole`),
  KEY `FK_enquete_individu_niveau_de_classe` (`id_niveau_de_classe`),
  CONSTRAINT `FK_enquete_individu_handicap_auditif` FOREIGN KEY (`id_handicap_auditif`) REFERENCES `handicap_auditif` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_enquete_individu_handicap_mental` FOREIGN KEY (`id_handicap_mental`) REFERENCES `handicap_mental` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_enquete_individu_handicap_moteur` FOREIGN KEY (`id_handicap_moteur`) REFERENCES `handicap_moteur` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_enquete_individu_handicap_parole` FOREIGN KEY (`id_handicap_parole`) REFERENCES `handicap_parole` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_enquete_individu_handicap_visuel` FOREIGN KEY (`id_handicap_visuel`) REFERENCES `handicap_visuel` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_enquete_individu_individu` FOREIGN KEY (`id_individu`) REFERENCES `individu` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_enquete_individu_liendeparente` FOREIGN KEY (`id_lien_de_parente`) REFERENCES `liendeparente` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_enquete_individu_niveau_de_classe` FOREIGN KEY (`id_niveau_de_classe`) REFERENCES `niveau_de_classe` (`id`),
  CONSTRAINT `FK_enquete_individu_type_ecole` FOREIGN KEY (`id_type_ecole`) REFERENCES `type_ecole` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.enquete_individu : ~0 rows (environ)
/*!40000 ALTER TABLE `enquete_individu` DISABLE KEYS */;
INSERT INTO `enquete_individu` (`id`, `id_individu`, `id_lien_de_parente`, `id_handicap_visuel`, `id_handicap_parole`, `id_handicap_auditif`, `id_handicap_mental`, `id_handicap_moteur`, `id_type_ecole`, `id_niveau_de_classe`, `langue`, `id_groupe_appartenance`) VALUES
	(1, 1, 1, 1, 1, 2, 2, 2, 1, 6, 'a:2:{i:0;s:1:"1";i:1;s:1:"2";}', 2);
/*!40000 ALTER TABLE `enquete_individu` ENABLE KEYS */;

-- Export de la structure de la table population_db. enquete_menage
DROP TABLE IF EXISTS `enquete_menage`;
CREATE TABLE IF NOT EXISTS `enquete_menage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_type_logement` int(11) DEFAULT NULL,
  `id_occupation_logement` int(11) DEFAULT NULL,
  `revetement_toit` longtext,
  `revetement_sol` varchar(255) DEFAULT '',
  `revetement_mur` varchar(255) DEFAULT '',
  `source_eclairage` varchar(255) DEFAULT '',
  `combustible` varchar(255) DEFAULT '',
  `toilette` varchar(255) DEFAULT '',
  `source_eau` varchar(255) DEFAULT '',
  `bien_equipement` varchar(255) DEFAULT '',
  `moyen_production` varchar(255) DEFAULT '',
  `source_revenu` varchar(255) DEFAULT '',
  `elevage` varchar(255) DEFAULT '',
  `culture` varchar(255) DEFAULT '',
  `aliment` varchar(255) DEFAULT '',
  `source_aliment` varchar(255) DEFAULT '',
  `probleme_sur_revenu` varchar(255) DEFAULT '',
  `strategie_sur_revenu` varchar(255) DEFAULT '',
  `activite_recours` varchar(255) DEFAULT '',
  `service_beneficie` varchar(255) DEFAULT '',
  `infrastructure_frequente` longtext,
  `strategie_alimentaire` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `FK_enquete_menage_menage` (`id_menage`),
  KEY `FK_enquete_menage_type_logement` (`id_type_logement`),
  KEY `FK_enquete_menage_occupation_logement` (`id_occupation_logement`),
  CONSTRAINT `FK_enquete_menage_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_enquete_menage_occupation_logement` FOREIGN KEY (`id_occupation_logement`) REFERENCES `occupation_logement` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_enquete_menage_type_logement` FOREIGN KEY (`id_type_logement`) REFERENCES `type_logement` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.enquete_menage : ~0 rows (environ)
/*!40000 ALTER TABLE `enquete_menage` DISABLE KEYS */;
INSERT INTO `enquete_menage` (`id`, `id_menage`, `id_type_logement`, `id_occupation_logement`, `revetement_toit`, `revetement_sol`, `revetement_mur`, `source_eclairage`, `combustible`, `toilette`, `source_eau`, `bien_equipement`, `moyen_production`, `source_revenu`, `elevage`, `culture`, `aliment`, `source_aliment`, `probleme_sur_revenu`, `strategie_sur_revenu`, `activite_recours`, `service_beneficie`, `infrastructure_frequente`, `strategie_alimentaire`) VALUES
	(1, 1, 7, 4, 'a:2:{i:0;s:1:"3";i:1;s:1:"1";}', 'a:3:{i:0;s:1:"6";i:1;s:1:"5";i:2;s:1:"7";}', 'a:2:{i:0;s:1:"5";i:1;s:1:"7";}', 'a:3:{i:0;s:1:"6";i:1;s:1:"9";i:2;s:1:"5";}', 'a:2:{i:0;s:1:"3";i:1;s:1:"1";}', 'a:2:{i:0;s:1:"1";i:1;s:1:"5";}', 'a:2:{i:0;s:1:"8";i:1;s:1:"6";}', 'a:3:{i:0;s:1:"1";i:1;s:1:"8";i:2;s:1:"3";}', 'a:3:{i:0;s:1:"4";i:1;s:2:"11";i:2;s:1:"7";}', 'a:2:{i:0;s:1:"1";i:1;s:1:"2";}', 'a:2:{i:0;s:1:"2";i:1;s:1:"1";}', 'a:3:{i:0;s:1:"1";i:1;s:1:"5";i:2;s:1:"2";}', 'a:3:{i:0;s:1:"5";i:1;s:1:"2";i:2;s:1:"1";}', 'a:2:{i:0;s:1:"1";i:1;s:1:"2";}', 'a:2:{i:0;s:1:"4";i:1;s:1:"1";}', 'a:3:{i:0;s:1:"3";i:1;s:1:"1";i:2;s:1:"4";}', 'a:2:{i:0;s:1:"2";i:1;s:1:"5";}', 'a:1:{i:0;s:1:"2";}', 'a:1:{i:0;s:1:"2";}', 'a:3:{i:0;s:1:"1";i:1;s:1:"3";i:2;s:1:"2";}');
/*!40000 ALTER TABLE `enquete_menage` ENABLE KEYS */;

-- Export de la structure de la table population_db. financement_intervention
DROP TABLE IF EXISTS `financement_intervention`;
CREATE TABLE IF NOT EXISTS `financement_intervention` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_intervention` int(11) DEFAULT NULL,
  `id_source_financement` int(11) DEFAULT NULL,
  `id_action_strategique` int(11) DEFAULT NULL,
  `id_devise` int(11) DEFAULT NULL,
  `id_type_secteur` int(11) DEFAULT NULL,
  `budget_initial` double DEFAULT NULL,
  `budget_modifie` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_financement_intervention_intervention` (`id_intervention`),
  KEY `FK_financement_intervention_source_financement` (`id_source_financement`),
  KEY `FK_financement_intervention_action_strategique` (`id_action_strategique`),
  KEY `FK_financement_intervention_devise` (`id_devise`),
  KEY `FK_financement_intervention_type_secteur` (`id_type_secteur`),
  CONSTRAINT `FK_financement_intervention_action_strategique` FOREIGN KEY (`id_action_strategique`) REFERENCES `action_strategique` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_financement_intervention_devise` FOREIGN KEY (`id_devise`) REFERENCES `devise` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_financement_intervention_intervention` FOREIGN KEY (`id_intervention`) REFERENCES `intervention` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_financement_intervention_source_financement` FOREIGN KEY (`id_source_financement`) REFERENCES `source_financement` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_financement_intervention_type_secteur` FOREIGN KEY (`id_type_secteur`) REFERENCES `type_secteur` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.financement_intervention : ~0 rows (environ)
/*!40000 ALTER TABLE `financement_intervention` DISABLE KEYS */;
INSERT INTO `financement_intervention` (`id`, `id_intervention`, `id_source_financement`, `id_action_strategique`, `id_devise`, `id_type_secteur`, `budget_initial`, `budget_modifie`) VALUES
	(1, 1, 2, 3, 1, 2, 125000, 230000);
/*!40000 ALTER TABLE `financement_intervention` ENABLE KEYS */;

-- Export de la structure de la table population_db. financement_programme
DROP TABLE IF EXISTS `financement_programme`;
CREATE TABLE IF NOT EXISTS `financement_programme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_programme` int(11) DEFAULT NULL,
  `id_source_financement` int(11) DEFAULT NULL,
  `id_axe_strategique` int(11) DEFAULT NULL,
  `id_devise` int(11) DEFAULT NULL,
  `budget_initial` double DEFAULT NULL,
  `budget_modifie` double DEFAULT NULL,
  `id_type_financement` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_financement_programme_programme` (`id_programme`),
  KEY `FK_financement_programme_source_financement` (`id_source_financement`),
  KEY `FK_financement_programme_axe_strategique` (`id_axe_strategique`),
  KEY `FK_financement_programme_devise` (`id_devise`),
  KEY `FK_financement_programme_type_financement` (`id_type_financement`),
  CONSTRAINT `FK_financement_programme_axe_strategique` FOREIGN KEY (`id_axe_strategique`) REFERENCES `axe_strategique` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_financement_programme_devise` FOREIGN KEY (`id_devise`) REFERENCES `devise` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_financement_programme_programme` FOREIGN KEY (`id_programme`) REFERENCES `programme` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_financement_programme_source_financement` FOREIGN KEY (`id_source_financement`) REFERENCES `source_financement` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_financement_programme_type_financement` FOREIGN KEY (`id_type_financement`) REFERENCES `type_financement` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.financement_programme : ~2 rows (environ)
/*!40000 ALTER TABLE `financement_programme` DISABLE KEYS */;
INSERT INTO `financement_programme` (`id`, `id_programme`, `id_source_financement`, `id_axe_strategique`, `id_devise`, `budget_initial`, `budget_modifie`, `id_type_financement`) VALUES
	(1, 1, 1, 2, 2, 15000000, 23500000, NULL),
	(2, 1, 1, 2, 2, 14500000, 75800000, NULL);
/*!40000 ALTER TABLE `financement_programme` ENABLE KEYS */;

-- Export de la structure de la table population_db. fokontany
DROP TABLE IF EXISTS `fokontany`;
CREATE TABLE IF NOT EXISTS `fokontany` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` longtext NOT NULL,
  `nom` longtext NOT NULL,
  `id_commune` int(11) NOT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fokontany_commune_id_idx` (`id_commune`),
  CONSTRAINT `FK_fokontany_commune` FOREIGN KEY (`id_commune`) REFERENCES `commune` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.fokontany : ~50 rows (environ)
/*!40000 ALTER TABLE `fokontany` DISABLE KEYS */;
INSERT INTO `fokontany` (`id`, `code`, `nom`, `id_commune`, `latitude`, `longitude`) VALUES
	(1, '110103001', 'Alaotra', 34, NULL, NULL),
	(2, '110111001', 'Ambarinakanga', 42, NULL, NULL),
	(3, '110113001', 'Manakambahiny', 44, NULL, NULL),
	(4, '110119001', 'Antanimenakely', 49, NULL, NULL),
	(5, '110119002', 'Soalazaina', 49, NULL, NULL),
	(6, '110120001', 'Ambatoaranana', 50, NULL, NULL),
	(7, '110120002', 'Ambatompy', 50, NULL, NULL),
	(8, '110120003', 'Andobomanana', 50, NULL, NULL),
	(9, '110120004', 'Ankisatra', 50, NULL, NULL),
	(10, '110120005', 'Anosibeorana', 50, NULL, NULL),
	(11, '110210001', 'Antetezantany', 16, NULL, NULL),
	(12, '110516001', 'Mandialaza', 25, NULL, NULL),
	(13, '50101001', 'Amboropotsy', 37, NULL, NULL),
	(14, '50101002', 'Andraikita', 37, NULL, NULL),
	(15, '50101003', 'Andranovorivato', 37, NULL, NULL),
	(16, '50101004', 'Marosaho', 37, NULL, NULL),
	(17, '50101005', 'Tsimalailoaka', 37, NULL, NULL),
	(18, '50106001', 'Bemaha', 27, NULL, NULL),
	(19, '50105001', 'Ambondromisitra', 47, NULL, NULL),
	(20, '50105002', 'Antsahasoa', 47, NULL, NULL),
	(21, '50105003', 'Magnavotsy', 47, NULL, NULL),
	(22, '50105004', 'Soavina', 47, NULL, NULL),
	(23, '50107001', 'Ambalahady', 3, NULL, NULL),
	(24, '50107002', 'Ambatonosy', 3, NULL, NULL),
	(25, '50107003', 'Ambodiala', 3, NULL, NULL),
	(26, '50107004', 'Andranongisa', 3, NULL, NULL),
	(27, '50107005', 'Isaka', 3, NULL, NULL),
	(28, '50107006', 'Maherisifotra', 3, NULL, NULL),
	(29, '50103001', 'Ambalamahatsara', 10, NULL, NULL),
	(30, '50102001', 'Antsorea', 9, NULL, NULL),
	(31, '50102002', 'Beronono-Fasiana', 9, NULL, NULL),
	(32, '50102003', 'Itremo', 9, NULL, NULL),
	(33, '50108001', 'Akijea', 4, NULL, NULL),
	(34, '50108002', 'Ambalavaomitahy', 4, NULL, NULL),
	(35, '50108003', 'Ankinagna', 4, NULL, NULL),
	(36, '50108004', 'Beravina', 4, NULL, NULL),
	(37, '50108005', 'Fenoarivo', 4, NULL, NULL),
	(38, '50108006', 'Ilempo', 4, NULL, NULL),
	(39, '50108007', 'Ilomba', 4, NULL, NULL),
	(40, '50108008', 'Janjina', 4, NULL, NULL),
	(41, '50108009', 'Manamy', 4, NULL, NULL),
	(42, '50108010', 'Mandrosonoro', 4, NULL, NULL),
	(43, '50108011', 'Maroanakaomby', 4, NULL, NULL),
	(44, '50109001', 'Andolomitahy', 5, NULL, NULL),
	(45, '50109002', 'Mangataboahangy', 5, NULL, NULL),
	(46, '50109003', 'Marolinta', 5, NULL, NULL),
	(47, '50109004', 'Modia', 5, NULL, NULL),
	(48, '50109005', 'Tsiafamakamba', 5, NULL, NULL),
	(49, '50104001', 'Soavina', 1, NULL, NULL),
	(50, '50110001', 'Antsakoazato', 15, NULL, NULL);
/*!40000 ALTER TABLE `fokontany` ENABLE KEYS */;

-- Export de la structure de la table population_db. frequence_transfert
DROP TABLE IF EXISTS `frequence_transfert`;
CREATE TABLE IF NOT EXISTS `frequence_transfert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(5) DEFAULT '',
  `description` varchar(30) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.frequence_transfert : ~7 rows (environ)
/*!40000 ALTER TABLE `frequence_transfert` DISABLE KEYS */;
INSERT INTO `frequence_transfert` (`id`, `code`, `description`) VALUES
	(1, '2', 'Hebdomadaire'),
	(2, '1', 'Journalier'),
	(3, '3', 'Mensuel'),
	(4, '4', 'Bimensuel'),
	(6, '5', 'Trimestriel'),
	(7, '6', 'Un seul transfert'),
	(8, '7', 'Non prÃ©visible/spontanÃ©');
/*!40000 ALTER TABLE `frequence_transfert` ENABLE KEYS */;

-- Export de la structure de la table population_db. groupe_appartenance
DROP TABLE IF EXISTS `groupe_appartenance`;
CREATE TABLE IF NOT EXISTS `groupe_appartenance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(2) DEFAULT '',
  `description` varchar(30) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.groupe_appartenance : ~3 rows (environ)
/*!40000 ALTER TABLE `groupe_appartenance` DISABLE KEYS */;
INSERT INTO `groupe_appartenance` (`id`, `code`, `description`) VALUES
	(1, '03', 'HandicapÃ©'),
	(2, '01', 'Femme'),
	(3, '02', 'Enfant');
/*!40000 ALTER TABLE `groupe_appartenance` ENABLE KEYS */;

-- Export de la structure de la table population_db. handicap_auditif
DROP TABLE IF EXISTS `handicap_auditif`;
CREATE TABLE IF NOT EXISTS `handicap_auditif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) DEFAULT '',
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.handicap_auditif : ~2 rows (environ)
/*!40000 ALTER TABLE `handicap_auditif` DISABLE KEYS */;
INSERT INTO `handicap_auditif` (`id`, `description`, `code`) VALUES
	(1, 'Malentendant', '01'),
	(2, 'Sourd', '02');
/*!40000 ALTER TABLE `handicap_auditif` ENABLE KEYS */;

-- Export de la structure de la table population_db. handicap_mental
DROP TABLE IF EXISTS `handicap_mental`;
CREATE TABLE IF NOT EXISTS `handicap_mental` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.handicap_mental : ~3 rows (environ)
/*!40000 ALTER TABLE `handicap_mental` DISABLE KEYS */;
INSERT INTO `handicap_mental` (`id`, `description`, `code`) VALUES
	(1, 'InsensÃ©', '01'),
	(2, 'AliÃ©nÃ©', '03'),
	(3, 'Fou', '02');
/*!40000 ALTER TABLE `handicap_mental` ENABLE KEYS */;

-- Export de la structure de la table population_db. handicap_moteur
DROP TABLE IF EXISTS `handicap_moteur`;
CREATE TABLE IF NOT EXISTS `handicap_moteur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.handicap_moteur : ~2 rows (environ)
/*!40000 ALTER TABLE `handicap_moteur` DISABLE KEYS */;
INSERT INTO `handicap_moteur` (`id`, `description`, `code`) VALUES
	(1, 'Paralytique', '02'),
	(2, 'Infirme', '01');
/*!40000 ALTER TABLE `handicap_moteur` ENABLE KEYS */;

-- Export de la structure de la table population_db. handicap_parole
DROP TABLE IF EXISTS `handicap_parole`;
CREATE TABLE IF NOT EXISTS `handicap_parole` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.handicap_parole : ~2 rows (environ)
/*!40000 ALTER TABLE `handicap_parole` DISABLE KEYS */;
INSERT INTO `handicap_parole` (`id`, `description`, `code`) VALUES
	(1, 'BÃ¨gue', '01'),
	(2, 'Muet', '02');
/*!40000 ALTER TABLE `handicap_parole` ENABLE KEYS */;

-- Export de la structure de la table population_db. handicap_visuel
DROP TABLE IF EXISTS `handicap_visuel`;
CREATE TABLE IF NOT EXISTS `handicap_visuel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.handicap_visuel : ~2 rows (environ)
/*!40000 ALTER TABLE `handicap_visuel` DISABLE KEYS */;
INSERT INTO `handicap_visuel` (`id`, `description`, `code`) VALUES
	(1, 'Malvoyant', '01'),
	(2, 'Aveugle', '02');
/*!40000 ALTER TABLE `handicap_visuel` ENABLE KEYS */;

-- Export de la structure de la table population_db. historique_utilisateur
DROP TABLE IF EXISTS `historique_utilisateur`;
CREATE TABLE IF NOT EXISTS `historique_utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) DEFAULT NULL,
  `date_action` timestamp NULL DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.historique_utilisateur : ~0 rows (environ)
/*!40000 ALTER TABLE `historique_utilisateur` DISABLE KEYS */;
/*!40000 ALTER TABLE `historique_utilisateur` ENABLE KEYS */;

-- Export de la structure de la table population_db. individu
DROP TABLE IF EXISTS `individu`;
CREATE TABLE IF NOT EXISTS `individu` (
  `id_menage` int(11) DEFAULT NULL,
  `identifiant_unique` varchar(60) DEFAULT '',
  `nom` varchar(50) DEFAULT '',
  `prenom` varchar(50) DEFAULT '',
  `cin` varchar(12) DEFAULT '',
  `date_naissance` date DEFAULT NULL,
  `sexe` varchar(1) DEFAULT '',
  `id_handicap_visuel` int(11) DEFAULT NULL,
  `id_handicap_parole` int(11) DEFAULT NULL,
  `id_handicap_auditif` int(11) DEFAULT NULL,
  `id_handicap_mental` int(11) DEFAULT NULL,
  `id_handicap_moteur` int(11) DEFAULT NULL,
  `id_type_ecole` int(11) DEFAULT NULL,
  `id_niveau_de_classe` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identifiant_appariement` varchar(60) DEFAULT '',
  `date_enregistrement` date DEFAULT NULL,
  `numero_ordre` smallint(6) DEFAULT NULL,
  `numero_ordre_pere` smallint(6) DEFAULT NULL,
  `numero_ordre_mere` smallint(6) DEFAULT NULL,
  `inscription_etatcivil` varchar(15) DEFAULT '',
  `possede_cin` longtext,
  `numero_extrait_naissance` smallint(6) DEFAULT NULL,
  `id_groupe_appartenance` int(11) DEFAULT NULL,
  `frequente_ecole` varchar(15) DEFAULT '',
  `avait_frequente_ecole` varchar(15) DEFAULT '',
  `occupation` varchar(50) DEFAULT '',
  `statut` smallint(6) DEFAULT NULL,
  `date_sortie` date DEFAULT NULL,
  `commentaire` varchar(60) DEFAULT '',
  `nom_ecole` varchar(50) DEFAULT '',
  `flag_integration_donnees` smallint(6) DEFAULT NULL,
  `nouvelle_integration` bit(1) DEFAULT NULL,
  `id_liendeparente` int(11) DEFAULT NULL,
  `id_situation_matrimoniale` int(11) DEFAULT NULL,
  `id_acteur` int(11) DEFAULT NULL,
  `langue` varchar(150) DEFAULT '',
  `decede` smallint(6) DEFAULT '0',
  `date_deces` date DEFAULT NULL,
  `chef_menage` varchar(3) DEFAULT NULL,
  `handicap_visuel` varchar(3) DEFAULT NULL,
  `handicap_parole` varchar(3) DEFAULT NULL,
  `handicap_auditif` varchar(3) DEFAULT NULL,
  `handicap_moteur` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_individu_menage` (`id_menage`),
  KEY `FK_individu_handicap_visuel` (`id_handicap_visuel`),
  KEY `FK_individu_handicap_parole` (`id_handicap_parole`),
  KEY `FK_individu_handicap_auditif` (`id_handicap_auditif`),
  KEY `FK_individu_handicap_mental` (`id_handicap_mental`),
  KEY `FK_individu_handicap_moteur` (`id_handicap_moteur`),
  KEY `FK_individu_type_ecole` (`id_type_ecole`),
  KEY `FK_individu_niveau_de_classe` (`id_niveau_de_classe`),
  KEY `FK_individu_situation_matrimoniale` (`id_situation_matrimoniale`),
  KEY `FK_individu_groupe_appartenance` (`id_groupe_appartenance`),
  KEY `FK_individu_acteur` (`id_acteur`),
  CONSTRAINT `FK_individu_acteur` FOREIGN KEY (`id_acteur`) REFERENCES `acteur` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_individu_groupe_appartenance` FOREIGN KEY (`id_groupe_appartenance`) REFERENCES `groupe_appartenance` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_individu_handicap_auditif` FOREIGN KEY (`id_handicap_auditif`) REFERENCES `handicap_auditif` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_individu_handicap_mental` FOREIGN KEY (`id_handicap_mental`) REFERENCES `handicap_mental` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_individu_handicap_moteur` FOREIGN KEY (`id_handicap_moteur`) REFERENCES `handicap_moteur` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_individu_handicap_parole` FOREIGN KEY (`id_handicap_parole`) REFERENCES `handicap_parole` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_individu_handicap_visuel` FOREIGN KEY (`id_handicap_visuel`) REFERENCES `handicap_visuel` (`id`),
  CONSTRAINT `FK_individu_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_individu_niveau_de_classe` FOREIGN KEY (`id_niveau_de_classe`) REFERENCES `niveau_de_classe` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_individu_situation_matrimoniale` FOREIGN KEY (`id_situation_matrimoniale`) REFERENCES `situation_matrimoniale` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_individu_type_ecole` FOREIGN KEY (`id_type_ecole`) REFERENCES `type_ecole` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.individu : ~1 rows (environ)
/*!40000 ALTER TABLE `individu` DISABLE KEYS */;
INSERT INTO `individu` (`id_menage`, `identifiant_unique`, `nom`, `prenom`, `cin`, `date_naissance`, `sexe`, `id_handicap_visuel`, `id_handicap_parole`, `id_handicap_auditif`, `id_handicap_mental`, `id_handicap_moteur`, `id_type_ecole`, `id_niveau_de_classe`, `id`, `identifiant_appariement`, `date_enregistrement`, `numero_ordre`, `numero_ordre_pere`, `numero_ordre_mere`, `inscription_etatcivil`, `possede_cin`, `numero_extrait_naissance`, `id_groupe_appartenance`, `frequente_ecole`, `avait_frequente_ecole`, `occupation`, `statut`, `date_sortie`, `commentaire`, `nom_ecole`, `flag_integration_donnees`, `nouvelle_integration`, `id_liendeparente`, `id_situation_matrimoniale`, `id_acteur`, `langue`, `decede`, `date_deces`, `chef_menage`, `handicap_visuel`, `handicap_parole`, `handicap_auditif`, `handicap_moteur`) VALUES
	(1, '12789456', 'KOTO', 'Florent', '101211100789', '2000-03-12', 'H', NULL, NULL, 2, 1, 2, 1, 7, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, b'0', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `individu` ENABLE KEYS */;

-- Export de la structure de la table population_db. individu_beneficiaire
DROP TABLE IF EXISTS `individu_beneficiaire`;
CREATE TABLE IF NOT EXISTS `individu_beneficiaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_individu` int(11) DEFAULT NULL,
  `id_intervention` int(11) DEFAULT NULL,
  `date_sortie` date DEFAULT NULL,
  `date_inscription` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_individu_beneficiaire_individu` (`id_individu`),
  KEY `FK_individu_beneficiaire_intervention` (`id_intervention`),
  CONSTRAINT `FK_individu_beneficiaire_individu` FOREIGN KEY (`id_individu`) REFERENCES `individu` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_individu_beneficiaire_intervention` FOREIGN KEY (`id_intervention`) REFERENCES `intervention` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.individu_beneficiaire : ~1 rows (environ)
/*!40000 ALTER TABLE `individu_beneficiaire` DISABLE KEYS */;
INSERT INTO `individu_beneficiaire` (`id`, `id_individu`, `id_intervention`, `date_sortie`, `date_inscription`) VALUES
	(1, 1, 1, NULL, NULL);
/*!40000 ALTER TABLE `individu_beneficiaire` ENABLE KEYS */;

-- Export de la structure de la table population_db. infrastructure
DROP TABLE IF EXISTS `infrastructure`;
CREATE TABLE IF NOT EXISTS `infrastructure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_type_infrastructure` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_infrastructure_menage` (`id_menage`),
  KEY `FK_infrastructure_type_infrastructure` (`id_type_infrastructure`),
  CONSTRAINT `FK_infrastructure_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_infrastructure_type_infrastructure` FOREIGN KEY (`id_type_infrastructure`) REFERENCES `type_infrastructure` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.infrastructure : ~0 rows (environ)
/*!40000 ALTER TABLE `infrastructure` DISABLE KEYS */;
/*!40000 ALTER TABLE `infrastructure` ENABLE KEYS */;

-- Export de la structure de la table population_db. intervention
DROP TABLE IF EXISTS `intervention`;
CREATE TABLE IF NOT EXISTS `intervention` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identifiant` varchar(25) DEFAULT '',
  `nom_informateur` varchar(50) DEFAULT '',
  `prenom_informateur` varchar(50) DEFAULT '',
  `telephone_informateur` varchar(20) DEFAULT '',
  `email_informateur` varchar(100) DEFAULT '',
  `ministere_tutelle` varchar(100) DEFAULT '',
  `intitule` varchar(150) DEFAULT '',
  `id_acteur` int(11) DEFAULT NULL,
  `categorie_intervention` varchar(30) DEFAULT '',
  `inscription_budgetaire` varchar(50) DEFAULT '',
  `programmation` varchar(20) DEFAULT '',
  `duree` smallint(6) DEFAULT NULL,
  `id_type_transfert` int(11) DEFAULT NULL,
  `montant_transfert` double DEFAULT NULL,
  `nouvelle_integration` bit(1) DEFAULT NULL,
  `commentaire` longtext,
  `id_type_action` int(11) DEFAULT NULL,
  `id_programme` int(11) DEFAULT NULL,
  `unite_duree` varchar(30) DEFAULT '',
  `flag_integration_donnees` bit(1) DEFAULT NULL,
  `id_frequence_transfert` int(11) DEFAULT NULL,
  `id_nomenclature_intervention` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_intervention_acteur` (`id_acteur`),
  KEY `FK_intervention_type_transfert` (`id_type_transfert`),
  KEY `FK_intervention_type_action` (`id_type_action`),
  KEY `FK_intervention_programme` (`id_programme`),
  KEY `FK_intervention_frequence_transfert` (`id_frequence_transfert`),
  KEY `FK_intervention_nomenclature_intervention4` (`id_nomenclature_intervention`),
  CONSTRAINT `FK_intervention_acteur` FOREIGN KEY (`id_acteur`) REFERENCES `acteur` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_intervention_frequence_transfert` FOREIGN KEY (`id_frequence_transfert`) REFERENCES `frequence_transfert` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_intervention_nomenclature_intervention4` FOREIGN KEY (`id_nomenclature_intervention`) REFERENCES `nomenclature_intervention4` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_intervention_programme` FOREIGN KEY (`id_programme`) REFERENCES `programme` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_intervention_type_action` FOREIGN KEY (`id_type_action`) REFERENCES `type_action` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_intervention_type_transfert` FOREIGN KEY (`id_type_transfert`) REFERENCES `type_transfert` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.intervention : ~0 rows (environ)
/*!40000 ALTER TABLE `intervention` DISABLE KEYS */;
INSERT INTO `intervention` (`id`, `identifiant`, `nom_informateur`, `prenom_informateur`, `telephone_informateur`, `email_informateur`, `ministere_tutelle`, `intitule`, `id_acteur`, `categorie_intervention`, `inscription_budgetaire`, `programmation`, `duree`, `id_type_transfert`, `montant_transfert`, `nouvelle_integration`, `commentaire`, `id_type_action`, `id_programme`, `unite_duree`, `flag_integration_donnees`, `id_frequence_transfert`, `id_nomenclature_intervention`) VALUES
	(1, 'dentifiant', 'Nom info', 'prÃ©nom inform', '039 21 345 67', 'mail@dts.mg', 'ministere tutelle', 'intitulÃ© de l\'intervention', 2, 'Service d\'action sociale', 'Hors budget', 'Annuel', 1, 1, 275300, b'0', 'commentaire sur l\'intervention niova', 1, 1, 'AnnÃ©e', b'0', 3, NULL);
/*!40000 ALTER TABLE `intervention` ENABLE KEYS */;

-- Export de la structure de la table population_db. langue
DROP TABLE IF EXISTS `langue`;
CREATE TABLE IF NOT EXISTS `langue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_individu` int(11) DEFAULT NULL,
  `id_liste_langue` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_langue_individu` (`id_individu`),
  KEY `FK_langue_liste_langue` (`id_liste_langue`),
  CONSTRAINT `FK_langue_individu` FOREIGN KEY (`id_individu`) REFERENCES `individu` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_langue_liste_langue` FOREIGN KEY (`id_liste_langue`) REFERENCES `liste_langue` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.langue : ~0 rows (environ)
/*!40000 ALTER TABLE `langue` DISABLE KEYS */;
/*!40000 ALTER TABLE `langue` ENABLE KEYS */;

-- Export de la structure de la table population_db. liendeparente
DROP TABLE IF EXISTS `liendeparente`;
CREATE TABLE IF NOT EXISTS `liendeparente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) DEFAULT '',
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.liendeparente : ~4 rows (environ)
/*!40000 ALTER TABLE `liendeparente` DISABLE KEYS */;
INSERT INTO `liendeparente` (`id`, `description`, `code`) VALUES
	(1, 'Ã‰poux/Ã©pouse', '01'),
	(2, 'PÃ¨re/mÃ¨re', '02'),
	(3, 'Petit-enfant', '04'),
	(5, 'Enfant', '03');
/*!40000 ALTER TABLE `liendeparente` ENABLE KEYS */;

-- Export de la structure de la table population_db. liste_langue
DROP TABLE IF EXISTS `liste_langue`;
CREATE TABLE IF NOT EXISTS `liste_langue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(15) DEFAULT '',
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.liste_langue : ~7 rows (environ)
/*!40000 ALTER TABLE `liste_langue` DISABLE KEYS */;
INSERT INTO `liste_langue` (`id`, `description`, `code`) VALUES
	(1, 'Malagasy', '01'),
	(2, 'FranÃ§ais', '02'),
	(3, 'Anglais', '03'),
	(4, 'Mandarin', '04'),
	(5, 'Espagnol', '05'),
	(6, 'Allemand', '06'),
	(7, 'Italien', '07');
/*!40000 ALTER TABLE `liste_langue` ENABLE KEYS */;

-- Export de la structure de la table population_db. liste_recommandations
DROP TABLE IF EXISTS `liste_recommandations`;
CREATE TABLE IF NOT EXISTS `liste_recommandations` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `resume` varchar(150) DEFAULT '',
  `url` varchar(70) DEFAULT '',
  `validation` smallint(6) DEFAULT '0',
  `utilisateur_id` int(11) DEFAULT NULL,
  `site_id` int(11) DEFAULT NULL,
  `nom_fichier` varchar(255) DEFAULT '',
  `repertoire` varchar(255) DEFAULT '',
  `date_upload` date DEFAULT NULL,
  `fait` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.liste_recommandations : ~0 rows (environ)
/*!40000 ALTER TABLE `liste_recommandations` DISABLE KEYS */;
INSERT INTO `liste_recommandations` (`id`, `resume`, `url`, `validation`, `utilisateur_id`, `site_id`, `nom_fichier`, `repertoire`, `date_upload`, `fait`) VALUES
	(1, 'Erreur lors de l\'insertion', '/ddb/enquete-sur-menage', 0, 1, NULL, 'REGLES_DE_GESTION_POPULATION.docx', 'http://localhost/2019/population/recommandation/', '2019-05-20', 0);
/*!40000 ALTER TABLE `liste_recommandations` ENABLE KEYS */;

-- Export de la structure de la table population_db. liste_validation_beneficiaire
DROP TABLE IF EXISTS `liste_validation_beneficiaire`;
CREATE TABLE IF NOT EXISTS `liste_validation_beneficiaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utiliateur` int(11) DEFAULT NULL,
  `date_reception` datetime DEFAULT NULL,
  `nom_fichier` text,
  `donnees_validees` smallint(6) DEFAULT NULL,
  `date_validation` datetime DEFAULT NULL,
  `id_utilisateur_validation` int(11) DEFAULT NULL,
  `repertoire` text,
  `id_fokontany` int(11) DEFAULT NULL,
  `id_intervention` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_liste_validation_donnees_utilisateur` (`id_utiliateur`),
  KEY `FK_liste_validation_donnees_utilisateur_2` (`id_utilisateur_validation`),
  KEY `FK_liste_validation_beneficiaire_fokontany` (`id_fokontany`),
  KEY `FK_liste_validation_beneficiaire_intervention` (`id_intervention`),
  CONSTRAINT `FK_liste_validation_beneficiaire_fokontany` FOREIGN KEY (`id_fokontany`) REFERENCES `fokontany` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_liste_validation_beneficiaire_intervention` FOREIGN KEY (`id_intervention`) REFERENCES `intervention` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_liste_validation_donnees_utilisateur` FOREIGN KEY (`id_utiliateur`) REFERENCES `utilisateur` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_liste_validation_donnees_utilisateur_2` FOREIGN KEY (`id_utilisateur_validation`) REFERENCES `utilisateur` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.liste_validation_beneficiaire : ~0 rows (environ)
/*!40000 ALTER TABLE `liste_validation_beneficiaire` DISABLE KEYS */;
/*!40000 ALTER TABLE `liste_validation_beneficiaire` ENABLE KEYS */;

-- Export de la structure de la table population_db. liste_validation_intervention
DROP TABLE IF EXISTS `liste_validation_intervention`;
CREATE TABLE IF NOT EXISTS `liste_validation_intervention` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) DEFAULT NULL,
  `date_reception` datetime DEFAULT NULL,
  `nom_fichier` text,
  `donnees_validees` smallint(6) NOT NULL DEFAULT '0',
  `date_validation` datetime DEFAULT NULL,
  `id_utilisateur_validation` int(11) DEFAULT NULL,
  `repertoire` text,
  PRIMARY KEY (`id`),
  KEY `FK_liste_validation_intervention_utilisateur` (`id_utilisateur`),
  KEY `FK_liste_validation_intervention_utilisateur_2` (`id_utilisateur_validation`),
  CONSTRAINT `FK_liste_validation_intervention_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_liste_validation_intervention_utilisateur_2` FOREIGN KEY (`id_utilisateur_validation`) REFERENCES `utilisateur` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.liste_validation_intervention : ~0 rows (environ)
/*!40000 ALTER TABLE `liste_validation_intervention` DISABLE KEYS */;
/*!40000 ALTER TABLE `liste_validation_intervention` ENABLE KEYS */;

-- Export de la structure de la table population_db. liste_variable
DROP TABLE IF EXISTS `liste_variable`;
CREATE TABLE IF NOT EXISTS `liste_variable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `decription` varchar(70) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.liste_variable : ~0 rows (environ)
/*!40000 ALTER TABLE `liste_variable` DISABLE KEYS */;
/*!40000 ALTER TABLE `liste_variable` ENABLE KEYS */;

-- Export de la structure de la table population_db. menage
DROP TABLE IF EXISTS `menage`;
CREATE TABLE IF NOT EXISTS `menage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identifiant_unique` varchar(60) DEFAULT '',
  `nom` varchar(50) DEFAULT '',
  `prenom` varchar(50) DEFAULT '',
  `cin` varchar(12) DEFAULT '',
  `chef_menage` varchar(1) DEFAULT '',
  `adresse` varchar(50) DEFAULT '',
  `date_naissance` date DEFAULT NULL,
  `nombre_beneficiaire` smallint(6) DEFAULT NULL,
  `profession` varchar(50) DEFAULT '',
  `tranche_age` varchar(10) DEFAULT '',
  `id_situation_matrimoniale` int(11) DEFAULT NULL,
  `sexe` varchar(1) DEFAULT '',
  `date_inscription` date DEFAULT NULL,
  `revenu_mensuel` double DEFAULT NULL,
  `depense_mensuel` double DEFAULT NULL,
  `id_fokontany` int(11) DEFAULT NULL,
  `id_type_beneficiaire` int(11) DEFAULT NULL,
  `identifiant_appariement` varchar(80) DEFAULT '',
  `numero_sequentiel` int(11) DEFAULT NULL,
  `lieu_residence` varchar(20) DEFAULT '',
  `surnom_chefmenage` varchar(30) DEFAULT '',
  `nom_prenom_pere` varchar(60) DEFAULT '',
  `nom_prenom_mere` varchar(60) DEFAULT '',
  `telephone` varchar(15) DEFAULT '',
  `statut` smallint(6) DEFAULT '0',
  `date_sortie` date DEFAULT NULL,
  `nom_enqueteur` varchar(60) DEFAULT '',
  `date_enquete` date DEFAULT NULL,
  `nom_superviseur_enquete` varchar(60) DEFAULT '',
  `date_supervision` date DEFAULT NULL,
  `flag_integration_donnees` smallint(6) DEFAULT NULL,
  `nouvelle_integration` bit(1) DEFAULT NULL,
  `commentaire` varchar(60) DEFAULT '',
  `id_acteur` int(11) DEFAULT NULL,
  `etat_groupe` smallint(6) NOT NULL DEFAULT '0' COMMENT '0 : Ménage; 1 : Groupe',
  `decede` smallint(6) DEFAULT '0',
  `date_deces` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_menage_type_beneficiaire` (`id_type_beneficiaire`),
  KEY `FK_menage_situation_matrimoniale` (`id_situation_matrimoniale`),
  KEY `FK_menage_acteur` (`id_acteur`),
  CONSTRAINT `FK_menage_acteur` FOREIGN KEY (`id_acteur`) REFERENCES `acteur` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_menage_situation_matrimoniale` FOREIGN KEY (`id_situation_matrimoniale`) REFERENCES `situation_matrimoniale` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_menage_type_beneficiaire` FOREIGN KEY (`id_type_beneficiaire`) REFERENCES `type_beneficiaire` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.menage : ~1 rows (environ)
/*!40000 ALTER TABLE `menage` DISABLE KEYS */;
INSERT INTO `menage` (`id`, `identifiant_unique`, `nom`, `prenom`, `cin`, `chef_menage`, `adresse`, `date_naissance`, `nombre_beneficiaire`, `profession`, `tranche_age`, `id_situation_matrimoniale`, `sexe`, `date_inscription`, `revenu_mensuel`, `depense_mensuel`, `id_fokontany`, `id_type_beneficiaire`, `identifiant_appariement`, `numero_sequentiel`, `lieu_residence`, `surnom_chefmenage`, `nom_prenom_pere`, `nom_prenom_mere`, `telephone`, `statut`, `date_sortie`, `nom_enqueteur`, `date_enquete`, `nom_superviseur_enquete`, `date_supervision`, `flag_integration_donnees`, `nouvelle_integration`, `commentaire`, `id_acteur`, `etat_groupe`, `decede`, `date_deces`) VALUES
	(1, '23456789', 'RAZAKANDRAINY', 'Jean Salomon', '101211100562', '1', 'Anjoma', '1977-12-30', NULL, 'Pecheur', NULL, 1, 'H', '2018-01-01', 50000, 70000, 1701, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, b'0', NULL, NULL, 0, 0, NULL);
/*!40000 ALTER TABLE `menage` ENABLE KEYS */;

-- Export de la structure de la table population_db. menage_beneficiaire
DROP TABLE IF EXISTS `menage_beneficiaire`;
CREATE TABLE IF NOT EXISTS `menage_beneficiaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_intervention` int(11) DEFAULT NULL,
  `date_sortie` date DEFAULT NULL,
  `date_inscription` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_menage_beneficiaire_menage` (`id_menage`),
  KEY `FK_menage_beneficiaire_intervention` (`id_intervention`),
  CONSTRAINT `FK_menage_beneficiaire_intervention` FOREIGN KEY (`id_intervention`) REFERENCES `intervention` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_menage_beneficiaire_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.menage_beneficiaire : ~1 rows (environ)
/*!40000 ALTER TABLE `menage_beneficiaire` DISABLE KEYS */;
INSERT INTO `menage_beneficiaire` (`id`, `id_menage`, `id_intervention`, `date_sortie`, `date_inscription`) VALUES
	(1, 1, 1, NULL, NULL);
/*!40000 ALTER TABLE `menage_beneficiaire` ENABLE KEYS */;

-- Export de la structure de la table population_db. moyens_production
DROP TABLE IF EXISTS `moyens_production`;
CREATE TABLE IF NOT EXISTS `moyens_production` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_moyen_production` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_moyens_production_menage` (`id_menage`),
  KEY `FK_moyens_production_type_moyen_production` (`id_moyen_production`),
  CONSTRAINT `FK_moyens_production_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_moyens_production_type_moyen_production` FOREIGN KEY (`id_moyen_production`) REFERENCES `type_moyen_production` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.moyens_production : ~0 rows (environ)
/*!40000 ALTER TABLE `moyens_production` DISABLE KEYS */;
/*!40000 ALTER TABLE `moyens_production` ENABLE KEYS */;

-- Export de la structure de la table population_db. niveau_de_classe
DROP TABLE IF EXISTS `niveau_de_classe`;
CREATE TABLE IF NOT EXISTS `niveau_de_classe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.niveau_de_classe : ~7 rows (environ)
/*!40000 ALTER TABLE `niveau_de_classe` DISABLE KEYS */;
INSERT INTO `niveau_de_classe` (`id`, `description`, `code`) VALUES
	(1, 'Aucune', '01'),
	(2, 'Maternelle', '02'),
	(3, 'CP', '03'),
	(4, 'CE1', '04'),
	(5, 'CE2', '05'),
	(6, 'CM1', '06'),
	(7, 'CM2', '07');
/*!40000 ALTER TABLE `niveau_de_classe` ENABLE KEYS */;

-- Export de la structure de la table population_db. nomenclature_intervention1
DROP TABLE IF EXISTS `nomenclature_intervention1`;
CREATE TABLE IF NOT EXISTS `nomenclature_intervention1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.nomenclature_intervention1 : ~0 rows (environ)
/*!40000 ALTER TABLE `nomenclature_intervention1` DISABLE KEYS */;
/*!40000 ALTER TABLE `nomenclature_intervention1` ENABLE KEYS */;

-- Export de la structure de la table population_db. nomenclature_intervention2
DROP TABLE IF EXISTS `nomenclature_intervention2`;
CREATE TABLE IF NOT EXISTS `nomenclature_intervention2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_nomenclature1` int(11) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_nomenclature_intervention2_nomenclature_intervention1` (`id_nomenclature1`),
  CONSTRAINT `FK_nomenclature_intervention2_nomenclature_intervention1` FOREIGN KEY (`id_nomenclature1`) REFERENCES `nomenclature_intervention1` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.nomenclature_intervention2 : ~0 rows (environ)
/*!40000 ALTER TABLE `nomenclature_intervention2` DISABLE KEYS */;
/*!40000 ALTER TABLE `nomenclature_intervention2` ENABLE KEYS */;

-- Export de la structure de la table population_db. nomenclature_intervention3
DROP TABLE IF EXISTS `nomenclature_intervention3`;
CREATE TABLE IF NOT EXISTS `nomenclature_intervention3` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_nomenclature2` int(11) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_nomenclature_intervention3_nomenclature_intervention2` (`id_nomenclature2`),
  CONSTRAINT `FK_nomenclature_intervention3_nomenclature_intervention2` FOREIGN KEY (`id_nomenclature2`) REFERENCES `nomenclature_intervention2` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.nomenclature_intervention3 : ~0 rows (environ)
/*!40000 ALTER TABLE `nomenclature_intervention3` DISABLE KEYS */;
/*!40000 ALTER TABLE `nomenclature_intervention3` ENABLE KEYS */;

-- Export de la structure de la table population_db. nomenclature_intervention4
DROP TABLE IF EXISTS `nomenclature_intervention4`;
CREATE TABLE IF NOT EXISTS `nomenclature_intervention4` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_nomenclature3` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_nomenclature_intervention4_nomenclature_intervention3` (`id_nomenclature3`),
  CONSTRAINT `FK_nomenclature_intervention4_nomenclature_intervention3` FOREIGN KEY (`id_nomenclature3`) REFERENCES `nomenclature_intervention3` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.nomenclature_intervention4 : ~0 rows (environ)
/*!40000 ALTER TABLE `nomenclature_intervention4` DISABLE KEYS */;
/*!40000 ALTER TABLE `nomenclature_intervention4` ENABLE KEYS */;

-- Export de la structure de la table population_db. occupation
DROP TABLE IF EXISTS `occupation`;
CREATE TABLE IF NOT EXISTS `occupation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_type_occupation` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_occupation_menage` (`id_menage`),
  KEY `FK_occupation_type_occupation` (`id_type_occupation`),
  CONSTRAINT `FK_occupation_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_occupation_type_occupation` FOREIGN KEY (`id_type_occupation`) REFERENCES `type_occupation` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.occupation : ~0 rows (environ)
/*!40000 ALTER TABLE `occupation` DISABLE KEYS */;
/*!40000 ALTER TABLE `occupation` ENABLE KEYS */;

-- Export de la structure de la table population_db. occupation_logement
DROP TABLE IF EXISTS `occupation_logement`;
CREATE TABLE IF NOT EXISTS `occupation_logement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.occupation_logement : ~6 rows (environ)
/*!40000 ALTER TABLE `occupation_logement` DISABLE KEYS */;
INSERT INTO `occupation_logement` (`id`, `description`, `code`) VALUES
	(1, 'PropriÃ©taire sans titre', '01'),
	(3, 'PropriÃ©taire avec titre', '02'),
	(4, 'Locataire simple', '03'),
	(6, 'Locataire acheteur', '04'),
	(7, 'LogÃ© par lâ€™employeur', '07'),
	(8, 'LogÃ© gratuitement', '06');
/*!40000 ALTER TABLE `occupation_logement` ENABLE KEYS */;

-- Export de la structure de la table population_db. probleme_revenu
DROP TABLE IF EXISTS `probleme_revenu`;
CREATE TABLE IF NOT EXISTS `probleme_revenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_type_probleme_revenu` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_probleme_revenu_menage` (`id_menage`),
  KEY `FK_probleme_revenu_type_probleme_revenu` (`id_type_probleme_revenu`),
  CONSTRAINT `FK_probleme_revenu_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_probleme_revenu_type_probleme_revenu` FOREIGN KEY (`id_type_probleme_revenu`) REFERENCES `type_probleme_revenu` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.probleme_revenu : ~0 rows (environ)
/*!40000 ALTER TABLE `probleme_revenu` DISABLE KEYS */;
/*!40000 ALTER TABLE `probleme_revenu` ENABLE KEYS */;

-- Export de la structure de la table population_db. programme
DROP TABLE IF EXISTS `programme`;
CREATE TABLE IF NOT EXISTS `programme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT '',
  `prenom` varchar(50) DEFAULT '',
  `telephone` varchar(15) DEFAULT '',
  `email` longtext,
  `situation_intervention` varchar(20) DEFAULT '',
  `id_tutelle` int(11) DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `description` varchar(150) DEFAULT '',
  `flag_integration_donnees` bit(1) DEFAULT NULL,
  `nouvelle_integration` bit(1) DEFAULT NULL,
  `commentaire` varchar(150) DEFAULT '',
  `id_type_action` int(11) DEFAULT NULL,
  `identifiant` varchar(60) DEFAULT '',
  `inscription_budgetaire` varchar(60) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `FK_programme_type_action` (`id_type_action`),
  KEY `FK_programme_tutelle` (`id_tutelle`),
  CONSTRAINT `FK_programme_tutelle` FOREIGN KEY (`id_tutelle`) REFERENCES `tutelle` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_programme_type_action` FOREIGN KEY (`id_type_action`) REFERENCES `type_action` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.programme : ~1 rows (environ)
/*!40000 ALTER TABLE `programme` DISABLE KEYS */;
INSERT INTO `programme` (`id`, `nom`, `prenom`, `telephone`, `email`, `situation_intervention`, `id_tutelle`, `date_fin`, `description`, `flag_integration_donnees`, `nouvelle_integration`, `commentaire`, `id_type_action`, `identifiant`, `inscription_budgetaire`) VALUES
	(1, 'RAKOTO', 'Namelenkafatra', '039 21 456 19', 'aime@dts.mg', 'En-cours', NULL, '2019-10-31', 'description', b'0', b'0', 'commentaire', 1, 'IDENTIFIANT', 'Lois des finances');
/*!40000 ALTER TABLE `programme` ENABLE KEYS */;

-- Export de la structure de la table population_db. rattachement_individu
DROP TABLE IF EXISTS `rattachement_individu`;
CREATE TABLE IF NOT EXISTS `rattachement_individu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_individu` int(11) DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `commentaire` varchar(100) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `FK_rattachement_individu_menage` (`id_menage`),
  KEY `FK_rattachement_individu_individu` (`id_individu`),
  CONSTRAINT `FK_rattachement_individu_individu` FOREIGN KEY (`id_individu`) REFERENCES `individu` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_rattachement_individu_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.rattachement_individu : ~0 rows (environ)
/*!40000 ALTER TABLE `rattachement_individu` DISABLE KEYS */;
/*!40000 ALTER TABLE `rattachement_individu` ENABLE KEYS */;

-- Export de la structure de la table population_db. region
DROP TABLE IF EXISTS `region`;
CREATE TABLE IF NOT EXISTS `region` (
  `code` varchar(10) DEFAULT '',
  `nom` varchar(100) DEFAULT '',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `surface` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.region : ~22 rows (environ)
/*!40000 ALTER TABLE `region` DISABLE KEYS */;
INSERT INTO `region` (`code`, `nom`, `id`, `surface`) VALUES
	('1', 'Analamanga', 1, NULL),
	('2', 'Itasy', 2, NULL),
	('3', 'Bongolava', 3, NULL),
	('4', 'Vakinakaratra', 4, NULL),
	('5', 'Amoron\'i-Mania', 5, NULL),
	('6', 'Haute-Matsiatra', 6, NULL),
	('7', 'Atsimo-antsinanana', 7, NULL),
	('8', 'Vatovavy-Fitovinany', 8, NULL),
	('9', 'Ihorombe', 9, NULL),
	('10', 'Antsinanana', 10, NULL),
	('11', 'Alaotra-Mangoro', 11, NULL),
	('12', 'Analanjirofo', 12, NULL),
	('13', 'Diana', 13, NULL),
	('14', 'Sava', 14, NULL),
	('15', 'Boeny', 15, NULL),
	('16', 'Betsiboka', 16, NULL),
	('17', 'Sofia', 17, NULL),
	('18', 'Melaky', 18, NULL),
	('19', 'Atsimo-andrefana', 19, NULL),
	('20', 'Anosy', 20, NULL),
	('21', 'Androy', 21, NULL),
	('22', 'Menabe', 22, NULL);
/*!40000 ALTER TABLE `region` ENABLE KEYS */;

-- Export de la structure de la table population_db. revetement_mur
DROP TABLE IF EXISTS `revetement_mur`;
CREATE TABLE IF NOT EXISTS `revetement_mur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_type_revetement_mur` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_revetement_mur_menage` (`id_menage`),
  KEY `FK_revetement_mur_type_revetement_mur` (`id_type_revetement_mur`),
  CONSTRAINT `FK_revetement_mur_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_revetement_mur_type_revetement_mur` FOREIGN KEY (`id_type_revetement_mur`) REFERENCES `type_revetement_mur` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.revetement_mur : ~0 rows (environ)
/*!40000 ALTER TABLE `revetement_mur` DISABLE KEYS */;
/*!40000 ALTER TABLE `revetement_mur` ENABLE KEYS */;

-- Export de la structure de la table population_db. revetement_sol
DROP TABLE IF EXISTS `revetement_sol`;
CREATE TABLE IF NOT EXISTS `revetement_sol` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_type_revetement_sol` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_revetement_sol_menage` (`id_menage`),
  KEY `FK_revetement_sol_type_revetement_sol` (`id_type_revetement_sol`),
  CONSTRAINT `FK_revetement_sol_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_revetement_sol_type_revetement_sol` FOREIGN KEY (`id_type_revetement_sol`) REFERENCES `type_revetement_sol` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.revetement_sol : ~0 rows (environ)
/*!40000 ALTER TABLE `revetement_sol` DISABLE KEYS */;
/*!40000 ALTER TABLE `revetement_sol` ENABLE KEYS */;

-- Export de la structure de la table population_db. revetement_toit
DROP TABLE IF EXISTS `revetement_toit`;
CREATE TABLE IF NOT EXISTS `revetement_toit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_type_revetement_toit` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_revetement_toit_menage` (`id_menage`),
  KEY `FK_revetement_toit_type_revetement_toit` (`id_type_revetement_toit`),
  CONSTRAINT `FK_revetement_toit_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`),
  CONSTRAINT `FK_revetement_toit_type_revetement_toit` FOREIGN KEY (`id_type_revetement_toit`) REFERENCES `type_revetement_toit` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.revetement_toit : ~0 rows (environ)
/*!40000 ALTER TABLE `revetement_toit` DISABLE KEYS */;
/*!40000 ALTER TABLE `revetement_toit` ENABLE KEYS */;

-- Export de la structure de la table population_db. secteur_programme
DROP TABLE IF EXISTS `secteur_programme`;
CREATE TABLE IF NOT EXISTS `secteur_programme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_financement_programme` int(11) DEFAULT NULL,
  `id_type_secteur` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_secteur_programme_financement_programme` (`id_financement_programme`),
  KEY `FK_secteur_programme_type_secteur` (`id_type_secteur`),
  CONSTRAINT `FK_secteur_programme_financement_programme` FOREIGN KEY (`id_financement_programme`) REFERENCES `financement_programme` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_secteur_programme_type_secteur` FOREIGN KEY (`id_type_secteur`) REFERENCES `type_secteur` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.secteur_programme : ~0 rows (environ)
/*!40000 ALTER TABLE `secteur_programme` DISABLE KEYS */;
/*!40000 ALTER TABLE `secteur_programme` ENABLE KEYS */;

-- Export de la structure de la table population_db. service_beneficie
DROP TABLE IF EXISTS `service_beneficie`;
CREATE TABLE IF NOT EXISTS `service_beneficie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_type_service_beneficie` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_service_beneficie_menage` (`id_menage`),
  KEY `FK_service_beneficie_type_service_beneficie` (`id_type_service_beneficie`),
  CONSTRAINT `FK_service_beneficie_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_service_beneficie_type_service_beneficie` FOREIGN KEY (`id_type_service_beneficie`) REFERENCES `type_service_beneficie` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.service_beneficie : ~0 rows (environ)
/*!40000 ALTER TABLE `service_beneficie` DISABLE KEYS */;
/*!40000 ALTER TABLE `service_beneficie` ENABLE KEYS */;

-- Export de la structure de la table population_db. situation_matrimoniale
DROP TABLE IF EXISTS `situation_matrimoniale`;
CREATE TABLE IF NOT EXISTS `situation_matrimoniale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(30) DEFAULT '',
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.situation_matrimoniale : ~3 rows (environ)
/*!40000 ALTER TABLE `situation_matrimoniale` DISABLE KEYS */;
INSERT INTO `situation_matrimoniale` (`id`, `description`, `code`) VALUES
	(1, 'CÃ©libataire', '01'),
	(2, 'MariÃ©(e)', '02'),
	(3, 'DivorcÃ©(e)', '03');
/*!40000 ALTER TABLE `situation_matrimoniale` ENABLE KEYS */;

-- Export de la structure de la table population_db. source_eau
DROP TABLE IF EXISTS `source_eau`;
CREATE TABLE IF NOT EXISTS `source_eau` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_type_source_eau` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_source_eau_menage` (`id_menage`),
  KEY `FK_source_eau_type_source_eau` (`id_type_source_eau`),
  CONSTRAINT `FK_source_eau_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_source_eau_type_source_eau` FOREIGN KEY (`id_type_source_eau`) REFERENCES `type_source_eau` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.source_eau : ~0 rows (environ)
/*!40000 ALTER TABLE `source_eau` DISABLE KEYS */;
/*!40000 ALTER TABLE `source_eau` ENABLE KEYS */;

-- Export de la structure de la table population_db. source_financement
DROP TABLE IF EXISTS `source_financement`;
CREATE TABLE IF NOT EXISTS `source_financement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.source_financement : ~2 rows (environ)
/*!40000 ALTER TABLE `source_financement` DISABLE KEYS */;
INSERT INTO `source_financement` (`id`, `nom`) VALUES
	(1, 'BANQUE MONDIALE'),
	(2, 'FID');
/*!40000 ALTER TABLE `source_financement` ENABLE KEYS */;

-- Export de la structure de la table population_db. source_obtention_aliment
DROP TABLE IF EXISTS `source_obtention_aliment`;
CREATE TABLE IF NOT EXISTS `source_obtention_aliment` (
  `id` int(11) NOT NULL,
  `id_menage` int(11) DEFAULT NULL,
  `id_type_source_obtention_aliment` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_source_obtention_aliment_menage` (`id_menage`),
  KEY `FK_source_obtention_aliment_type_source_obtention_aliment` (`id_type_source_obtention_aliment`),
  CONSTRAINT `FK_source_obtention_aliment_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_source_obtention_aliment_type_source_obtention_aliment` FOREIGN KEY (`id_type_source_obtention_aliment`) REFERENCES `type_source_obtention_aliment` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.source_obtention_aliment : ~0 rows (environ)
/*!40000 ALTER TABLE `source_obtention_aliment` DISABLE KEYS */;
/*!40000 ALTER TABLE `source_obtention_aliment` ENABLE KEYS */;

-- Export de la structure de la table population_db. source_revenu
DROP TABLE IF EXISTS `source_revenu`;
CREATE TABLE IF NOT EXISTS `source_revenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_type_source_revenu` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_source_revenu_menage` (`id_menage`),
  KEY `FK_source_revenu_type_source_revenu` (`id_type_source_revenu`),
  CONSTRAINT `FK_source_revenu_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_source_revenu_type_source_revenu` FOREIGN KEY (`id_type_source_revenu`) REFERENCES `type_source_revenu` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.source_revenu : ~0 rows (environ)
/*!40000 ALTER TABLE `source_revenu` DISABLE KEYS */;
/*!40000 ALTER TABLE `source_revenu` ENABLE KEYS */;

-- Export de la structure de la table population_db. strategie_face_probleme
DROP TABLE IF EXISTS `strategie_face_probleme`;
CREATE TABLE IF NOT EXISTS `strategie_face_probleme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_type_strategie_face_probleme` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_strategie_face_probleme_menage` (`id_menage`),
  KEY `FK_strategie_face_probleme_type_strategie_face_probleme` (`id_type_strategie_face_probleme`),
  CONSTRAINT `FK_strategie_face_probleme_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_strategie_face_probleme_type_strategie_face_probleme` FOREIGN KEY (`id_type_strategie_face_probleme`) REFERENCES `type_strategie_face_probleme` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.strategie_face_probleme : ~0 rows (environ)
/*!40000 ALTER TABLE `strategie_face_probleme` DISABLE KEYS */;
/*!40000 ALTER TABLE `strategie_face_probleme` ENABLE KEYS */;

-- Export de la structure de la table population_db. suivi_individu
DROP TABLE IF EXISTS `suivi_individu`;
CREATE TABLE IF NOT EXISTS `suivi_individu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_individu` int(11) DEFAULT NULL,
  `id_suivi_individu_entete` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_suivi_individu_suivi_individu_entete` (`id_suivi_individu_entete`),
  KEY `FK_suivi_individu_individu` (`id_individu`),
  CONSTRAINT `FK_suivi_individu_individu` FOREIGN KEY (`id_individu`) REFERENCES `individu` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_suivi_individu_suivi_individu_entete` FOREIGN KEY (`id_suivi_individu_entete`) REFERENCES `suivi_individu_entete` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.suivi_individu : ~0 rows (environ)
/*!40000 ALTER TABLE `suivi_individu` DISABLE KEYS */;
/*!40000 ALTER TABLE `suivi_individu` ENABLE KEYS */;

-- Export de la structure de la table population_db. suivi_individu_detail_transfert
DROP TABLE IF EXISTS `suivi_individu_detail_transfert`;
CREATE TABLE IF NOT EXISTS `suivi_individu_detail_transfert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_suivi_individu_entete` int(11) DEFAULT NULL,
  `id_detail_type_transfert` int(11) DEFAULT NULL,
  `valeur_quantite` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_suivi_individu_detail_transfert_suivi_individu_entete` (`id_suivi_individu_entete`),
  KEY `FK_suivi_individu_detail_transfert_detail_type_transfert` (`id_detail_type_transfert`),
  CONSTRAINT `FK_suivi_individu_detail_transfert_detail_type_transfert` FOREIGN KEY (`id_detail_type_transfert`) REFERENCES `detail_type_transfert` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_suivi_individu_detail_transfert_suivi_individu_entete` FOREIGN KEY (`id_suivi_individu_entete`) REFERENCES `suivi_individu_entete` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.suivi_individu_detail_transfert : ~0 rows (environ)
/*!40000 ALTER TABLE `suivi_individu_detail_transfert` DISABLE KEYS */;
/*!40000 ALTER TABLE `suivi_individu_detail_transfert` ENABLE KEYS */;

-- Export de la structure de la table population_db. suivi_individu_entete
DROP TABLE IF EXISTS `suivi_individu_entete`;
CREATE TABLE IF NOT EXISTS `suivi_individu_entete` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_intervention` int(11) DEFAULT NULL,
  `id_fokontany` int(11) DEFAULT NULL,
  `observation` varchar(150) DEFAULT NULL,
  `date_suivi` date DEFAULT NULL,
  `id_liste_validation_intervention` int(11) DEFAULT NULL,
  `montant_transfert` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_suivi_individu_entete_intervention` (`id_intervention`),
  KEY `FK_suivi_individu_entete_liste_validation_intervention` (`id_liste_validation_intervention`),
  KEY `FK_suivi_individu_entete_fokontany` (`id_fokontany`),
  CONSTRAINT `FK_suivi_individu_entete_fokontany` FOREIGN KEY (`id_fokontany`) REFERENCES `fokontany` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_suivi_individu_entete_intervention` FOREIGN KEY (`id_intervention`) REFERENCES `intervention` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_suivi_individu_entete_liste_validation_intervention` FOREIGN KEY (`id_liste_validation_intervention`) REFERENCES `liste_validation_intervention` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.suivi_individu_entete : ~0 rows (environ)
/*!40000 ALTER TABLE `suivi_individu_entete` DISABLE KEYS */;
/*!40000 ALTER TABLE `suivi_individu_entete` ENABLE KEYS */;

-- Export de la structure de la table population_db. suivi_menage
DROP TABLE IF EXISTS `suivi_menage`;
CREATE TABLE IF NOT EXISTS `suivi_menage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_suivi_menage_entete` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_suivi_menage_suivi_menage_entete` (`id_suivi_menage_entete`),
  KEY `FK_suivi_menage_menage` (`id_menage`),
  CONSTRAINT `FK_suivi_menage_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_suivi_menage_suivi_menage_entete` FOREIGN KEY (`id_suivi_menage_entete`) REFERENCES `suivi_menage_entete` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.suivi_menage : ~0 rows (environ)
/*!40000 ALTER TABLE `suivi_menage` DISABLE KEYS */;
/*!40000 ALTER TABLE `suivi_menage` ENABLE KEYS */;

-- Export de la structure de la table population_db. suivi_menage_detail_transfert
DROP TABLE IF EXISTS `suivi_menage_detail_transfert`;
CREATE TABLE IF NOT EXISTS `suivi_menage_detail_transfert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_suivi_menage_entete` int(11) DEFAULT NULL,
  `id_detail_type_transfert` int(11) DEFAULT NULL,
  `valeur_quantite` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_suivi_menage_detail_transfert_detail_type_transfert` (`id_detail_type_transfert`),
  KEY `FK_suivi_menage_detail_transfert_suivi_menage_entete` (`id_suivi_menage_entete`),
  CONSTRAINT `FK_suivi_menage_detail_transfert_detail_type_transfert` FOREIGN KEY (`id_detail_type_transfert`) REFERENCES `detail_type_transfert` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_suivi_menage_detail_transfert_suivi_menage_entete` FOREIGN KEY (`id_suivi_menage_entete`) REFERENCES `suivi_menage_entete` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.suivi_menage_detail_transfert : ~0 rows (environ)
/*!40000 ALTER TABLE `suivi_menage_detail_transfert` DISABLE KEYS */;
/*!40000 ALTER TABLE `suivi_menage_detail_transfert` ENABLE KEYS */;

-- Export de la structure de la table population_db. suivi_menage_entete
DROP TABLE IF EXISTS `suivi_menage_entete`;
CREATE TABLE IF NOT EXISTS `suivi_menage_entete` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_intervention` int(11) DEFAULT NULL,
  `id_fokontany` int(11) DEFAULT NULL,
  `observation` varchar(150) DEFAULT NULL,
  `date_suivi` date DEFAULT NULL,
  `id_liste_validation_intervention` int(11) DEFAULT NULL,
  `montant_transfert` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_suivi_menage_entete_intervention` (`id_intervention`),
  KEY `FK_suivi_menage_entete_liste_validation_intervention` (`id_liste_validation_intervention`),
  KEY `FK_suivi_menage_entete_fokontany` (`id_fokontany`),
  CONSTRAINT `FK_suivi_menage_entete_fokontany` FOREIGN KEY (`id_fokontany`) REFERENCES `fokontany` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_suivi_menage_entete_intervention` FOREIGN KEY (`id_intervention`) REFERENCES `intervention` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_suivi_menage_entete_liste_validation_intervention` FOREIGN KEY (`id_liste_validation_intervention`) REFERENCES `liste_validation_intervention` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.suivi_menage_entete : ~0 rows (environ)
/*!40000 ALTER TABLE `suivi_menage_entete` DISABLE KEYS */;
/*!40000 ALTER TABLE `suivi_menage_entete` ENABLE KEYS */;

-- Export de la structure de la table population_db. toilette
DROP TABLE IF EXISTS `toilette`;
CREATE TABLE IF NOT EXISTS `toilette` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menage` int(11) DEFAULT NULL,
  `id_type_toilette` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_toilette_menage` (`id_menage`),
  KEY `FK_toilette_type_toilette` (`id_type_toilette`),
  CONSTRAINT `FK_toilette_menage` FOREIGN KEY (`id_menage`) REFERENCES `menage` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_toilette_type_toilette` FOREIGN KEY (`id_type_toilette`) REFERENCES `type_toilette` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.toilette : ~0 rows (environ)
/*!40000 ALTER TABLE `toilette` DISABLE KEYS */;
/*!40000 ALTER TABLE `toilette` ENABLE KEYS */;

-- Export de la structure de la table population_db. tutelle
DROP TABLE IF EXISTS `tutelle`;
CREATE TABLE IF NOT EXISTS `tutelle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.tutelle : ~0 rows (environ)
/*!40000 ALTER TABLE `tutelle` DISABLE KEYS */;
INSERT INTO `tutelle` (`id`, `nom`) VALUES
	(1, 'MINISTERE DE LA POPULATION');
/*!40000 ALTER TABLE `tutelle` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_acteur
DROP TABLE IF EXISTS `type_acteur`;
CREATE TABLE IF NOT EXISTS `type_acteur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) DEFAULT '',
  `code` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_acteur : ~2 rows (environ)
/*!40000 ALTER TABLE `type_acteur` DISABLE KEYS */;
INSERT INTO `type_acteur` (`id`, `description`, `code`) VALUES
	(1, 'AGEX', NULL),
	(2, 'ONG', NULL);
/*!40000 ALTER TABLE `type_acteur` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_action
DROP TABLE IF EXISTS `type_action`;
CREATE TABLE IF NOT EXISTS `type_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_action : ~0 rows (environ)
/*!40000 ALTER TABLE `type_action` DISABLE KEYS */;
INSERT INTO `type_action` (`id`, `description`) VALUES
	(1, 'Intervention directe');
/*!40000 ALTER TABLE `type_action` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_aliment
DROP TABLE IF EXISTS `type_aliment`;
CREATE TABLE IF NOT EXISTS `type_aliment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) DEFAULT '',
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_aliment : ~4 rows (environ)
/*!40000 ALTER TABLE `type_aliment` DISABLE KEYS */;
INSERT INTO `type_aliment` (`id`, `description`, `code`) VALUES
	(1, 'Riz', '01'),
	(2, 'PÃ¢tes alimentaire, pain/galette et/ou beignets', '02'),
	(4, 'Racines, tubercules', '03'),
	(5, 'LÃ©gumineuse/noix', '04');
/*!40000 ALTER TABLE `type_aliment` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_beneficiaire
DROP TABLE IF EXISTS `type_beneficiaire`;
CREATE TABLE IF NOT EXISTS `type_beneficiaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_beneficiaire : ~2 rows (environ)
/*!40000 ALTER TABLE `type_beneficiaire` DISABLE KEYS */;
INSERT INTO `type_beneficiaire` (`id`, `description`) VALUES
	(1, 'MÃ©nage'),
	(2, 'Association');
/*!40000 ALTER TABLE `type_beneficiaire` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_bien_equipement
DROP TABLE IF EXISTS `type_bien_equipement`;
CREATE TABLE IF NOT EXISTS `type_bien_equipement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_bien_equipement : ~9 rows (environ)
/*!40000 ALTER TABLE `type_bien_equipement` DISABLE KEYS */;
INSERT INTO `type_bien_equipement` (`id`, `description`, `code`) VALUES
	(1, 'Radio/Radiocassette', '07'),
	(3, 'Ordinateur fixe/portable', '01'),
	(5, 'Internet', '02'),
	(6, 'Ventilateur', '03'),
	(7, 'Climatiseur', '04'),
	(8, 'TÃ©lÃ©vision', '05'),
	(9, 'VidÃ©o/VCD/DVD', '06'),
	(10, 'TÃ©lÃ©phone fixe', '09'),
	(11, 'CuisiniÃ¨re moderne', '08');
/*!40000 ALTER TABLE `type_bien_equipement` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_combustible
DROP TABLE IF EXISTS `type_combustible`;
CREATE TABLE IF NOT EXISTS `type_combustible` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_combustible : ~5 rows (environ)
/*!40000 ALTER TABLE `type_combustible` DISABLE KEYS */;
INSERT INTO `type_combustible` (`id`, `description`, `code`) VALUES
	(1, 'Charbon de bois', '01'),
	(3, 'Bois de chauffe', '02'),
	(4, 'Gaz', '03'),
	(5, 'ElectricitÃ©', '04'),
	(6, 'DÃ©chet dâ€™animaux', '05');
/*!40000 ALTER TABLE `type_combustible` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_culture
DROP TABLE IF EXISTS `type_culture`;
CREATE TABLE IF NOT EXISTS `type_culture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_culture : ~4 rows (environ)
/*!40000 ALTER TABLE `type_culture` DISABLE KEYS */;
INSERT INTO `type_culture` (`id`, `description`, `code`) VALUES
	(1, 'Arachide', '01'),
	(2, 'Riz', '03'),
	(3, 'Oignon', '04'),
	(5, 'MaÃ®s', '02');
/*!40000 ALTER TABLE `type_culture` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_difficulte_alimentaire
DROP TABLE IF EXISTS `type_difficulte_alimentaire`;
CREATE TABLE IF NOT EXISTS `type_difficulte_alimentaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_difficulte_alimentaire : ~5 rows (environ)
/*!40000 ALTER TABLE `type_difficulte_alimentaire` DISABLE KEYS */;
INSERT INTO `type_difficulte_alimentaire` (`id`, `description`, `code`) VALUES
	(1, 'Consommer des aliments moins prÃ©fÃ©rÃ©s car moins coÃ»teux', '01'),
	(2, 'Emprunter des aliments ou compter sur lâ€™aide des amis,', '02'),
	(3, 'RÃ©duire la quantitÃ© de nourriture lors de la prÃ©paration des repas', '03'),
	(5, 'RÃ©duire la consommation des adultes/mÃ¨res au profit des enfants', '04'),
	(6, 'RÃ©duire le nombre de repas journaliers', '05');
/*!40000 ALTER TABLE `type_difficulte_alimentaire` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_eclairage
DROP TABLE IF EXISTS `type_eclairage`;
CREATE TABLE IF NOT EXISTS `type_eclairage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_eclairage : ~7 rows (environ)
/*!40000 ALTER TABLE `type_eclairage` DISABLE KEYS */;
INSERT INTO `type_eclairage` (`id`, `description`, `code`) VALUES
	(1, 'ElectricitÃ© (JIRAMA)', '01'),
	(4, 'Groupe Ã©lectrogÃ¨ne', '02'),
	(5, 'Lampe Ã  gaz', '03'),
	(6, 'Lampe tempÃªte', '04'),
	(7, 'Lampe Ã  pÃ©trole artisanale', '05'),
	(8, 'Lampe rechargeable', '06'),
	(9, 'Bougie', '07');
/*!40000 ALTER TABLE `type_eclairage` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_ecole
DROP TABLE IF EXISTS `type_ecole`;
CREATE TABLE IF NOT EXISTS `type_ecole` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(2) DEFAULT '',
  `description` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_ecole : ~2 rows (environ)
/*!40000 ALTER TABLE `type_ecole` DISABLE KEYS */;
INSERT INTO `type_ecole` (`id`, `code`, `description`) VALUES
	(1, '01', 'Ecole publique'),
	(2, '02', 'Ecole privÃ©e');
/*!40000 ALTER TABLE `type_ecole` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_elevage
DROP TABLE IF EXISTS `type_elevage`;
CREATE TABLE IF NOT EXISTS `type_elevage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_elevage : ~4 rows (environ)
/*!40000 ALTER TABLE `type_elevage` DISABLE KEYS */;
INSERT INTO `type_elevage` (`id`, `description`, `code`) VALUES
	(1, 'Bovins (BÅ“ufs, Vaches) hh', '01'),
	(2, 'Ovins (Moutons, brebis, etc.)', '03'),
	(3, 'Volailles (poulet, canard, etc.)', '04'),
	(5, 'Caprins (ChÃ¨vres)', '02');
/*!40000 ALTER TABLE `type_elevage` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_engagement_activite
DROP TABLE IF EXISTS `type_engagement_activite`;
CREATE TABLE IF NOT EXISTS `type_engagement_activite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_engagement_activite : ~4 rows (environ)
/*!40000 ALTER TABLE `type_engagement_activite` DISABLE KEYS */;
INSERT INTO `type_engagement_activite` (`id`, `description`, `code`) VALUES
	(1, 'Envoyer des membres du mÃ©nage en migration de travail', '01'),
	(2, 'Acheter de la nourriture Ã  crÃ©dit ou emprunter des aliments', '03'),
	(3, 'Vendre des biens productifs ou des moyens de transport (matÃ©riels agricoles, machine Ã  coudre, moulin, brouette, vÃ©lo etc.)', '02'),
	(5, 'Emprunt', '04');
/*!40000 ALTER TABLE `type_engagement_activite` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_financement
DROP TABLE IF EXISTS `type_financement`;
CREATE TABLE IF NOT EXISTS `type_financement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `intitule` varchar(20) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_financement : ~2 rows (environ)
/*!40000 ALTER TABLE `type_financement` DISABLE KEYS */;
INSERT INTO `type_financement` (`id`, `intitule`) VALUES
	(1, 'Dons'),
	(2, 'Emprunt');
/*!40000 ALTER TABLE `type_financement` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_infrastructure
DROP TABLE IF EXISTS `type_infrastructure`;
CREATE TABLE IF NOT EXISTS `type_infrastructure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_infrastructure : ~3 rows (environ)
/*!40000 ALTER TABLE `type_infrastructure` DISABLE KEYS */;
INSERT INTO `type_infrastructure` (`id`, `description`, `code`) VALUES
	(1, 'Ecole primaire', '02'),
	(2, 'Dispensaire/poste de santÃ©', '01'),
	(3, 'MaternitÃ©', '03');
/*!40000 ALTER TABLE `type_infrastructure` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_logement
DROP TABLE IF EXISTS `type_logement`;
CREATE TABLE IF NOT EXISTS `type_logement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_logement : ~5 rows (environ)
/*!40000 ALTER TABLE `type_logement` DISABLE KEYS */;
INSERT INTO `type_logement` (`id`, `description`, `code`) VALUES
	(1, 'Case', '01'),
	(2, 'Baraque', '02'),
	(6, 'Maison basse', '03'),
	(7, 'Maisone Ã  Ã©tage', '04'),
	(8, 'Appartement dans un immeuble', '05');
/*!40000 ALTER TABLE `type_logement` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_moyen_production
DROP TABLE IF EXISTS `type_moyen_production`;
CREATE TABLE IF NOT EXISTS `type_moyen_production` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_moyen_production : ~15 rows (environ)
/*!40000 ALTER TABLE `type_moyen_production` DISABLE KEYS */;
INSERT INTO `type_moyen_production` (`id`, `description`, `code`) VALUES
	(1, 'Houe/Charrue/Semoir', '01'),
	(3, 'CalÃ¨che/Charrette', '02'),
	(4, 'Animaux de trait', '03'),
	(5, 'Tracteur', '04'),
	(6, 'Voiture/Camion', '05'),
	(7, 'Mobylette/motocyclette', '06'),
	(8, 'Mobylette/motocyclette', '06'),
	(9, 'Pirogue', '07'),
	(10, 'RÃ©frig/congÃ©lat', '08'),
	(11, 'Machine Ã  coudre', '09'),
	(12, 'Machine de musique', '10'),
	(13, 'Chaises/BÃ¢ches', '11'),
	(14, 'TÃ©lÃ©phone/Fac', '12'),
	(15, 'Photocopieuse', '13'),
	(16, 'Ordinateur/Wifi', '14');
/*!40000 ALTER TABLE `type_moyen_production` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_occupation
DROP TABLE IF EXISTS `type_occupation`;
CREATE TABLE IF NOT EXISTS `type_occupation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(2) DEFAULT '',
  `description` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_occupation : ~0 rows (environ)
/*!40000 ALTER TABLE `type_occupation` DISABLE KEYS */;
/*!40000 ALTER TABLE `type_occupation` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_probleme_revenu
DROP TABLE IF EXISTS `type_probleme_revenu`;
CREATE TABLE IF NOT EXISTS `type_probleme_revenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_probleme_revenu : ~7 rows (environ)
/*!40000 ALTER TABLE `type_probleme_revenu` DISABLE KEYS */;
INSERT INTO `type_probleme_revenu` (`id`, `description`, `code`) VALUES
	(1, 'Le dÃ©cÃ¨s d\'un soutien de famille', '01'),
	(2, 'Incendie', '02'),
	(3, 'Inondation', '03'),
	(4, 'SÃ©cheresse', '07'),
	(6, 'Un Accident grave', '04'),
	(7, 'La perte dâ€™emploi', '05'),
	(8, 'La perte de rÃ©colte', '06');
/*!40000 ALTER TABLE `type_probleme_revenu` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_revetement_mur
DROP TABLE IF EXISTS `type_revetement_mur`;
CREATE TABLE IF NOT EXISTS `type_revetement_mur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_revetement_mur : ~7 rows (environ)
/*!40000 ALTER TABLE `type_revetement_mur` DISABLE KEYS */;
INSERT INTO `type_revetement_mur` (`id`, `description`, `code`) VALUES
	(1, 'Briques en banco', '03'),
	(3, 'Briques en ciment', '01'),
	(4, 'Carreau', '02'),
	(5, 'Bois', '04'),
	(6, 'TÃ´le en mÃ©tal', '05'),
	(7, 'PisÃ©', '06'),
	(8, 'Paille/tige', '07');
/*!40000 ALTER TABLE `type_revetement_mur` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_revetement_sol
DROP TABLE IF EXISTS `type_revetement_sol`;
CREATE TABLE IF NOT EXISTS `type_revetement_sol` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_revetement_sol : ~7 rows (environ)
/*!40000 ALTER TABLE `type_revetement_sol` DISABLE KEYS */;
INSERT INTO `type_revetement_sol` (`id`, `description`, `code`) VALUES
	(1, 'Moquette', '06'),
	(4, 'Ciment', '01'),
	(5, 'Carreau', '02'),
	(6, 'Banco', '03'),
	(7, 'Sable', '04'),
	(8, 'Tapis', '05'),
	(9, 'Bois cirÃ©', '07');
/*!40000 ALTER TABLE `type_revetement_sol` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_revetement_toit
DROP TABLE IF EXISTS `type_revetement_toit`;
CREATE TABLE IF NOT EXISTS `type_revetement_toit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_revetement_toit : ~4 rows (environ)
/*!40000 ALTER TABLE `type_revetement_toit` DISABLE KEYS */;
INSERT INTO `type_revetement_toit` (`id`, `description`, `code`) VALUES
	(1, 'Beton/Ciment', '01'),
	(3, 'Tuile/ardoise', '02'),
	(4, 'Zinc', '03'),
	(5, 'Chaume/paille', '04');
/*!40000 ALTER TABLE `type_revetement_toit` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_secteur
DROP TABLE IF EXISTS `type_secteur`;
CREATE TABLE IF NOT EXISTS `type_secteur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_secteur : ~3 rows (environ)
/*!40000 ALTER TABLE `type_secteur` DISABLE KEYS */;
INSERT INTO `type_secteur` (`id`, `nom`) VALUES
	(1, 'SantÃ©'),
	(2, 'WASH'),
	(3, 'Education');
/*!40000 ALTER TABLE `type_secteur` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_service_beneficie
DROP TABLE IF EXISTS `type_service_beneficie`;
CREATE TABLE IF NOT EXISTS `type_service_beneficie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_service_beneficie : ~3 rows (environ)
/*!40000 ALTER TABLE `type_service_beneficie` DISABLE KEYS */;
INSERT INTO `type_service_beneficie` (`id`, `description`, `code`) VALUES
	(1, 'AUTRES Mutuelles de santÃ©', '01'),
	(2, 'Habitat social', '03'),
	(3, 'Cantines Scolaires (DCaS)', '02');
/*!40000 ALTER TABLE `type_service_beneficie` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_source_eau
DROP TABLE IF EXISTS `type_source_eau`;
CREATE TABLE IF NOT EXISTS `type_source_eau` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_source_eau : ~8 rows (environ)
/*!40000 ALTER TABLE `type_source_eau` DISABLE KEYS */;
INSERT INTO `type_source_eau` (`id`, `description`, `code`) VALUES
	(1, 'Vendeur dâ€™eau', '08'),
	(3, 'Robinet intÃ©rieur', '01'),
	(4, 'Robinet publique', '02'),
	(5, 'Robinet du voisin', '03'),
	(6, 'Puits protÃ©gÃ©', '04'),
	(7, 'Forage motorisÃ©', '05'),
	(8, 'Forage Ã  pompe manuel', '06'),
	(9, 'Service de camion-citerne', '07');
/*!40000 ALTER TABLE `type_source_eau` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_source_obtention_aliment
DROP TABLE IF EXISTS `type_source_obtention_aliment`;
CREATE TABLE IF NOT EXISTS `type_source_obtention_aliment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(2) DEFAULT '',
  `description` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_source_obtention_aliment : ~3 rows (environ)
/*!40000 ALTER TABLE `type_source_obtention_aliment` DISABLE KEYS */;
INSERT INTO `type_source_obtention_aliment` (`id`, `code`, `description`) VALUES
	(1, '01', 'Propre production (vÃ©gÃ©tale, animale)'),
	(2, '02', 'PÃªche/Chasse'),
	(3, '03', 'Cueillette');
/*!40000 ALTER TABLE `type_source_obtention_aliment` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_source_revenu
DROP TABLE IF EXISTS `type_source_revenu`;
CREATE TABLE IF NOT EXISTS `type_source_revenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_source_revenu : ~5 rows (environ)
/*!40000 ALTER TABLE `type_source_revenu` DISABLE KEYS */;
INSERT INTO `type_source_revenu` (`id`, `description`, `code`) VALUES
	(1, 'Revenus de l\'agriculture', '03'),
	(2, 'Revenu de l\'Ã©levage', '04'),
	(4, 'Pension de retraite', '02'),
	(5, 'Salaires', '01'),
	(6, 'Revenus des activitÃ©s non agricolesâ€¦', '05');
/*!40000 ALTER TABLE `type_source_revenu` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_strategie_face_probleme
DROP TABLE IF EXISTS `type_strategie_face_probleme`;
CREATE TABLE IF NOT EXISTS `type_strategie_face_probleme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_strategie_face_probleme : ~4 rows (environ)
/*!40000 ALTER TABLE `type_strategie_face_probleme` DISABLE KEYS */;
INSERT INTO `type_strategie_face_probleme` (`id`, `description`, `code`) VALUES
	(1, 'Aide de l\'Ã©tat', '01'),
	(2, 'Aide de ONG', '02'),
	(3, 'Vente de biens non productif', '03'),
	(4, 'Utilisation de son Ã©pargne', '04');
/*!40000 ALTER TABLE `type_strategie_face_probleme` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_toilette
DROP TABLE IF EXISTS `type_toilette`;
CREATE TABLE IF NOT EXISTS `type_toilette` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `code` varchar(2) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_toilette : ~7 rows (environ)
/*!40000 ALTER TABLE `type_toilette` DISABLE KEYS */;
INSERT INTO `type_toilette` (`id`, `description`, `code`) VALUES
	(1, 'Cuvette/seau', '06'),
	(3, 'Chasse dâ€™eau avec Ã©gout', '01'),
	(4, 'Chasse dâ€™eau avec fosse septique', '02'),
	(5, 'Latrines couvertes', '03'),
	(6, 'Latrines ventilÃ©es amÃ©liorÃ©es', '04'),
	(7, 'Latrines non couvertes', '05'),
	(8, 'Edicule public', '07');
/*!40000 ALTER TABLE `type_toilette` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_transfert
DROP TABLE IF EXISTS `type_transfert`;
CREATE TABLE IF NOT EXISTS `type_transfert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) DEFAULT '',
  `code` varchar(5) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_transfert : ~4 rows (environ)
/*!40000 ALTER TABLE `type_transfert` DISABLE KEYS */;
INSERT INTO `type_transfert` (`id`, `description`, `code`) VALUES
	(1, 'Nature', '2'),
	(2, 'MonÃ©taire', '1'),
	(3, 'Service', '3'),
	(6, 'Autres', '4');
/*!40000 ALTER TABLE `type_transfert` ENABLE KEYS */;

-- Export de la structure de la table population_db. type_usage_service_medical
DROP TABLE IF EXISTS `type_usage_service_medical`;
CREATE TABLE IF NOT EXISTS `type_usage_service_medical` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(2) DEFAULT '',
  `description` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.type_usage_service_medical : ~0 rows (environ)
/*!40000 ALTER TABLE `type_usage_service_medical` DISABLE KEYS */;
/*!40000 ALTER TABLE `type_usage_service_medical` ENABLE KEYS */;

-- Export de la structure de la table population_db. unite_mesure
DROP TABLE IF EXISTS `unite_mesure`;
CREATE TABLE IF NOT EXISTS `unite_mesure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(20) DEFAULT '',
  `code` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.unite_mesure : ~5 rows (environ)
/*!40000 ALTER TABLE `unite_mesure` DISABLE KEYS */;
INSERT INTO `unite_mesure` (`id`, `description`, `code`) VALUES
	(1, 'Ariary', NULL),
	(2, 'Litre', NULL),
	(3, 'Kg', NULL),
	(4, 'UnitÃ©', NULL),
	(5, 'Sachet', NULL);
/*!40000 ALTER TABLE `unite_mesure` ENABLE KEYS */;

-- Export de la structure de la table population_db. usage_service_medical
DROP TABLE IF EXISTS `usage_service_medical`;
CREATE TABLE IF NOT EXISTS `usage_service_medical` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_individu` int(11) DEFAULT NULL,
  `id_type_usage_service_medical` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_usage_service_medical_individu` (`id_individu`),
  KEY `FK_usage_service_medical_type_usage_service_medical` (`id_type_usage_service_medical`),
  CONSTRAINT `FK_usage_service_medical_individu` FOREIGN KEY (`id_individu`) REFERENCES `individu` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_usage_service_medical_type_usage_service_medical` FOREIGN KEY (`id_type_usage_service_medical`) REFERENCES `type_usage_service_medical` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.usage_service_medical : ~0 rows (environ)
/*!40000 ALTER TABLE `usage_service_medical` DISABLE KEYS */;
/*!40000 ALTER TABLE `usage_service_medical` ENABLE KEYS */;

-- Export de la structure de la table population_db. utilisateur
DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) DEFAULT '',
  `prenom` varchar(255) DEFAULT '',
  `email` varchar(255) DEFAULT '',
  `password` varchar(255) DEFAULT '',
  `date_creation` datetime DEFAULT NULL,
  `date_modification` datetime DEFAULT NULL,
  `enabled` smallint(6) DEFAULT NULL,
  `token` longtext,
  `roles` longtext,
  `id_region` int(11) DEFAULT NULL,
  `id_district` int(11) DEFAULT NULL,
  `id_commune` int(11) DEFAULT NULL,
  `id_fokontany` int(11) DEFAULT NULL,
  `id_intervention` int(11) DEFAULT NULL,
  `default_password` smallint(6) NOT NULL DEFAULT '0',
  `piece_identite` varchar(12) DEFAULT NULL,
  `adresse` varchar(100) DEFAULT NULL,
  `fonction` varchar(60) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `raison_sociale` varchar(60) DEFAULT NULL,
  `adresse_hote` varchar(60) DEFAULT NULL,
  `nom_responsable` varchar(60) DEFAULT NULL,
  `fonction_responsable` varchar(60) DEFAULT NULL,
  `email_hote` varchar(255) DEFAULT NULL,
  `telephone_hote` varchar(20) DEFAULT NULL,
  `description_hote` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.utilisateur : ~1 rows (environ)
/*!40000 ALTER TABLE `utilisateur` DISABLE KEYS */;
INSERT INTO `utilisateur` (`id`, `nom`, `prenom`, `email`, `password`, `date_creation`, `date_modification`, `enabled`, `token`, `roles`, `id_region`, `id_district`, `id_commune`, `id_fokontany`, `id_intervention`, `default_password`, `piece_identite`, `adresse`, `fonction`, `telephone`, `raison_sociale`, `adresse_hote`, `nom_responsable`, `fonction_responsable`, `email_hote`, `telephone_hote`, `description_hote`) VALUES
	(1, 'RAJAONARISOA', 'Harizo', 'rajaonarisoazo@gmail.com', '09b4af440c5e17e02e5d0a2618aed25eca4b0332', '2019-01-07 11:35:05', '2019-01-07 11:35:05', 1, '0b005f721aa1042e2a3e97f6232f95f88b8bfc8b8026273c2f89383d9be74db1', 'a:6:{i:0;s:4:"USER";i:1;s:3:"DDB";i:2;s:5:"ADMIN";i:3;s:3:"MGS";i:4;s:3:"SSI";i:5;s:3:"VLD";}', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `utilisateur` ENABLE KEYS */;

-- Export de la structure de la table population_db. variable
DROP TABLE IF EXISTS `variable`;
CREATE TABLE IF NOT EXISTS `variable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_liste_variable` int(11) DEFAULT NULL,
  `description` varchar(70) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_variable_liste_variable` (`id_liste_variable`),
  CONSTRAINT `FK_variable_liste_variable` FOREIGN KEY (`id_liste_variable`) REFERENCES `liste_variable` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.variable : ~0 rows (environ)
/*!40000 ALTER TABLE `variable` DISABLE KEYS */;
/*!40000 ALTER TABLE `variable` ENABLE KEYS */;

-- Export de la structure de la table population_db. variable_intervention
DROP TABLE IF EXISTS `variable_intervention`;
CREATE TABLE IF NOT EXISTS `variable_intervention` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_liste_variable` int(11) DEFAULT NULL,
  `id_variable` int(11) DEFAULT NULL,
  `id_liste_validation_beneficiaire` int(11) DEFAULT NULL,
  `id_intervention` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_variable_intervention_liste_variable` (`id_liste_variable`),
  KEY `FK_variable_intervention_variable` (`id_variable`),
  KEY `FK_variable_intervention_liste_validation_beneficiaire` (`id_liste_validation_beneficiaire`),
  KEY `FK_variable_intervention_intervention` (`id_intervention`),
  CONSTRAINT `FK_variable_intervention_intervention` FOREIGN KEY (`id_intervention`) REFERENCES `intervention` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_variable_intervention_liste_validation_beneficiaire` FOREIGN KEY (`id_liste_validation_beneficiaire`) REFERENCES `liste_validation_beneficiaire` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_variable_intervention_liste_variable` FOREIGN KEY (`id_liste_variable`) REFERENCES `liste_variable` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_variable_intervention_variable` FOREIGN KEY (`id_variable`) REFERENCES `variable` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.variable_intervention : ~0 rows (environ)
/*!40000 ALTER TABLE `variable_intervention` DISABLE KEYS */;
/*!40000 ALTER TABLE `variable_intervention` ENABLE KEYS */;

-- Export de la structure de la table population_db. zone_intervention
DROP TABLE IF EXISTS `zone_intervention`;
CREATE TABLE IF NOT EXISTS `zone_intervention` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_intervention` int(11) DEFAULT NULL,
  `id_fokontany` int(11) DEFAULT NULL,
  `menage_beneficiaire_prevu` int(11) DEFAULT NULL,
  `individu_beneficiaire_prevu` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_zone_intervention_intervention` (`id_intervention`),
  KEY `FK_zone_intervention_fokontany` (`id_fokontany`),
  CONSTRAINT `FK_zone_intervention_fokontany` FOREIGN KEY (`id_fokontany`) REFERENCES `fokontany` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_zone_intervention_intervention` FOREIGN KEY (`id_intervention`) REFERENCES `intervention` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.zone_intervention : ~0 rows (environ)
/*!40000 ALTER TABLE `zone_intervention` DISABLE KEYS */;
INSERT INTO `zone_intervention` (`id`, `id_intervention`, `id_fokontany`, `menage_beneficiaire_prevu`, `individu_beneficiaire_prevu`) VALUES
	(1, 1, 44, 125, 250);
/*!40000 ALTER TABLE `zone_intervention` ENABLE KEYS */;

-- Export de la structure de la table population_db. zone_intervention_programme
DROP TABLE IF EXISTS `zone_intervention_programme`;
CREATE TABLE IF NOT EXISTS `zone_intervention_programme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_programme` int(11) DEFAULT NULL,
  `id_district` int(11) DEFAULT NULL,
  `menage_beneficiaire_prevu` int(11) DEFAULT NULL,
  `individu_beneficiaire_prevu` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_zone_intervention_programme_programme` (`id_programme`),
  KEY `FK_zone_intervention_programme_district` (`id_district`),
  CONSTRAINT `FK_zone_intervention_programme_district` FOREIGN KEY (`id_district`) REFERENCES `district` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_zone_intervention_programme_programme` FOREIGN KEY (`id_programme`) REFERENCES `programme` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Export de données de la table population_db.zone_intervention_programme : ~0 rows (environ)
/*!40000 ALTER TABLE `zone_intervention_programme` DISABLE KEYS */;
INSERT INTO `zone_intervention_programme` (`id`, `id_programme`, `id_district`, `menage_beneficiaire_prevu`, `individu_beneficiaire_prevu`) VALUES
	(2, 1, 41, 120, 150);
/*!40000 ALTER TABLE `zone_intervention_programme` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
