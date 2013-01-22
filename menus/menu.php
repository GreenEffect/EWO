<?php
$menu = array();

$menu['jeu'][] = array('url' => $root_url, 'nom' => 'Index', 'style' => 'bold');

if (!isset($_SESSION['utilisateur']['id'])) {
	$menu['jeu'][] = array('url' => $root_url.'/inscription/', 'nom' => 'Inscription');
}

$menu['jeu'][] = array('url' => $root_url.'/forum/', 'nom' => 'Forum');
$menu['jeu'][] = array('url' => 'http://wiki.ewo-le-monde.com', 'nom' => 'Guide du Jeu');
$menu['jeu'][] = array('url' => $root_url.'/carte/', 'nom' => 'Carte du Monde');
$menu['jeu'][] = array('url' => $root_url.'/annuaire/', 'nom' => 'Annuaire');

if (isset($_SESSION['utilisateur']['id'])) {
	$menu['jeu'][] = array('url' => $root_url.'/classement/classement.php', 'nom' => 'Classement');
}

$menu['jeu'][] = array('url' => $root_url.'/bug/', 'nom' => 'Bugs');
$menu['jeu'][] = array('url' => $root_url.'/irc/', 'nom' => 'Chat');
$menu['jeu'][] = array('url' => $root_url.'/partenaires/', 'nom' => 'Partenaires');
$menu['jeu'][] = array('url' => 'http://blog.ewo-le-monde.com', 'nom' => 'Blog d\'Ewo');
$menu['jeu'][] = array('url' => $root_url.'/contact/', 'nom' => 'Contact');
$menu['jeu'][] = array('url' => $root_url.'/boutique/', 'nom' => 'Boutique');

$is_log = ControleAcces('utilisateur',0);
$is_admin = ControleAcces('admin',0);
$is_anim = ControleAcces('admin;anim',0);
$is_at = ControleAcces('admin;at',0);

if ($is_log) {
	$utilisateur_id = $_SESSION['utilisateur']['id'];


	$nbdem = 0;
	$nbdem_fac = 0;

        $nbdems = 'SELECT COUNT(w.vassal) AS nombre
				FROM persos p
                                JOIN wait_affil w
                                    ON w.superieur = p.id
				WHERE p.utilisateur_id = '.$utilisateur_id;

	$resultat1 = mysql_query ($nbdems) or die (mysql_error());
	$nbdem_ = mysql_fetch_array ($resultat1);
	$nbdem = $nbdem_['nombre'];

	for($inci=1; $inci<=$_SESSION['persos']['inc']; $inci++) {

		$perso_id = $_SESSION['persos']['id'][$inci];
		if (isset($_SESSION['persos']['faction']['droits'][$inci])){
			$droits  = $_SESSION['persos']['faction']['droits'][$inci];
		} else {
			$droits  = array(0,0,0,0,0,0,0,0);
		}

		if($droits[4] || $droits[0]) {
			$nbdems = "SELECT COUNT(wait_faction.faction_id) AS nombre
						FROM wait_faction
						INNER JOIN persos ON persos.id=$perso_id
						WHERE wait_faction.faction_id = persos.faction_id AND demandeur = '1'";

			$resultat1 = mysql_query ($nbdems) or die (mysql_error());
			$nbdem_ = mysql_fetch_array ($resultat1);
			$nbdem_fac += $nbdem_['nombre'];
		}
	}

	$nom = 'Joueurs';
	$nom_aff = $nom_fac = '';

	if($nbdem > 0) {
		$nom .= " <span style='color:#f12727'>($nbdem)</span>";
		$nom_aff = "<span style='color:#27f127'>($nbdem)</span>";
	}

	if($nbdem_fac > 0) {
		$nom .= " <span style='color:#27f127'>($nbdem_fac)</span>";
		$nom_fac  = " <span style='color:#27f127'>($nbdem_fac)</span>";

	}

	$menu['utilisateur'][] = array('url' => $root_url.'/persos/liste_persos.php', 'nom' => $nom, 'taille' => 'grand');

	$menu['utilisateur'][] = array('url' => $root_url.'/compte/', 'nom' => 'Mon compte');
	$menu['utilisateur'][] = array('url' => $root_url.'/persos/liste_persos.php', 'nom' => 'Mes personnages');
	$menu['utilisateur'][] = array('url' => $root_url.'/event/liste_events.php', 'nom' => 'Mes &eacute;v&eacute;nements');

	$creerperso = false;
	$nbperso = @$_SESSION['persos']['inc'];

// Savoir si l'utilisateur peut encore créer des persos.
	$creerperso = false;
	$camp_utilisateur = NULL;
	$nbperso = @$_SESSION['persos']['inc'];
	$nbPersoT3 = $nbPersoT4 = 0;

	if($nbperso == NULL) {
		$creerperso = true;
	} else {
		for($numPerso = 1; $numPerso <= $nbperso; $numPerso++) {
			if (!$camp_utilisateur && $_SESSION['persos']['camp'][$numPerso] != 2 && $_SESSION['persos']['camp'][$numPerso] != 5 && $_SESSION['persos']['camp'][$numPerso] != 6) {
				$camp_utilisateur = $_SESSION['persos']['camp'][$numPerso];
			}

			if ($_SESSION['persos']['type'][$numPerso] == 4)
				$nbPersoT4++;
			else
				$nbPersoT3++;
		}
	}

	if ($camp_utilisateur == 1) {
		$nbPersoRestantT3 = 1 - $nbPersoT3;
		$nbPersoRestantT4 = 8 - $nbPersoT4;
	} else {
		if ($nbPersoT4 >= 1) {
			$nbPersoRestantT3 = 2 - $nbPersoT3;
			$nbPersoRestantT4 = 4 - $nbPersoT4;
		} else {
			$nbPersoRestantT3 = 3 - $nbPersoT3;
			if ($nbPersoRestantT3 >= 1)
				$nbPersoRestantT4 = 4;
			else
				$nbPersoRestantT4 = 0;
		}
	}

	if ($nbPersoRestantT3 >= 1 || $nbPersoRestantT4 >= 1)
		$creerperso = true;
// Fin "Savoir si l'utilisateur peut encore créer des persos."

	if($creerperso) {
		$menu['utilisateur'][] = array('url' => $root_url.'/inscription/creation_perso.php', 'nom' => 'Créer un personnage');
	}

	$menu['utilisateur'][] = array('url' => $root_url.'/affiliation/', 'nom' => 'Affiliation personnages'.$nom_aff);
	$menu['utilisateur'][] = array('url' => $root_url.'/legion/', 'nom' => 'Légions personnages'.$nom_fac);

	$menu['persos'][] = array('url' => $root_url.'/persos/liste_persos.php', 'nom' => 'Page de jeu');

	if($creerperso) {
		$menu['persos'][] = array('url' => $root_url.'/inscription/creation_perso.php', 'nom' => 'Créer un personnage');
	}

	$tot_bal = 0;
	for($inci=1; $inci<=$nbperso; $inci++) {
		$datetour = $_SESSION['persos']['date_tour'][$inci];
		$datetour = strtotime($datetour);

		$time = time();

		if ($time >= $datetour) {
			$color = "style='color:#f12727'";
		} else {
			$color = "";
		}

		$id_perso = $_SESSION['persos']['id'][$inci];

		$nbbals = "SELECT COUNT(bals.id) AS nombre
					FROM bals
					INNER JOIN persos
					ON bals.perso_src_id = persos.id
					WHERE perso_dest_id = '$id_perso' AND flag_lu = '0'";

		$resultat1 = mysql_query ($nbbals) or die (mysql_error());
		$nbbal = mysql_fetch_array ($resultat1);
		$_SESSION['persos']['nbbal'][$inci] = $nbbal['nombre'];

		$tot_bal+=$_SESSION['persos']['nbbal'][$inci];

		$menu['persos'][] = array('url' => $root_url.'/jeu/index.php?perso_id='.$inci, 'nom' => '<span id="color_perso_'.$inci.'" '.$color.' >'. $_SESSION['persos']['nom'][$inci].'</span>');
	}

	$nom = 'Bals';

	if($tot_bal > 0) {
		$nom .= " <span style='color:#f12727'>(<span id='bal_total'>$tot_bal</span>)</span>";
	}

	$menu['bal'][] = array('url' => '#', 'nom' => $nom);

	for($inci=1; $inci<=$nbperso; $inci++) {
		$nom = $_SESSION['persos']['nom'][$inci];

		if($_SESSION['persos']['nbbal'][$inci] > 0) {
			$nom .= " <span style='color:#f12727'>(<span id='total_bal_".$_SESSION['persos']['id'][$inci]."'>".$_SESSION['persos']['nbbal'][$inci]."</span>)</span>";
		}

		$menu['bal'][] = array('url' => $root_url.'/messagerie/index.php?id='.$_SESSION['persos']['id'][$inci], 'nom' => $nom);
	}

	if($is_admin) {
		$menu['admin'][] = array('url' => '#', 'nom' => 'Admin', 'taille' => 'grand');
		$menu['admin'][] = array('url' => $root_url.'/admin/utilisateurs/', 'nom' => 'Editer Utilisateur');
		$menu['admin'][] = array('url' => $root_url.'/admin/persos/', 'nom' => 'Editer Personnage');
		$menu['admin'][] = array('url' => $root_url.'/admin/persos/creation_perso.php', 'nom' => 'Création de personnage');
		$menu['admin'][] = array('url' => $root_url.'/admin/persos/ewolution.php', 'nom' => 'Simulateur d\'ewolution');
		$menu['admin'][] = array('url' => $root_url.'/event/eventperso.php', 'nom' => 'Evenement d\'animation');
		$menu['admin'][] = array('url' => $root_url.'/news/liste_news.php', 'nom' => 'Gestion des News');
		$menu['admin'][] = array('url' => $root_url.'/admin/gestion_actions/', 'nom' => 'Gestion Actions');
		$menu['admin'][] = array('url' => $root_url.'/admin/gestion_camp/', 'nom' => 'Gestion Camps');
		$menu['admin'][] = array('url' => $root_url.'/admin/gestion_race/gestion_race.php', 'nom' => 'Gestion Races');
		$menu['admin'][] = array('url' => $root_url.'/admin/gestion_grade/gestion_grade.php', 'nom' => 'Gestion Grades');
		$menu['admin'][] = array('url' => $root_url.'/admin/gestion_galon/', 'nom' => 'Gestion Galons');
		$menu['admin'][] = array('url' => $root_url.'/admin/gestion_icone/', 'nom' => 'Gestion Icônes');
		$menu['admin'][] = array('url' => $root_url.'/legion/index.php?p=3', 'nom' => 'Gestion des Légions');
		$menu['admin'][] = array('url' => $root_url.'/admin/gestion_invitation/', 'nom' => 'Gestion des invitations');
		$menu['admin'][] = array('url' => $root_url.'/editeur/', 'nom' => 'Editeur');
		$menu['admin'][] = array('url' => $root_url.'/admin/newsletter/', 'nom' => 'Newsletter');
		$menu['admin'][] = array('url' => $root_url.'/admin/logs/liste_logs.php', 'nom' => 'Logs des actions');
		$menu['admin'][] = array('url' => 'http://blog.ewo-le-monde.com/wp-admin/index.php', 'nom' => 'DevBlog');
		$menu['admin'][] = array('url' => $root_url.'/statistique/stats_grades.php', 'nom' => 'Répartition des grades/galons');
	}

	if($is_at) {
		$menu['at'][] = array('url' => '#', 'nom' => 'At');
		$menu['at'][] = array('url' => $root_url.'/admin/antitriche', 'nom' => 'Administration');
	}

	if($is_anim) {
		$menu['anim'][] = array('url' => '#', 'nom' => 'Animation');
		$menu['anim'][] = array('url' => '#', 'nom' => 'Anim');
		$menu['anim'][] = array('url' => $root_url.'/event/eventperso.php', 'nom' => 'Evenement d\'animation');
		$menu['anim'][] = array('url' => $root_url.'/statistique/stats_grades.php', 'nom' => 'Répartition des grades/galons');
		$menu['anim'][] = array('url' => $root_url.'/affiliation/liste_affilies.php', 'nom' => 'Gestion des Affiliations');
		$menu['anim'][] = array('url' => $root_url.'/legion/index.php?p=3', 'nom' => 'Gestion des Légions');
		$menu['anim'][] = array('url' => $root_url.'/news/liste_news.php', 'nom' => 'Gestion des News');
	}
}
?>
