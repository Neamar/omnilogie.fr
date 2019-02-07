<?php
/**
* Layout :
* - Code Erreur
* - Explication
* Aller plus loin
* Menu : répartition par statut
*/
//Afficher les articles

?>
<h3 id="h3_erreur"><span>Erreur <?php Template::put('CodeErreur');?></span></h3>
<p>Oh non ! Une erreur s'est produite en essayant de charger la page.</p>

<header class="message">
<?php Template::put('Erreur');?>
</header>

<hr />
<p>Mais tout n'est pas perdu ! Pourquoi ne pas recommencer à naviguer depuis ces liens ?</p>
<?php echo Formatting::makeList($C['Liens']); ?>

<p>Si vous tenez vraiment à trouver cette page, utilisez l'outil de recherche dans le coin en haut à droite ;)</p>