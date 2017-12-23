<?php
//Permet la gestion de pods
//Chaque pod se conforme � la structure
/*
[id] => Array(
[Title] le titre du pod � afficher
[Content] le contenu
*/
if(isset($C['SpecialPods']))
	Debug::fail('Special Pods d�pr�ci�s.');

foreach($C['Pods'] as $PodID=>$Pod)
{?>
<section id="<?php echo $PodID ?>" class="menu">
<h3><?php echo $Pod['Title'] ?></h3>
<?php echo $Pod['Content']; ?>
</section>
<?php
}
