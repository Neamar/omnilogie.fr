<?php
/**
* Vue : admin/Datas
* Layout : toutes les sections disponibles
*/

?>

<h3><span>Taxinomie exhaustive</span></h3>
<p>Cette page gère les actions sur l'arbre.</p>
</section>

<section>
<h3><span>Ajout de catégorie</span></h3>

<form method="post" action="">
<label for="parent">Catégorie mère :</label>
<select name="parent" id="parent">
<?php foreach($C['ListeAjout'] as $ID=>$Item)
	echo '<option value="' . $ID . '">' . $Item . '</option>'
?>
</select><br />
<label for="enfant">Nom de la nouvelle catégorie :</label>
<input type="text" name="enfant" id="enfant" placeholder="Enfant"/><br />

<input type="submit" value="Enregistrer la nouvelle catégorie" />
