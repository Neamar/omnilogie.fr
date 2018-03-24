<?php
/**
* Vue : membres/Redaction
* Layout : Titre,
* Article
* Anecdote
* Liens
* En r�alisation de la proposition
*
*/
?>

<h3 id="h3_redaction"><span>R�daction d'article</span></h3>
<p>Vous voil� sur la page de r�daction.<br />
Pour �crire un article, il faut sp�cifier son titre, une phrase de pr�sentation, puis l'article en lui-m�me.
Si vous utilisez des sources externes (URL ou livres) n'h�sitez pas � les citer pour que le lecteur interess� puisse approfondir ce que vous lui apprenez.</p>
</section>

<section>
<h3 id="h3_redaction_form"><span>Formulaire de cr�ation</span></h3>
<form method="post" action="" id="ajout">
<label for="titre">Titre :</label><small> Le titre peut �tre humoristique, jouer sur les mots ou ce qui vous pla�t &ndash; mais essayez de rester relativement fid�le au contenu de votre article ! La r�gle ? Une fois l'article lu, le titre doit �tre pleinement compr�hensible.</small><br />
<input type="text" name="titre" id="titre" value="<?php Template::put('titre','Valeurs') ?>" maxlength="90" /><br />

<hr />

<label for="article">Article :</label>
<small>Le champ le plus important : votre article ! Utilisez votre style d'�criture, mais respectez la langue fran�aise.<br />
Si votre article d�passe deux �crans de texte dans l'aper�u, il est temps de conclure !</small>
<br />
<?php
Typo::setTexte($C['Valeurs']['article']);
Typo::renderIDE(array('Name'=>'article','Rows'=>17,'Preview'=>array('URL'=>'/membres/Apercu','ID'=>'Typo_preview')));
?>
<hr />

<p>Aper�u de votre texte :</p>
<div id="Typo_preview">
Cliquez sur l'icone <img src="https://neamar.fr/lib/markitup/sets/Typo/images/preview.png" alt="Fl�che" class="TexPic"> au dessus de l'interface d'�dition pour afficher ici un aper�u de votre texte.
</div>

<hr />

<label for="sources">Sources :</label>
<small>Entrez ici une liste de sources ; chaque source doit �tre s�par�e de la pr�c�dente par un appui sur la touche "Entr�e". Les sources peuvent-�tre des liens ou des r�f�rences de livres ou publications.</small><br />
<textarea cols="25" rows="5" name="sources" id="sources"><?php Template::put('sources','Valeurs') ?>
</textarea>

<hr />

<?php
if(count($C['Propositions'])!=0) { ?>
<p>Cet article est la r�alisation d'une proposition r�serv�e :</p>
<input type="radio" name="proposition" value="no" id="no" <?php if($C['Valeurs']['prop-id']=='no'){?>checked="checked"<?php }?> /><label for="no">Aucune proposition</label><br />
<?php foreach($C['Propositions'] as $ID=>$Text){?>
	<input type="radio" name="proposition" value="<?php echo $ID ?>" id="prop-<?php echo $ID ?>" <?php if($C['Valeurs']['prop-id']==$ID){?>checked="checked"<?php }?>/><label for="prop-<?php echo $ID ?>"><?php echo $Text ?></label><br />
<?php } ?>
<hr />
<?php } ?>

<p>C'est tout bon ? Cliquez ici pour enregistrer l'article. Vous pourrez encore l'�diter � loisir apr�s !</p>
<p><input type="checkbox" name="brouillon" id="brouillon" /> <label for="brouillon">Cet article est encore au stade d'�bauche, ne pas le faire para�tre.</label></p>
<input type="submit" value="Cr�er l'article" onclick="this.value='Enregistrement en cours...'; window.onbeforeunload=function(){};"/>
</form>

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

inits.push(function(){document.getElementById('ajout').onsubmit = checkTitle;});

window.onbeforeunload=function()
{
	if(document.getElementsByTagName('textarea')[0].value.length>0)
		return "Votre omnilogisme n'a pas �t� enregistr�. En quittant, vous perdrez toute modification...";
};

</script>
