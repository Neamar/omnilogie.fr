<?php
/**
* Vue : membres/index
* Layout : Lien écrire un article
* Lien stats.
* Lien page membre publique
* Liste des propositions réservées
* Articles modifiables
*
*
*/
?>
<h3><span>Accueil de l'espace membre</span></h3>
<h1><?php echo AUTHOR; ?></h1>

<p>Bienvenue sur votre espace membre !<br />
Depuis cette page vous pouvez contrôler votre profil, rédiger un nouvel omnilogisme et accéder à toutes les fonctions disponibles aux membres. N'oubliez pas le menu à droite qui contient des liens utiles aussi !</p>

<nav id="redactionArticle">
<h2>Rédaction d'un article</h2>
<p><a href="/membres/Redaction">Rédiger un nouvel article</a>.</p>
</nav>

<nav id="pageMembrePublique">
<h2>Page membre publique</h2>
<p><?php echo Anchor::author(AUTHOR) ?></p>
</nav>

<nav id="statistiquesMembre">
<h2>Statistiques sur vos articles</h2>
<p><a href="/membres/Stats">Infos et chiffres</a>.</p>
</nav>

<h2>Liste des propositions réservées</h2>
<p>Cette liste contient les <a href="/membres/Propositions">propositions</a> que vous avez réservées et pas encore rédigées. <small>Pour écrire un article répondant à une des propositions ci-dessous, cliquez sur "<a href="/membres/Redaction">rédiger un nouvel article</a>" puis cochez la case associée à la proposition</small>.</p>

<?php echo Formatting::makeList($C['Propositions'])?>

<h2>Articles éditables</h2>
<?php echo Formatting::makeList($C['Editables']) ?>


<h2>Modification des données membres</h2>
<form method="post" action="">
<label for="auteur">Pseudonyme :</label><br />
<input type="text" name="auteur" id="auteur" value="<?php Template::put('Auteur','MembreData') ?>" /><br />

<label for="password">Mot de passe : <small>Laissez vide pour garder l'ancien.</small></label><br />
<input type="password" name="password" id="password" value="" /><br />

<label for="password2">Mot de passe (confirmez) : <small>Laissez vide pour garder l'ancien.</small></label><br />
<input type="password" name="password2" id="password2" value="" /><br />

<label for="adsense">Référence éditeur Google Adsense : <small>Cet identifiant vous permet de gagner de l'argent avec vos articles. Consultez <a href="http://omnilogie.fr/Ligne#ligne-publicite">cette page pour plus de détails</a>.<br />Laissez vide si vous ne souhaitez pas l'utiliser.</small></label><br />
<input type="text" name="adsense" id="adsense" value="<?php Template::put('Adsense','MembreData') ?>" placeholder="pub-9999999999999999" /><br />

<label for="googleplus">Profil Google+ : <small>Cet identifiant vous permet d'afficher votre image en face des recherches pour vos articles sur Google.  Consultez <a href="http://omnilogie.fr/Ligne#ligne-plus">cette page pour plus de détails</a>.<br />Laissez vide si vous ne souhaitez pas l'utiliser.</small></label><br />
<input type="text" name="googleplus" id="googeplus" value="<?php Template::put('GooglePlus','MembreData') ?>" placeholder="https://plus.google.com/999999999999999999999"/><br />

<label for="mail">Mail :</label><br />
<input type="text" name="mail" id="mail"  value="<?php Template::put('Mail','MembreData') ?>" /><br />

<label for="dernierMail">Afficher ce mail publiquement sur ma page membre :</label><br />
<input type="radio" name="public" value="oui" id="oui" <?php if($C['MembreData']['MailPublic']=='oui') echo 'checked="checked"'; ?> /> <label for="oui">Oui. Votre mail sera alors affiché sous forme d'image, et seuls des humains pourront vous envoyer un mail.</label><br />
<input type="radio" name="public" value="non" id="non" <?php if($C['MembreData']['MailPublic']=='non') echo 'checked="checked"'; ?> /> <label for="non">Non. Personne ne pourra vous joindre, à l'exception des administrateurs et du robot mailer du site d'omnilogie.</label><br />
<br />

<label for="dernierMail">Dernier mail recu :</label><br />
<input type="text" name="dernierMail" id="dernierMail" disabled="disabled" value="<?php Template::put('DernierMail','MembreData') ?>" /><br />

<label for="histoire">Histoire :</label> <small>(Qui êtes vous ? Pourquoi participez-vous ? Quel goût a la soupe au choux ? Comptez vos genoux ! Bref, parlez de ce que vous voulez : ce texte s'affichera sur votre page de membre. Vous pouvez utiliser les mêmes balises que lors de la rédaction d'un omnilogisme)</small>.<br /><span style="color:red;">Ce champ ne sert pas à marquer un omnilogisme ! Utilisez <a href="/membres/Redaction">ce lien</a> pour créer un nouvel article.</span><br />
<?php

Typo::setTexte($C['MembreData']['Histoire']==''?DEFAUT_PRESENTATION:$C['MembreData']['Histoire']);
Typo::renderIDE(array('Name'=>'histoire','Rows'=>10));
?>
<br />
<input type="submit" value="Enregistrer les changements" />
</form>