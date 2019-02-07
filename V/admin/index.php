<?php
/**
* Vue : admin/
* Layout : toutes les sections d'administration disponibles
*/

?>
<h3><span>Page d'accueil de l'administration</span></h3>
<blockquote><p>Un grand pouvoir implique de grandes responsabilités. </p></blockquote>
<?php echo Formatting::makeList($C['TOC'],'ul','admin_actions'); ?>

<?php
foreach($C['Sections'] as $ID=>$Section)
{?>
<hr />
</section>

<section>
<h3 id="<?php echo $ID; ?>"><span><?php echo $Section['Titre'] . (count($Section['Articles'])>1?' <small>(' . count($Section['Articles']) . ')</small>':'') ?></span></h3>

<?php
if(!isset($Section['HTML']))
	echo Admin::getVue($Section['Articles'],$Section['Vue'],$Section['Max'],$Section['Action'],$Section['IsFileForm'],$Section['SubmitCaption']);
else
	echo $Section['HTML'];
}