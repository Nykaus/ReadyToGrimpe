
/*---------------------------------------------------------------
  SQL DB BACKUP 19.07.2015 12:51 
  HOST: climbingoortg.mysql.db
  DATABASE: climbingoortg
  TABLES: Array
  ---------------------------------------------------------------*/

/*---------------------------------------------------------------
  TABLE: `topo_site`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `topo_site`;
CREATE TABLE `topo_site` (
  `site_id` int(11) NOT NULL AUTO_INCREMENT,
  `site_nom` varchar(100) DEFAULT 'Site sans nom',
  `site_description_courte` text,
  `site_description_longue` text,
  `site_type` varchar(255) NOT NULL DEFAULT 'Falaise',
  `site_public` int(1) NOT NULL,
  `site_complements` text NOT NULL,
  `site_lat` decimal(10,8) NOT NULL,
  `site_lon` decimal(11,8) NOT NULL,
  `site_hauteur_min` int(3) NOT NULL,
  `site_hauteur_max` int(3) NOT NULL,
  `site_url_achat` text NOT NULL,
  PRIMARY KEY (`site_id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;


/*---------------------------------------------------------------
  TABLE: `topo_secteur`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `topo_secteur`;
CREATE TABLE `topo_secteur` (
  `secteur_id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL,
  `secteur_nom` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `secteur_photo` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `secteur_description_courte` text COLLATE latin1_general_ci NOT NULL,
  `secteur_description_longue` longtext COLLATE latin1_general_ci NOT NULL,
  `secteur_ordre` int(11) NOT NULL,
  `secteur_complements` text COLLATE latin1_general_ci NOT NULL,
  `secteur_groupe` varchar(255) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`secteur_id`)
) ENGINE=MyISAM AUTO_INCREMENT=93 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*---------------------------------------------------------------
  TABLE: `topo_depart`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `topo_depart`;
CREATE TABLE `topo_depart` (
  `depart_id` int(11) NOT NULL AUTO_INCREMENT,
  `secteur_id` int(11) NOT NULL,
  `depart_lat` decimal(10,8) DEFAULT NULL,
  `depart_lon` decimal(11,8) DEFAULT NULL,
  `depart_exposition` varchar(45) DEFAULT NULL,
  `depart_description_courte` text,
  `depart_description_longue` text,
  `depart_ordre` int(11) NOT NULL,
  `depart_complements` text NOT NULL,
  PRIMARY KEY (`depart_id`)
) ENGINE=MyISAM AUTO_INCREMENT=470 DEFAULT CHARSET=utf8;

/*---------------------------------------------------------------
  TABLE: `topo_voie`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `topo_voie`;
CREATE TABLE `topo_voie` (
  `voie_id` int(11) NOT NULL AUTO_INCREMENT,
  `depart_id` int(11) NOT NULL,
  `voie_nom` varchar(100) DEFAULT '',
  `voie_cotation_indice` int(11) DEFAULT '6',
  `voie_cotation_lettre` varchar(1) DEFAULT 'a',
  `voie_cotation_ext` varchar(2) DEFAULT NULL,
  `voie_dessin` longtext,
  `voie_hauteur` int(3) DEFAULT NULL,
  `voie_degaine` int(3) DEFAULT NULL,
  `voie_description_courte` varchar(255) DEFAULT NULL,
  `voie_description_longue` longtext,
  `voie_type` varchar(255) DEFAULT NULL,
  `voie_complements` text NOT NULL,
  `voie_ordre` int(11) NOT NULL,
  `voie_type_depart` varchar(255) NOT NULL DEFAULT 'debout',
  PRIMARY KEY (`voie_id`)
) ENGINE=MyISAM AUTO_INCREMENT=628 DEFAULT CHARSET=utf8;

/*---------------------------------------------------------------
  TABLE: `topo_log`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `topo_log`;
CREATE TABLE `topo_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_qui` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `log_action` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `log_element_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `log_element_id` int(11) NOT NULL,
  `log_date` datetime NOT NULL,
  `log_date_derniere` datetime NOT NULL,
  `log_element_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1568 DEFAULT CHARSET=utf8;

/*---------------------------------------------------------------
  TABLE: `topo_commentaires`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `topo_commentaires`;
CREATE TABLE `topo_commentaires` (
  `commentaires_id` int(11) NOT NULL AUTO_INCREMENT,
  `commentaires_qui` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `commentaires_data` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `commentaires_element_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `commentaires_element_id` int(11) NOT NULL,
  `commentaires_date` datetime NOT NULL,
  `commentaires_element_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `commentaires_public` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`commentaires_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

/*---------------------------------------------------------------
  TABLE: `topo_mesvoies`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `topo_mesvoies`;
CREATE TABLE `topo_mesvoies` (
  `voie_id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `mesvoies_datas` text NOT NULL,
  `mesvoies_derniere_tentatives` date NOT NULL,
  `mesvoies_valide` tinyint(1) NOT NULL,
  `mesvoies_nombre_tentative` int(11) NOT NULL,
  PRIMARY KEY (`voie_id`,`utilisateur_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*---------------------------------------------------------------
  TABLE: `topo_secteur_groupe`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `topo_secteur_groupe`;
CREATE TABLE `topo_secteur_groupe` (
  `groupe_name` varchar(255) NOT NULL,
  `groupe_ordre` int(11) NOT NULL,
  `site_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `topo_secteur_groupe` VALUES   ('Secteur Blocs Roses','30','17');
INSERT INTO `topo_secteur_groupe` VALUES ('Secteur Pierres Posées','20','17');
INSERT INTO `topo_secteur_groupe` VALUES ('Secteur du Plateau','10','17');
