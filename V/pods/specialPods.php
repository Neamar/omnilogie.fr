<?php
//Permet la gestion de pods spéciaux et différants selon les pages.
//Par exemple, les articles dans une saga quand on affiche la saga.

if(isset($C['SpecialPods']))
{
	foreach($C['SpecialPods'] as $Pod)
	{?>
<section id="<?php echo $Pod['PodID'] ?>" class="menu">
<h3><?php echo $Pod['PodTitle'] ?></h3>
<?php echo $Pod['Content']; ?>
</section>
	<?php
	}
}