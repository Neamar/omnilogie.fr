<?php
/**
* Vue : contact
* Layout : PrÃ©sentation / Formulaire
*
*/
?>

<h3><span>Contact</span></h3>

<!-- Vous pouvez aussi utiliser contact@omnilogie.fr -->

<p>Besoin d'entrer en contact avec les administrateurs du site ? Utilisez ce formulaire pour signaler une erreur sur un article, une faute, un probl&egrave;me, un bug, une suggestion, un partenariat, votre admiration sans bornes, votre haine, ou tout ce qui vous passe par la t&ecirc;te vaguement en rapport avec Omnilogie.</p>

<form method="post" action="https://neamar.fr/email.php" accept-charset="utf-8" onsubmit="document.getElementById('protection').value = 2">
	<p><label for="mail">Votre e-mail :</label>
	<input type="email" name="_replyto" id="mail" autofocus required/></p>
	<input type="hidden" name="_to" value="neamar@neamar.fr" />
	<input type="hidden" name="protection" id="protection" value="1" />
	<p><label for="titre">Titre :</label>
	<input type="text" name="_subject" id="titre" value="<?php echo (isset($_POST['titre'])?$_POST['titre']:'') ?>" /></p>

	<p><label for="message">Message :</label><br />
	<textarea cols="25" rows="7" id="message" name="message" required style="width:90%"><?php echo (isset($_POST['message'])?$_POST['message']:'') ?></textarea></p>

	<p><input type="submit" value="Envoyer le message" /></p>
</form>
