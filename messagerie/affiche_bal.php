<?php
/**
 * Retourne les bals pour les parsers en ajax
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 2.1
 * @package messagerie
 */
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");
include ("messagerieDAO.php");
/*-- Connexion requise --*/
if (ControleAcces('utilisateur',0) == false){
	echo "acces denied";exit;
}
/*-----------------------*/

if (isset($_GET['id']) AND isset($_GET['exp'])){
	if ($_SESSION['perso']['id'] == $_GET['exp']){
	
	// Paramètres de connexion à la base de données
	$conn = messagerieDAO::getInstance();
	
	$id = $_GET['id'];
	$exp = $_GET['exp'];	
		
	if(isset($_GET['page']) && $_GET['page']=='send'){
		$bal = $conn->SelectBalCorpsEnvoye($id, $exp);	
	}else {
		$bal = $conn->SelectBalCorps($id, $exp);	
	}	

	//$text = array("id"=>$bal['id_bals'], "corps"=>$bal['corps'], "signature"=>$bal['signature']);
		
	/*header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');
	echo json_encode($text);*/
	
	//print_r($bal);
	echo $bal["corps"];
  }
}else{
	echo "null";
}
?>
