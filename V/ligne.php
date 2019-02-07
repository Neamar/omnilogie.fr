<?php
/**
* Vue : Ligne
* Layout :
* - Informations pour les lecteurs
* - Informations pour les auteurs
* - Informations pour les administrateurs
*/
?>

<h3><span>Quelques mots sur le fonctionnement du site&hellip;</span></h3>

<ul>
	<li><a href="#ligne-lecteurs">Informations pour les lecteurs</a></li>
	<li><a href="#ligne-auteurs">Informations pour les auteurs</a></li>
	<li><a href="#ligne-publicite">Informations pour la publicité</a></li>
	<li><a href="#ligne-plus">Informations pour la recherche</a></li>
	<li><a href="#ligne-administrateurs">Informations sur les administrateurs</a></li>
	<li><a href="#ligne-copyright">Informations sur la propriété intellectuelle</a></li>
</ul>

<style>
#content-g2 a {
	text-decoration: underline;
}
</style>

<p>Ce qui suit est un recueil de textes explicatifs &ndash; le style de chaque texte est différent, ne s'adressant pas à la même personne.</p>
</section>

<section>
<h3 id="ligne-lecteurs"><span>Informations pour les lecteurs</span></h3>
<?php Template::put('Lecteurs'); ?>
</section>

<section>
<h3 id="ligne-auteurs"><span>Informations pour les auteurs</span></h3>
<?php Template::put('Auteurs'); ?>
</section>

<section>
<h3 id="ligne-publicite"><span>Informations sur la publicité</span></h3>
<?php Template::put('Pub'); ?>
</section>

<section>
<h3 id="ligne-plus"><span>Informations sur les recherches</span></h3>
<?php Template::put('Plus'); ?>
</section>

<section>
<h3 id="ligne-administrateurs"><span>Informations pour les administrateurs</span></h3>
<?php Template::put('Administrateurs'); ?>
</section>

<section>
<h3 id="ligne-copyright"><span>Informations sur la propriété intellectuelle</span></h3>
<?php Template::put('Licence'); ?>
</section>