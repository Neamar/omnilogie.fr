<?php
/**
* Vue : membres/Edit
* Layout : Titre,
* Article
* Liens
* Brouillon
*/
?>

<h3><span>Modification d'article</span></h3>
<p>Vous voil� sur la page de correction.<br />
Vous pouvez modifier votre article autant de fois que n�cessaire, et ce tant qu'il n'est pas paru.<br />
N'oubliez pas l'aper�u, juste en dessous de votre texte !</p>
<p>Lien vers l'article tel qu'il sera affich� : <?php Template::put('LienDirect'); ?></p>
</section>

<section>
<h3><span>Formulaire de correction</span></h3>
<form method="post" action="" id="edition" enctype="multipart/form-data">
<label for="titre">Titre :</label><br />
<input type="text" name="titre" id="titre" value="<?php Template::put('titre','Valeurs') ?>" maxlength="90" /><br />

<hr />

<?php
Typo::setTexte($C['Valeurs']['article']);
Typo::renderIDE(array('Name'=>'article','Rows'=>17,'Preview'=>array('URL'=>'/membres/Apercu','ID'=>'Typo_preview')));
?><br />
<input type="submit" value="Enregistrer les changements" id="save_change"/>
<hr />

<p>Aper�u de votre texte :</p>
<div id="Typo_preview">
<p>Cliquez sur l'icone <img src="http://neamar.fr/lib/markitup/sets/Typo/images/preview.png" alt="Fl�che" class="TexPic"> au dessus de l'interface d'�dition pour afficher ici un aper�u de votre texte.<br />
Version actuelle :</p>
<div class="omnilogisme">
<?php Template::put('Apercu') ?>
</div>
</div>

<hr />

<label for="sources">Sources :</label><br />
<textarea cols="25" rows="5" name="sources" id="sources"><?php Template::put('sources','Valeurs') ?>
</textarea>

<hr />

<label for="banniere">Banni�re de l'article :</label> <small>Si vous ne souhaitez pas d�finir de banni�re ou conserver la banni�re actuelle, laissez ce champ vide. <strong>Attention : l'image doit �tre au format PNG et mesurer 690x95</strong></small>.<br />
<input type="file" name="banniere-<?php echo $C['Valeurs']['id'] ?>" id="banniere" />

<hr />

<p>C'est tout bon ? Cliquez ici pour enregistrer vos modifications.</p>
<p><input type="checkbox" name="brouillon" id="brouillon" <?php Template::put('brouillon','Valeurs') ?>/> <label for="brouillon">Cet article est encore au stade d'�bauche, ne pas le faire para�tre.</label></p>
<input type="submit" value="Enregistrer les changements" id="save_change"/>
</form>

<script type="text/javascript" src="/images/lock.js"></script>
<script type="text/javascript">
function checkTitle()
{
	var Titre = document.getElementById('titre').value;
	if(Titre.indexOf('?') !=-1 && Titre.indexOf('?')!=Titre.length-1)
	{
		alert("Le titre d'un article ne doit pas comporter de points d'interrogation. Une exception est tol�r�e si le point d'interrogation se situe en toute fin de l'article");
		return false;
	}
}

inits.push(function(){document.getElementById('edition').onsubmit = checkTitle;});
</script>