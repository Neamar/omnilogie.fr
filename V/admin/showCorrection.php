<?php
/**
* Vue : admin/Edit
* Layout : Titre,
* Article
* Liens
*/
?>
<style type="text/css">
.fullscrenIDE
{
	position: fixed;
	top: 0;
	left: 0;
	width: 50%;
	margin: 0;
	height: 100%;
	background:white;
	overflow:scroll;
}

.fullscrenIDE hr
{
	display:none
}

#Typo_preview.fullscreenPreview
{
	position:fixed;
	top: 0;
	left: 50%;
	width: 50%;
	margin: 0;
	height: 100%;
	background:white;
	z-index:10;
	overflow:scroll;
}

#menu ul, aside#menus
{
	z-index:5
}
</style>

<h3><span>Modification d'article</span></h3>
<p>Lien vers l'article tel qu'il sera affiché : <?php Template::put('LienDirect'); ?><br />
<p><a href="/admin/Edit/">Liste des articles à modifier</a></p>
<p><a href="<?php echo Link::omni($C['Titre'],'/admin/'); ?>">Administrer l'article</a></p>
<p><a href="<?php echo Link::omni($C['Titre'],'/admin/Ref/'); ?>">Référencer l'article</a></p>
</section>

<section>
<h3><span>Formulaire de correction</span></h3>
<form method="post" action="">
<label for="titre">Titre :</label><br />
<input type="text" name="titre" id="titre" value="<?php Template::put('titre','Valeurs') ?>" /><br />


<hr />
<?php
Typo::setTexte($C['Valeurs']['article']);
Typo::renderIDE(array('Name'=>'article','Rows'=>17,'Preview'=>array('URL'=>'/membres/Apercu','ID'=>'Typo_preview')));
?>

<script type="text/javascript">
$(function(){
	var fullscreen = $('<li class="markItUpButton typo_fullscreen"><a title="Plein écran" href="#" style="background-image: url(https://neamar.fr/lib/markitup/sets/Typo/images/fullscreen.png)">Plein écran</a>');
	$('#markItUpArticle ul:first').append(fullscreen);

	var isFullscreen = false;
	fullscreen.click(function() {
		$('aside.info').remove();
		if(!isFullscreen) {
			$('#global-f form:first').addClass('fullscrenIDE');
			$('#Typo_preview').addClass('fullscreenPreview');
		} else {
			$('#global-f form:first').removeClass('fullscrenIDE');
			$('#Typo_preview').removeClass('fullscreenPreview');
		}

		isFullscreen = !isFullscreen;
		return false;
	});
});
</script>
<hr />

<p>C'est tout bon ? Cliquez ici pour enregistrer vos modifications.</p>
<input type="submit" value="Enregistrer les changements" id="save_change"/>

<hr />

<label for="sources">Sources :</label><br />
<textarea cols="25" rows="5" name="sources" id="sources"><?php Template::put('sources','Valeurs') ?>
</textarea>
</form>

<hr />

<p>Aperçu de l'article modifié :</p>
<div id="Typo_preview">
<p>Cliquez sur l'icone <img src="https://neamar.fr/lib/markitup/sets/Typo/images/preview.png" alt="Flèche" class="TexPic"> au-dessus de l'interface d'édition pour afficher ici un aperçu de votre texte.<br />
Version actuelle :</p>
<div class="omnilogisme">
<?php Template::put('Apercu') ?>
</div>
</div>

<script type="text/javascript" src="/images/lock.js"></script>
