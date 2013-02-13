<?php

if(!defined('CREATION')) {
    exit;
}

include(SERVER_ROOT. "/persos/fonctions.php");

include(SERVER_ROOT."/template/header_new.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);

/*-----------------------------*/

include_once(SERVER_ROOT."/persos/creation/controle_persos.php");

extract(controleCreationPerso($utilisateur_id));


$js->AddScript('creation');

?>

<div id="page_persos">  
	<form action="ajout_perso.php" method="post" name="personnage">
	<h2>Informations n&eacute;cessaires &agrave; la cr&eacute;ation de votre/vos personnage(s)</h2>
<?php




if ($peutCreer) { //DEBUT PEUTCREER ?>
<!-- Debut du coin -->
<div class="upperleft" id='coin_75'>
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright">
			<!-- conteneur -->
	<table border="0" width="100%">
		<tr align="center">
			<td colspan="3">
					<?php echo '<h3>'.$texte.'</h3>'; 
					if(isset($_SESSION['erreur']['perso'])){echo $_SESSION['erreur']['perso'];} ?> <br />
			</td>
		</tr>
		<tr align="center">
			<td colspan="3">
				<img src="<?php echo SERVER_URL; ?>/images/site/ornement.png" width="200" height="50">
			</td>
		</tr>			
		<tr align="center">
			<td colspan="3">
				<h3>Choix du gameplay</h3>
				<p><i>Par gameplay, on entend le système de jeu. Que vous choisissiez de jouer Humain ou Ailé, il existe deux possibilités</i></p>
			</td>
		</tr>
		<tr align="center">
			<td colspan="3">
				<div style="float: left; width: 50%">
					<h3>Trois personnages distincts</h3>
					<img src="<?php echo SERVER_URL; ?>/images/races/T3.png" width="150" height="100">
					<div class="presentation">
						<p>Ce gameplay vous permet de jouer trois cases du camp de votre choix. Des Démons, des Anges, ou des Héros de l’Humanité. Vous les jouez de manière distincte et séparée – ils ne peuvent pas interagir entre eux – et donc doivent se trouver éloignés les uns des autres que ce soit dans leur RP ou sur le damier. On nomme ce gameplay “T1”.</p>
					</div>	
					<input type="button" class="choixgameplay" name="T3" value="Chuis trop un roxxor!" <?php if (!$creationT3) echo 'disabled'; ?>/>
				</div>	
				<div style="float: right; width: 50%">				
					<h3>4 personnages en coordination et deux personnage indépendant</h3>
					<img src="<?php echo SERVER_URL; ?>/images/races/T41.png" width="150" height="100">
					<div class="presentation">
						<p>Ce gameplay vous permet de jouer 4 cases du camp de votre choix. Des Diablotins, des Chérubins, ou des Humains. De constitution plus faible que les Anges, Démons et Héros, ils ont cependant l’avantage de pouvoir être joués ensemble sur le damier. Attention, ils mourront facilement, mais pourront aussi bien se montrer d’une redoutable efficacité sur le terrain. Ne comptez pas les jouer chacun dans leur coin, vous ne survivrez pas. On appelle ce Gameplay le “T4”. Notez qu'en plus de ces quatre personnages, vous avez droit à deux personnages de type T1, indépendants.</p>
					</div>
					<input type="button" class="choixgameplay" name="T4" value="L'union fait la Force!" <?php if (!$creationT4) echo 'disabled'; ?>/>
				</div>
			</td>
		</tr>	
		<?php if($camp == null) { ?>
		<tr align="center">
			<td colspan="3">
				<img src="<?php echo SERVER_URL; ?>/images/site/ornement.png" width="200" height="50">
			</td>
		</tr>	
		<tr align="center">
			<td colspan="3">
				<h3>Choix du camp</h3>
				<div class="presentation">
					<p>Eternal War One est un jeu. Tout rapprochement entre les créatures d’EWO et les écrits historiques, 
					bibliques, ou religieux quelconques est à proscrire. Ne laissez libre court qu’à votre imagination, et 
					surtout ne la bridez pas. Si les inspirations bibliques des combats entre Anges, Démons et Humains 
					sont évidentes, il ne faut surtout pas croire que EWO se veut fidèle aux croyances populaires.</p>

					<p>La vision du monde manichéenne telle que décrite – par exemple – dans la Bible ne peut en aucun cas
					être appliquée à EWO. Les Anges ne représentent pas le Bien, les Démons ne représentent pas le mal car chaque
					camp souhaite dominer le monde, et seule leur nature les différencie véritablement.</p>
				</div>
			</td>
		</tr>
		<tr align="center">
			<td class="hover_ange"><h3>Ange</h3></td>
			<td class="hover_humain"><h3>Humain</h3></td>
			<td class="hover_demon"><h3>Démon</h3></td>
		</tr>		
		<tr align="center">
			<td class="hover_ange"><img src="<?php echo SERVER_URL; ?>/images/races/ange.png" alt="Race Ange" width="165" height="220"></td>
			<td class="hover_humain"><img src="<?php echo SERVER_URL; ?>/images/races/humain.png" alt="Race Humain" width="165" height="220"></td>
			<td class="hover_demon"><img src="<?php echo SERVER_URL; ?>/images/races/demon.png" alt="Race Demon" width="165" height="220"></td>
		</tr>
		<tr align="center">
			<td class="hover_ange">
				<input type="button" class="choixrace" name="ange" value="Combattre le mal par le mal, pour les Anges c'est normal." />
			</td>
			<td class="hover_humain">
				<input type="button" class="choixrace" name="humain" value=" Les Humains sont faibles ? : on veux des preuves!" />
			</td>
			<td class="hover_demon">
				<input type="button" class="choixrace" name="demon" value="Démon ? Alors va, et ravage tout sur ton passage." />
			</td>
		</tr>
		<tr align="center">
			<td colspan="3" id="description" height="280">
				<div id="description_ange" class="presentation" style="display: none;">
					<p>Créature agréable à l’œil, l’Ange est souvent porteur de l’auréole et arbore des ailes blanches. Il est souvent représenté avec un halo bleuté autour de lui, que l’on attribue à sa connexion à Célestia. Si les Anges ont jadis fait croire à l’Humanité qu’ils étaient les envoyés de Dix-Yeux sur Althian et qu’ils représentaient le Bien, cette description est tout sauf exacte. Les Humains s’en sont, depuis, rendu-compte.</p>

					<p>Les Anges sont généralement fourbes et manipulateurs. Derrière leur apparence avenante se cache souvent cruauté et désir de conquête. Ils sont discrets et agissent, bien entendu, pour le bien de leurs propres intérêts de manière subtile. Là où le Démon préfère la force brute, l’Ange adopte généralement la traitrise et le vice. Encore que l’on trouve de tout suivant les caractères. </p>				
				</div>		
				<div id="description_humain" class="presentation" style="display: none;">
					<p>Les Humains sont dépourvus d’ailes et de tout autre don magique. Ils maîtrisent en revanche la technologie et sont pour la plupart surentraînés au combat, si bien qu’ils peuvent – depuis peu – tenir tête aux hordes d’Ailés qui envahissent Althian.</p>

					<p>La liberté est leur crédo. La guerre, leur art le plus abouti. On trouve une variété infinie de caractères différents dans l’Humanité, qui peut faire le charme de cette race. Mais, par nature, et libres de leur destin, les Humains sont généralement querelleurs et désorganisés car incapables de choisir un dirigeant. Leur liberté de choix et leurs guerres internes pourraient coûter son monde à cette race qui attend encore et toujours le héros qui saura l’unifier.</p>

					<p>Pourtant, depuis le retour des Ailés sur Althian, la race Humaine semble au moins s'accorder sur un point : bouter dehors tous ces envahisseurs. Un semblant de cohésion se forme alors car les Hommes n'ont pas oublié que, jadis, unis, ils étaient parvenus à se débarrasser de la menace Ailée. </p>				
				</div>
				<div id="description_demon" class="presentation" style="display: none;">
					<p>D’apparence généralement plus effrayante que celle des Anges, les Démons arborent des cornes et des ailes aussi noires que la nuit la plus profonde. Ils sont généralement entourés d’un halo rougeoyant, rappelant le feu couvant d’un volcan. Ils sont malins et aiment répandre la mort et la souffrance, et ils le font ouvertement la plupart du temps.</p>

					<p>Bien sûr, certains savent se montrer subtiles, et les individus sont aussi variés que les Anges, même si l’engeance démoniaque a une tendance plus prononcée pour la violence brute et le crime gratuit.</p>
				</div>				
			</td>
		</tr>
		<tr align="center">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr><?php } ?>
		<tr>
			<td colspan="3" align="right">
				<input type="hidden" id="gameplay" name="gameplay" value="" />
				<input type="hidden" id="race" name="race" value="<?php echo $camp; ?>" />
				<input type="submit" id="suite" value="Passez à l'étape suivante" disabled />
				<input type="reset" value="Effacer" />
			</td>
		</tr>
	</table>
	</form>
				<!-- fin conteneur -->
			</div>
		</div>
	</div>
</div>
<!-- Fin du coin -->

</div>

<?php
} // FIN PEUTCREER
if(isset($_SESSION['erreur']['perso'])){$_SESSION['erreur']['perso'] = '';}
//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//------------
?>