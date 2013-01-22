<?php
/**
 * Carte de la terre
 *
 * @author Anarion <anarion@ewo.fr>
 * @version 1.0
 * @package carte
 */
session_start();
header("content-Type: image/png");

$root_url = "..";

include("../conf/master.php");
include("../persos/fonctions.php");
include("../jeu/fonctions.php");
// Param�tres de connexion � la base de donn�es
$ewo_bdd = bdd_connect('ewo');

ControleAcces('utilisateur',1);

// Recup�ration des donn�es de la carte

$carte_pos 		= 1;

$sql="SELECT * FROM cartes WHERE id='$carte_pos'";
$resultat = mysql_query ($sql) or die (mysql_error());
$carte = mysql_fetch_array ($resultat);

$x_min_carte = $carte['x_min'];
$x_max_carte = $carte['x_max'];

$y_min_carte = $carte['y_min'];
$y_max_carte = $carte['y_max'];

$x_min_visible = $carte['visible_x_min'];
$x_max_visible = $carte['visible_x_max'];

$y_min_visible = $carte['visible_y_min'];
$y_max_visible = $carte['visible_y_max'];

$circ_x = $carte['circ'][0];
$circ_y = $carte['circ'][1];

//Determination de la taille

if($circ_x){
	$taille_x=$x_max_visible - $x_min_visible;
	}
	else $taille_x=$x_max_visible - $x_min_visible + 1;

if($circ_y){
		$taille_y=$y_max_visible - $y_min_visible;
		}
		else $taille_y=$y_max_visible - $y_min_visible + 1;
		
$time_up=false;
$time=time();

if(isset($_GET['race']) && !preg_match('#[a-z]#i',$_GET['race']) && isset($_GET['grade']) && !preg_match('#[a-z]#i',$_GET['grade'])){
$grace  = $_GET['race'];
$ggrade = $_GET['grade'];

if (isset($_SESSION['utilisateur']['carte_terre']['race'][$grace][$ggrade])){
	if($_SESSION['utilisateur']['carte_terre']['race'][$grace][$ggrade]+90<$time){
		$time_up = true;
		$_SESSION['utilisateur']['carte_terre']['race'][$grace][$ggrade] = $time;
		}else $time_up = false ;
	} else {
		$_SESSION['utilisateur']['carte_terre']['race'][$grace][$ggrade] = $time;
		$time_up = true;
		}
}

if(isset($_GET['porte']) && $_GET['porte']==1){

if (isset($_SESSION['utilisateur']['carte_terre']['porte'])){
	if($_SESSION['utilisateur']['carte_terre']['porte']+90<$time){
		$time_up = true;
		$_SESSION['utilisateur']['carte_terre']['porte'] = $time;
		}else $time_up = false ;
	} else {
		$_SESSION['utilisateur']['carte_terre']['porte'] = $time;
		$time_up = true;
		}
}

if(isset($_GET['bouclier']) && $_GET['bouclier']==1){

if (isset($_SESSION['utilisateur']['carte_terre']['bouclier'])){
	if($_SESSION['utilisateur']['carte_terre']['bouclier']+90<$time){
		$time_up = true;
		$_SESSION['utilisateur']['carte_terre']['bouclier'] = $time;
		}else $time_up = false ;
	} else {
		$_SESSION['utilisateur']['carte_terre']['bouclier'] = $time;
		$time_up = true;
		}
}


if(isset($_GET['viseur']) && $_GET['viseur']==1){
	$time_up = true;
	}


if(isset($_GET['grille']) && $_GET['grille']==1){

if (isset($_SESSION['utilisateur']['carte_terre']['grille'])){
	if($_SESSION['utilisateur']['carte_terre']['grille']+90<$time){
		$time_up = true;
		$_SESSION['utilisateur']['carte_terre']['grille'] = $time;
		}else $time_up = false ;
	} else {
		$_SESSION['utilisateur']['carte_terre']['grille'] = $time;
		$time_up = true;
		}
}

if($time_up || (isset($_GET['viseur']) && $_GET['viseur']==1)){
//Cr�ation de l'image 750*750
//$carte = imagecreatefromjpeg("../images/cartes/plan/terre.jpg");
$coeff=10;
$maxi_x=700;
$maxi_y=400;
$mini_carte = imagecreate($coeff*$taille_x , $coeff*$taille_y);
$maxi_carte = imagecreate($maxi_x , $maxi_y);

$fond = imagecolorallocate($mini_carte, 230,  230, 150);

$humain	= imagecolorallocate($mini_carte,0,200,0);
$roi	= imagecolorallocate($mini_carte,0,200,0);
$paria	= imagecolorallocate($mini_carte,200,0,200);
$AA		= imagecolorallocate($mini_carte,0,0,200);
$ange	= imagecolorallocate($mini_carte,0,0,200);
$SD		= imagecolorallocate($mini_carte,200,0,0);
$demon	= imagecolorallocate($mini_carte,200,0,0);
$black	= imagecolorallocate($mini_carte,0,0,0);
$bouclier   = imagecolorallocate($mini_carte,0,100,100);
}

//Recup�ration des personnages de la carte
$rchch_x= "(pos_x>='$x_min_visible' AND pos_x<='$x_max_visible')";
$rchch_y= "( pos_y>='$y_min_visible' AND pos_y<='$y_max_visible')";


if(isset($_GET['race']) && !preg_match('#[a-z]#i',$_GET['race']) && isset($_GET['grade']) && !preg_match('#[a-z]#i',$_GET['grade'])){
	if($time_up){
	if($_GET['grade']==-1){
		$rchch_race='1';
		$rchch_grade='persos.grade_id='.$_GET['grade'];
		}elseif($_GET['grade']==0){
			$rchch_race='races.camp_id='.$_GET['race'];
			$rchch_grade='(persos.grade_id>=0 AND persos.grade_id<4)';
				}else{
					$rchch_race='races.camp_id='.$_GET['race'];
					$rchch_grade='persos.grade_id='.$_GET['grade'];
					}
					
$sql="SELECT * FROM damier_persos WHERE (($rchch_x) AND ($rchch_y) AND (carte_id='$carte_pos'))";
$reponse = mysql_query($sql) or die(mysql_error());
while($persos=mysql_fetch_array($reponse)){
	$sql_info="SELECT persos.race_id AS race_id, persos.grade_id AS grade_id, races.camp_id AS camp FROM persos INNER JOIN races ON races.race_id = persos.race_id WHERE $rchch_race AND $rchch_grade AND persos.id='".$persos['perso_id']."'";
	$rep_infos = mysql_query($sql_info) or die(mysql_error());
	if($infos=mysql_fetch_array($rep_infos)){

	switch($infos['camp']){
		case 1 :
			if($infos['grade_id']>=4){
				$color=$roi;			
				}
				else{
					$color=$humain;	
					}
		break;
			case 2 :
			if($infos['grade_id']>4){
				$color=$paria;			
				}
				else{
					$color=$paria;	
					}
		break;
			case 3 :
			if($infos['grade_id']>4){
				$color=$AA;	
				}
				else{
					$color=$ange;
					}
		break;
			case 4 :
			if($infos['grade_id']>4){
				$color=$SD;			
				}
				else{
					$color=$demon;
					}
		break;
		default:	
			$color=$black;	
			}
	if($circ_y){
		imagefilledrectangle ($mini_carte, $coeff*($persos['pos_x']-$x_min_visible-1), $coeff*(imagesy($mini_carte)/$coeff-($persos['pos_y']-$y_min_visible)), $coeff*($persos['pos_x']-$x_min_visible-1)+$coeff, $coeff*(imagesy($mini_carte)/$coeff-($persos['pos_y']-$y_min_visible))+$coeff, $color);
		}
		else {
			imagefilledrectangle($mini_carte, $coeff*($persos['pos_x']-$x_min_visible-1), $coeff*(imagesy($mini_carte)/$coeff-($persos['pos_y']-$y_min_visible+1)), $coeff*($persos['pos_x']-$x_min_visible-1)+$coeff, $coeff*(imagesy($mini_carte)/$coeff-($persos['pos_y']-$y_min_visible+1))+$coeff, $color);
			}
		}
	}
	imagecopyresized($maxi_carte, $mini_carte, 0, 0, 0, 0, imagesx($maxi_carte), imagesy($maxi_carte), imagesx($mini_carte), imagesy($mini_carte));
	imagecolortransparent($maxi_carte, $fond);
	imagepng($maxi_carte,"./terre_R".$_GET['race']."G".$_GET['grade'].".png");
	imagecolortransparent($mini_carte, $fond);
	imagepng($mini_carte,"./Gterre_R".$_GET['race']."G".$_GET['grade'].".png");
	}else{
	
		$maxi_carte = imagecreatefrompng("./terre_R".$_GET['race']."G".$_GET['grade'].".png");
		$mini_carte = imagecreatefrompng("./Gterre_R".$_GET['race']."G".$_GET['grade'].".png");
		}
}

if(isset($_GET['porte']) && $_GET['porte']==1){
if($time_up){
$sql="SELECT * FROM damier_porte WHERE (($rchch_x) AND ($rchch_y) AND (carte_id='$carte_pos'))";
$reponse = mysql_query($sql) or die(mysql_error());
while($infos=mysql_fetch_array($reponse)){
	$color=$black;	
	if($circ_y){
		for($inci=0;$inci<=3;$inci++){
			for($incj=0;$incj<=3;$incj++){
				imagefilledrectangle($mini_carte, $coeff*($infos['pos_x']+$inci-$x_min_visible-1), $coeff*(imagesy($mini_carte)/$coeff-($infos['pos_y']-$incj-$y_min_visible)),  $coeff*($infos['pos_x']+$inci-$x_min_visible-1)+$coeff, $coeff*(imagesy($mini_carte)/$coeff-($infos['pos_y']-$incj-$y_min_visible))+$coeff, $color);
				}
			}
		}
		else {
			for($inci=0;$inci<=3;$inci++){
				for($incj=0;$incj<=3;$incj++){
					imagefilledrectangle($mini_carte, $coeff*($infos['pos_x']+$inci-$x_min_visible-1), $coeff*(imagesy($mini_carte)/$coeff-($infos['pos_y']-$incj-$y_min_visible+1)), $coeff*($infos['pos_x']+$inci-$x_min_visible-1)+$coeff, $coeff*(imagesy($mini_carte)/$coeff-($infos['pos_y']-$incj-$y_min_visible+1))+$coeff, $color);
					}
				}
		}
	}
	imagecopyresized($maxi_carte, $mini_carte, 0, 0, 0, 0, imagesx($maxi_carte), imagesy($maxi_carte), imagesx($mini_carte), imagesy($mini_carte));
	imagecolortransparent($maxi_carte, $fond);
	imagepng($maxi_carte,"./terre_porte.png");
	imagecolortransparent($mini_carte, $fond);
	imagepng($mini_carte,"./Gterre_porte.png");
	}else{
		$maxi_carte = imagecreatefrompng("./terre_porte.png");
		$mini_carte = imagecreatefrompng("./Gterre_porte.png");
		}
}

if(isset($_GET['bouclier']) && $_GET['bouclier']==1){
if($time_up){
$sql="SELECT * FROM damier_bouclier WHERE (($rchch_x) AND ($rchch_y) AND (carte_id='$carte_pos'))";
$reponse = mysql_query($sql) or die(mysql_error());
while($infos=mysql_fetch_array($reponse)){
	$color=$bouclier;
	$taille=$infos['type_id'];
	if($circ_y){
		for($inci=0;$inci<$taille;$inci++){
			for($incj=0;$incj<$taille;$incj++){
				imagefilledrectangle($mini_carte, $coeff*($infos['pos_x']+$inci-$x_min_visible-1), $coeff*(imagesy($mini_carte)/$coeff-($infos['pos_y']-$incj-$y_min_visible)), $coeff*($infos['pos_x']+$inci-$x_min_visible-1)+$coeff, $coeff*(imagesy($mini_carte)/$coeff-($infos['pos_y']-$incj-$y_min_visible))+$coeff, $color);
				}
			}
		}
		else {
			for($inci=0;$inci<$taille;$inci++){
				for($incj=0;$incj<$taille;$incj++){
					imagefilledrectangle($mini_carte, $coeff*($infos['pos_x']+$inci-$x_min_visible-1), $coeff*(imagesy($mini_carte)/$coeff-($infos['pos_y']-$incj-$y_min_visible+1)), $coeff*($infos['pos_x']+$inci-$x_min_visible-1)+$coeff, $coeff*(imagesy($mini_carte)/$coeff-($infos['pos_y']-$incj-$y_min_visible+1))+$coeff, $color);
					}
				}
		}
	}
	imagecopyresized($maxi_carte, $mini_carte, 0, 0, 0, 0, imagesx($maxi_carte), imagesy($maxi_carte), imagesx($mini_carte), imagesy($mini_carte));
	imagecolortransparent($maxi_carte, $fond);
	imagepng($maxi_carte,"./terre_bouclier.png");
	imagecolortransparent($mini_carte, $fond);
	imagepng($mini_carte,"./Gterre_bouclier.png");
	}else{
		$maxi_carte = imagecreatefrompng("./terre_bouclier.png");
		$mini_carte = imagecreatefrompng("./Gterre_bouclier.png");
		}
}

if(isset($_GET['grille']) && $_GET['grille']==1){
	if($time_up){
		
		$fond = imagecolorallocate($maxi_carte, 230,  230, 150);
		$black	= imagecolorallocate($maxi_carte,0,0,0);
		imagecolortransparent($maxi_carte, $fond);
		$sub_x=$maxi_x/5;
		$sub_y=$maxi_y/5;
		for($inci=1;$inci<=4;$inci++){
			imageline($maxi_carte, 0, $inci*$sub_y, imagesx($maxi_carte), $inci*$sub_y, $black);
			imagestring  (  $maxi_carte  ,  2  ,  2  ,  $inci*$sub_y+5  ,  "Y=".($y_max_visible-$inci*$sub_y/$maxi_y*$taille_y)  ,  $black  );
			imagestring  (  $maxi_carte  ,  2  ,  imagesx($maxi_carte)-30  ,  $inci*$sub_y+5  ,  "Y=".($y_max_visible-$inci*$sub_y/$maxi_y*$taille_y)  ,  $black  );
			imagepng($maxi_carte,"./grille.png");
			}
		for($inci=1;$inci<=4;$inci++){
			imageline($maxi_carte, $inci*$sub_x, 0, $inci*$sub_x, imagesy($maxi_carte), $black);
			imagestring  ($maxi_carte, 2, $inci*$sub_x+5, 3, "X=".($x_min_visible+$inci*$sub_x/$maxi_x*$taille_x),   $black);
			imagestring  ($maxi_carte, 2, $inci*$sub_x+5, imagesy($maxi_carte)-15, "X=".($x_min_visible+$inci*$sub_x/$maxi_x*$taille_x), $black);
			imagepng($maxi_carte,"./grille.png");
			}
		}else{
			$maxi_carte = imagecreatefrompng("./grille.png");
			}

	}
	
if(isset($_GET['viseur']) && $_GET['viseur']==1){
	// R�cup�ration des donn�es des persos.
	$nb_perso =  $_SESSION['persos']['inc'];
	$fond	= imagecolorallocate($maxi_carte,0,100,100);
	$black	= imagecolorallocate($maxi_carte,0,0,0);
//echo imagesx($maxi_carte);echo "   ";echo imagesy($maxi_carte);echo " ;  ";
	for($inc=1 ; $inc<=$nb_perso ; $inc++){
		if(isset($_SESSION['persos']['carte'][$inc]) && $_SESSION['persos']['carte'][$inc]==1){
			// echo $centre_x = round(imagesx($maxi_carte)*($_SESSION['persos']['pos_x'][$inc]-$x_min_visible-1)/imagesx($mini_carte)); echo "   ";
			// echo $centre_y = round(imagesy($maxi_carte)*(imagesy($mini_carte)-($_SESSION['persos']['pos_y'][$inc]-$y_min_visible))/imagesy($mini_carte));echo "   ";echo "   ";
			$centre_x = imagesx($maxi_carte)*($_SESSION['persos']['pos_x'][$inc]-$x_min_visible-1)/(imagesx($mini_carte)/$coeff)+1;
			$centre_y = imagesy($maxi_carte)*(imagesy($mini_carte)/$coeff-($_SESSION['persos']['pos_y'][$inc]-$y_min_visible))/(imagesy($mini_carte)/$coeff)+1;
			$valx_min = $centre_x-2;
			$valx_max = $centre_x+2;
			$valy_min = $centre_y-2;
			$valy_max = $centre_y+2;
			
			imageline($maxi_carte, $valx_min-6, $centre_y, $valx_min, $centre_y, $black);
			imageline($maxi_carte, $valx_max, $centre_y, $valx_max+6, $centre_y, $black);
			imageline($maxi_carte, $centre_x, $valy_min-6, $centre_x, $valy_min, $black);
			imageline($maxi_carte, $centre_x, $valy_max, $centre_x, $valy_max+6, $black);
			imageellipse  ($maxi_carte, $centre_x, $centre_y, 12, 12, $black );
			}
		}
	imagecolortransparent($maxi_carte, $fond);
	
}
//imagecopymerge($carte, $maxi_carte, 0, 0, 0, 0, imagesx($maxi_carte), imagesy($maxi_carte), 100);
if(isset($_GET['zoom']) && $_GET['zoom']==1){
	imagepng($mini_carte);
	}else imagepng($maxi_carte);
	
	if($time_up || (isset($_GET['viseur']) && $_GET['viseur']==1) || (isset($_GET['zoom']) && $_GET['zoom']==1)){
		imagedestroy($mini_carte);
		}
imagedestroy($maxi_carte);

mysql_close($ewo_bdd);
