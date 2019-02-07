<?php
/**
* Vue : admin/Datas
* Layout : toutes les sections disponibles
*/

?>

<h3><span>Liste des auteurs</span></h3>
<h1>Données auteurs</h1>
<?php Template::put('Membres') ?>
</section>

<section>
<h3><span>Envoi de mail à tous</span></h3>
<p>Cette fonctionnalité permet d'envoyer un mail à tous les auteurs d'un coup.</p>
<form method="post" action="">

<label for="sujet">Sujet du mail :</label><input type="text" name="sujet" id="sujet" value="<?php echo (isset($_POST['sujet'])?stripslashes($_POST['sujet']):'') ?>"/>
<?php
Typo::setTexte((isset($_POST['mail'])?stripslashes($_POST['mail']):'Bonjour %Auteur% !'));
Typo::renderIDE(array('Name'=>'mail','Rows'=>10,'Preview'=>array('URL'=>'/membres/Apercu','ID'=>'Typo_preview')));
?>

<div id="Typo_preview">
<p>Cliquez sur l'icone <img src="https://neamar.fr/lib/markitup/sets/Typo/images/preview.png" alt="Flèche" class="TexPic"> au dessus de l'interface d'édition pour afficher ici un aperçu de votre texte.<br /></p>
</div>

<input type="checkbox" name="test" id="test" checked="checked" /><label for="test">Envoyer juste pour test (uniquement à neamar@neamar.fr)</label>
<br />
<input type="submit" value="Envoyer !" />
</form>
