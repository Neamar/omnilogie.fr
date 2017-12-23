<?php
//Permet la gestion des footers
//Chaque footer se conforme à la structure
/*
[id] => Array(
[Title] le titre du footer à afficher
[Content] le contenu
*/


foreach($C['Footers'] as $PodID=>$Pod)
{?>
<section id="<?php echo $PodID ?>" class="footer">
<h3><?php echo $Pod['Title'] ?></h3>
<?php echo $Pod['Content']; ?>
</section>
<?php
}
