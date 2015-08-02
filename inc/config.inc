<?php


$config["Bdd"]["server"] = 'climbingoortg.mysql.db';
$config["Bdd"]["user"] = 'climbingoortg';
$config["Bdd"]["password"] = 'azSSqX4XrfSd';
$config["Bdd"]["bddname"] = "climbingoortg";

$config["BaseDNSHttp"] = "http://www.readytogrimpe.climbing-crew.fr";
$config["BaseUrl"] =  "/";
$config["GeoportailKey"] = "gv75hsmz2ywmtajozkzq472t";

$config["KeySign"]["HashType"] = MHASH_WHIRLPOOL;
$config["KeySign"]["HashType"] = "CRYPT";
$config["KeySign"]["HashKey"]  = "Une§%ù*øCl3fAlàc0n";


$config["Auth"]["TokenTime"]  = 10  *3600*24; //10 jours
$config["Auth"]["BaseUrl"]    = "http://www.readytogrimpe.climbing-crew.fr/login.php";

$config["Right"]["TokenTime"]  = 365 *3600 *24; //1 ans
$config["Right"]["BaseUrl"]    = "http://www.readytogrimpe.climbing-crew.fr/right.php";

$config["Auth"]["mail"]["subject"] = "Jeton d'authentification pour le site Ready To Grimpe";
$config["Auth"]["mail"]["from"]    = "readytogrimpe@climbing-crew.fr";
$config["Auth"]["mail"]["body"]    = "Bonjour,\nLe lien pour l'authentification est §link§\n Il est valable 10 jours.\nMerci\n";


$config["Log"]["nbAff"] = 10;

if (preg_match("/\/public_rtg\//",$GLOBALS['_SERVER']['SCRIPT_URL']))
{
	$config["BaseUrl"] = "/public_rtg/";
	$config["Auth"]["BaseUrl"]    = "http://climbingoo.cluster007.ovh.net/public_rtg/login.php";
	$config["Right"]["BaseUrl"]    = "http://climbingoo.cluster007.ovh.net/public_rtg/right.php";
}




$config["PI"]["p"]["libelle"] = "Parking";
$config["PI"]["p"]["zoomlevel"] = "14";
$config["PI"]["p"]["icon"]  = "parking.png";

$config["PI"]["caf"]["libelle"] = "Club Alpin";
$config["PI"]["caf"]["zoomlevel"] = "8";
$config["PI"]["caf"]["icon"]  = "caf.png";

$config["PI"]["ffme"]["libelle"] = "Club FFME";
$config["PI"]["ffme"]["zoomlevel"] = "8";
$config["PI"]["ffme"]["icon"]  = "ffme.png";

$config["PI"]["pdv"]["libelle"] = "Point de vue";
$config["PI"]["pdv"]["zoomlevel"] = "14";
$config["PI"]["pdv"]["icon"]  = "point-de-vue.png";

$config["PI"]["acces"]["libelle"] = "Accès au site";
$config["PI"]["acces"]["zoomlevel"] = "14";
$config["PI"]["acces"]["icon"]  = "acces-site.png";

$config["PI"]["partenaire"]["libelle"] = "Partenaire";
$config["PI"]["partenaire"]["zoomlevel"] = "13";
$config["PI"]["partenaire"]["icon"]  = "partenaire.png";

$config["PI"]["climbingcrew"]["libelle"] = "Climbing Crew";
$config["PI"]["climbingcrew"]["zoomlevel"] = "7";
$config["PI"]["climbingcrew"]["icon"]  = "cc.png";


$config['type']['recomandation']['nom'] = 'Recomandation';
$config['type']['recomandation']['mode'] = 'multi';
$config['type']['recomandation']['valeurs'][] = "Coup de coeur";
$config['type']['recomandation']['valeurs'][] = "Voie majeur";



$config['type']['profil']['nom'] = 'Type de profil';
$config['type']['profil']['mode'] = 'multi';
$config['type']['profil']['valeurs'][] = "devers";
$config['type']['profil']['valeurs'][] = "toit";
$config['type']['profil']['valeurs'][] = "surplomb";
$config['type']['profil']['valeurs'][] = "dalle";
$config['type']['profil']['valeurs'][] = "vertical";
$config['type']['profil']['valeurs'][] = "diédre";
$config['type']['profil']['valeurs'][] = "fissure";
$config['type']['profil']['valeurs'][] = "traversées";
$config['type']['profil']['valeurs'][] = "proue";

$config['type']['prises']['nom'] = 'Type de prises';
$config['type']['prises']['mode'] = 'multi';
$config['type']['prises']['valeurs'][] = "reglettes";
$config['type']['prises']['valeurs'][] = "trous";
$config['type']['prises']['valeurs'][] = "verticales";
$config['type']['prises']['valeurs'][] = "inverses";
$config['type']['prises']['valeurs'][] = "plats";

$config['type']['escalade']['nom'] = 'Type d\'escalade';
$config['type']['escalade']['mode'] = 'multi';
$config['type']['escalade']['valeurs'][] = "technique";
$config['type']['escalade']['valeurs'][] = "athlétique";
$config['type']['escalade']['valeurs'][] = "rési";
$config['type']['escalade']['valeurs'][] = "conti";
$config['type']['escalade']['valeurs'][] = "dynamique";
$config['type']['escalade']['valeurs'][] = "bloc";
$config['type']['escalade']['valeurs'][] = "morpho";


$config['complements']['si']['ouvreur']['nom'] = 'Ouvreur(s)';
$config['complements']['si']['ouvreur']['mode'] = 'text';

$config['complements']['w']['ouvreur']['nom'] = 'Ouvreur(s)';
$config['complements']['w']['ouvreur']['mode'] = 'text';

$config['complements']['w']['dateouvreur']['nom'] = 'Date d\'ouverture';
$config['complements']['w']['dateouvreur']['mode'] = 'text';


$config['complements']['w']['nomalternatif']['nom'] = 'Nom alternatif';
$config['complements']['w']['nomalternatif']['mode'] = 'text';


$config['complements']['w']['cotemorfo']['nom']  = 'Cote morphologique';
$config['complements']['w']['cotemorfo']['mode'] = 'text';


$config['complements']['w']['piedvoie']['nom']  = 'Qualité du pied de la voie';
$config['complements']['w']['piedvoie']['mode'] = 'qualite';
$config['complements']['w']['piedvoie']['valeurs'][2] = "Excellent";
$config['complements']['w']['piedvoie']['valeurs'][1] = "Bon";
$config['complements']['w']['piedvoie']['valeurs'][0] = "Correct";
$config['complements']['w']['piedvoie']['valeurs'][-1] = "A peine confortable";
$config['complements']['w']['piedvoie']['valeurs'][-2] = "Accidenté";
$config['complements']['w']['piedvoie']['valeurs'][-3] = "Dangereux";

$config['complements']['si']['piedvoie'] = $config['complements']['sc']['piedvoie'] = $config['complements']['sp']['piedvoie']  = $config['complements']['w']['piedvoie'];


$config['complements']['w']['commantaireequipement']['nom']  = 'Commentaire sur l\'équipement';
$config['complements']['w']['commantaireequipement']['mode'] = 'text';
$config['complements']['si']['commantaireequipement'] = $config['complements']['sc']['commantaireequipement'] = $config['complements']['sp']['commantaireequipement']  = $config['complements']['w']['commantaireequipement'];


$config['complements']['w']['equipement']['nom']  = 'Qualité de l\'équipement';
$config['complements']['w']['equipement']['mode'] = 'qualite';
$config['complements']['w']['equipement']['valeurs'][2] = "Trés bien équipé / ecole";
$config['complements']['w']['equipement']['valeurs'][1] = "Bien équipé";
$config['complements']['w']['equipement']['valeurs'][0] = "Assez bien équipé";
$config['complements']['w']['equipement']['valeurs'][-1] = "Distant";
$config['complements']['w']['equipement']['valeurs'][-2] = "Engagé";
$config['complements']['w']['equipement']['valeurs'][-3] = "Exposé";
$config['complements']['si']['equipement'] = $config['complements']['sc']['equipement'] = $config['complements']['sp']['equipement']  = $config['complements']['w']['equipement'];


$config['complements']['w']['commantaireqroche']['nom']  = 'Commentaire sur la qualité de la roche';
$config['complements']['w']['commantaireqroche']['mode'] = 'text';
$config['complements']['si']['commantaireqroche'] = $config['complements']['sc']['commantaireqroche'] = $config['complements']['sp']['commantaireqroche']  = $config['complements']['w']['commantaireqroche'];


$config['complements']['w']['qroche']['nom']  = 'Qualité de la roche';
$config['complements']['w']['qroche']['mode'] = 'qualite';
$config['complements']['w']['qroche']['valeurs'][2] = "Trés bonne";
$config['complements']['w']['qroche']['valeurs'][1] = "Bonne";
$config['complements']['w']['qroche']['valeurs'][0] = "Correct";
$config['complements']['w']['qroche']['valeurs'][-1] = "Mauvaise";
$config['complements']['w']['qroche']['valeurs'][-2] = "Très mauvaise";
$config['complements']['si']['qroche'] = $config['complements']['sc']['qroche'] = $config['complements']['sp']['qroche']  = $config['complements']['w']['qroche'];


$config['complements']['si']['troche']['nom']  = 'Type de roche';
$config['complements']['si']['troche']['mode'] = 'select';
$config['complements']['si']['troche']['valeurs']['calcaire'] = "Calcaire";
$config['complements']['si']['troche']['valeurs']['granite'] = "Granite";
$config['complements']['si']['troche']['valeurs']['gres'] = "Grès";

$config['complements']['si']['expo']['nom']  = 'Exposition principale';
$config['complements']['si']['expo']['mode'] = 'select';
$config['complements']['si']['expo']['valeurs']['Nord'] = "Nord";
$config['complements']['si']['expo']['valeurs']['NordEst'] = "Nord - Est";
$config['complements']['si']['expo']['valeurs']['Est'] = "Est";
$config['complements']['si']['expo']['valeurs']['SudEst'] = "Sud - Est";
$config['complements']['si']['expo']['valeurs']['Sud'] = "Sud";
$config['complements']['si']['expo']['valeurs']['SudOuest'] = "Sud - Ouest";
$config['complements']['si']['expo']['valeurs']['Ouest'] = "Ouest";
$config['complements']['si']['expo']['valeurs']['NordOuest'] = "Nord - Ouest";



$config['complements']['si']['gestionaire']['nom']  = 'Gestionnaire';
$config['complements']['si']['gestionaire']['mode'] = 'text';

$config['complements']['si']['urltopo']['nom']  = 'Lien vers le topo';
$config['complements']['si']['urltopo']['mode'] = 'url';


$config['complements']['si']['txttopo']['nom']  = 'Où trouver le topo officiel';
$config['complements']['si']['txttopo']['mode'] = 'text';

$config['complements']['si']['txtauteurtopo']['nom']  = 'Auteur de ce topo';
$config['complements']['si']['txtauteurtopo']['mode'] = 'text';


$config['complements']['w']['enfants']['nom']  = 'Adapté aux enfants';
$config['complements']['w']['enfants']['mode'] = 'qualite';
$config['complements']['w']['enfants']['valeurs'][2] = "Oui";
$config['complements']['w']['enfants']['valeurs'][-1] = "Non";

$config['complements']['si']['enfants'] = $config['complements']['sc']['enfants'] = $config['complements']['sp']['enfants']  = $config['complements']['w']['enfants'];


$config['complements']['w']['photo']['nom']  = 'Photo';
$config['complements']['w']['photo']['mode'] = 'image';
$config['complements']['w']['photo']['position'] = 'central';
$config['complements']['si']['photo'] = $config['complements']['sc']['photo'] = $config['complements']['sp']['photo']  = $config['complements']['w']['photo'];


$config['complements']['w']['gpx']['nom']  = 'Trace GPS (gpx)';
$config['complements']['w']['gpx']['mode'] = 'gpx';
//$config['complements']['w']['gpx']['position'] = 'central';
$config['complements']['pi']['gpx'] = $config['complements']['si']['gpx'] = $config['complements']['sc']['gpx'] = $config['complements']['sp']['gpx']  = $config['complements']['w']['gpx'];


$config['complements']['pi']['logo']['nom']  = 'image';
$config['complements']['pi']['logo']['mode'] = 'image';

$config['complements']['pi']['siteweb']['nom']  = 'Site internet';
$config['complements']['pi']['siteweb']['mode'] = 'url';

$config['complements']['pi']['tel']['nom']  = 'Téléphone';
$config['complements']['pi']['tel']['mode'] = 'text';




?>
