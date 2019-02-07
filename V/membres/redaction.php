<?php
/**
* Vue : membres/Redaction
* Layout : Titre,
* Article
* Anecdote
* Liens
* En réalisation de la proposition
*
*/
?>

<h3 id="h3_redaction"><span>Rédaction d'article</span></h3>
<p>Vous voilà sur la page de rédaction.<br />
Pour écrire un article, il faut spécifier son titre, une phrase de présentation, puis l'article en lui-même.
Si vous utilisez des sources externes (URL ou livres) n'hésitez pas à les citer pour que le lecteur interessé puisse approfondir ce que vous lui apprenez.</p>
</section>

<section>
<h3 id="h3_redaction_form"><span>Formulaire de création</span></h3>
<form method="post" action="" id="ajout">
<label for="titre">Titre :</label><small> Le titre peut être humoristique, jouer sur les mots ou ce qui vous plaît &ndash; mais essayez de rester relativement fidèle au contenu de votre article ! La règle ? Une fois l'article lu, le titre doit être pleinement compréhensible.</small><br />
<input type="text" name="titre" id="titre" value="<?php Template::put('titre','Valeurs') ?>" maxlength="90" /><br />

<hr />

<label for="article">Article :</label>
<small>Le champ le plus important : votre article ! Utilisez votre style d'écriture, mais respectez la langue française.<br />
Si votre article dépasse deux écrans de texte dans l'aperçu, il est temps de conclure !</small>
<br />
<?php
Typo::setTexte($C['Valeurs']['article']);
Typo::renderIDE(array('Name'=>'article','Rows'=>17,'Preview'=>array('URL'=>'/membres/Apercu','ID'=>'Typo_preview')));
?>
<hr />

<p>Aperçu de votre texte :</p>
<div id="Typo_preview">
Cliquez sur l'icone <img src="https://neamar.fr/lib/markitup/sets/Typo/images/preview.png" alt="Flèche" class="TexPic"> au dessus de l'interface d'édition pour afficher ici un aperçu de votre texte.
</div>

<hr />

<label for="sources">Sources :</label>
<small>Entrez ici une liste de sources ; chaque source doit être séparée de la précédente par un appui sur la touche "Entrée". Les sources peuvent-être des liens ou des références de livres ou publications.</small><br />
<textarea cols="25" rows="5" name="sources" id="sources"><?php Template::put('sources','Valeurs') ?>
</textarea>

<hr />

<?php
if(count($C['Propositions'])!=0) { ?>
<p>Cet article est la réalisation d'une proposition réservée :</p>
<input type="radio" name="proposition" value="no" id="no" <?php if($C['Valeurs']['prop-id']=='no'){?>checked="checked"<?php }?> /><label for="no">Aucune proposition</label><br />
<?php foreach($C['Propositions'] as $ID=>$Text){?>
	<input type="radio" name="proposition" value="<?php echo $ID ?>" id="prop-<?php echo $ID ?>" <?php if($C['Valeurs']['prop-id']==$ID){?>checked="checked"<?php }?>/><label for="prop-<?php echo $ID ?>"><?php echo $Text ?></label><br />
<?php } ?>
<hr />
<?php } ?>

<p>C'est tout bon ? Cliquez ici pour enregistrer l'article. Vous pourrez encore l'éditer à loisir après !</p>
<p><input type="checkbox" name="brouillon" id="brouillon" /> <label for="brouillon">Cet article est encore au stade d'ébauche, ne pas le faire paraître.</label></p>
<input type="submit" value="Créer l'article" onclick="this.value='Enregistrement en cours...'; window.onbeforeunload=function(){};"/>
</form>

<script type="text/javascript">
function checkTitle()
{
	var Titre = document.getElementById('titre').value;
	if(Titre.indexOf('?') !=-1 && Titre.indexOf('?')!=Titre.length-1)
	{
		alert("Le titre d'un article ne doit pas comporter de points d'interrogation. Une exception est tolérée si le point d'interrogation se situe en toute fin de l'article");
		return false;
	}
}

inits.push(function(){document.getElementById('ajout').onsubmit = checkTitle;});

window.onbeforeunload=function()
{
	if(document.getElementsByTagName('textarea')[0].value.length>0)
		return "Votre omnilogisme n'a pas été enregistré. En quittant, vous perdrez toute modification...";
};

</script>
