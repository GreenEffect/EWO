<?php

namespace conf;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Helpers
 *
 * @author Leo
 */
class Helpers {
/**
 *
 */
    public static function getSelectOption($liste, $type, $choix, $defaut){
                    $html 		= array();

                    $html[]	= '<select id="'.$type.'" name="'.$type.'">';
                    $html[] = '<option value="">'.$defaut.'</option>';
                    foreach($liste as $label=>$value){
                            $selected = '';
                            if($value == $choix){
                                    $selected = ' selected="SELECTED" ';
                            }
                            $html[] = '<option value="'.$value.'"'.$selected.'>'.$label.'</option>';
                    }
                    $html[]	= '</select>';

                    return join(PHP_EOL,$html);
    }
    
    public static function Dice($valeur1,$valeur2) {
		$max = mt_getrandmax()+1.0;

		$u = sqrt(-2*log(mt_rand()/$max));
		$v = 2*M_PI*mt_rand()/$max;

		$a = round(sqrt(2.0*$valeur1/3.0)*$u*cos($v)+2.0*$valeur1);
		$b = round(sqrt(2.0*$valeur2/3.0)*$u*sin($v)+2.0*$valeur2);

		$x = min(max($valeur1,$a),3*$valeur1);
		$y = min(max($valeur2,$b),3*$valeur2);

		$dices = array($x,$y);

		return $dices;
    }
	
	/**
	 * G�n�ration d'un nouveau mot de passe
	 * @param $length Par defaut 9 sinon celui pass� en param
	 * @return $password Mot de passe retourn� par la fonction de la taille demand�
	 */
	public static function generatePassword ($length = 9)
	{
	  $password = "";
	  $possible = "0123456789abcdefghjkmnpqrstuvwxyzABCDFGHJKMNOPQRSTVWXYZ"; 
	  $i = 0; 
	  while ($i < $length) { 
		$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
		if (!strstr($password, $char)) { 
		  $password .= $char;
		  $i++;
		}
	  }
		return $password;
	}
}

?>
