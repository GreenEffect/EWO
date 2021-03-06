<?php

use \conf\ConnecteurDAO as ConnecteurDAO;

define('CALL_FROM_CRON', true);

$path = realpath(dirname($_SERVER['SCRIPT_FILENAME']));
$root_url = explode('/',$path);
array_pop($root_url);
$root_url = join('/',$root_url);

include_once($root_url."/cron/MicroIA.class.php");

$db = ConnecteurDAO::getInstance();

$db->query("SELECT id, time FROM persos_ia WHERE time <= now() LIMIT 10");

$liste = $db->fetchAll_row();
$cpt = 0;
if($liste != null)
{       
        echo '<ul>';
	foreach($liste as $ia)
	{
                MicroIA::Run($ia[0]);
                $cpt++;
                echo '<li>'.$ia[0].' / '.$ia[1].'</li>';
	}
        echo '</ul>';
}

echo 'done * '.$cpt.' : '.time();

//print_r($liste);

//$ia = new MicroIA(4);

/*$ia->updateGrid(-8, 7, 5);
$ia->updateGrid(-8, 8, 3);

print_table($ia->dangergrid);


function print_table($grid) {
	echo '<table border="1">';
	foreach($grid as $k => $ligne)
	{
		echo '<tr>';
		foreach($ligne as $v => $colonne)
		{
			echo "<td>$colonne ($k $v)</td>";
		}
		echo '</tr>';
	}
}*/