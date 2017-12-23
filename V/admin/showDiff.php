<?php
/**
* Vue : admin/showDiff
* Layout : toutes les sauvegardes de l'article disponibles
*/

?>

<h3><span>Logs d'un article</span></h3>
<p>Article : <?php echo Anchor::Omni($C['Article']) ?></p>
<?php echo Formatting::makeList($C['TOC']); ?>

<?php
if(!isset($C['Original']['Date']))
{?><p>Rien à afficher.</p><?php return; }?>

<p>Version comparée : <?php echo Template::put('Date','Original');?> par <?php echo Template::put('Auteur','Original');?></p>

<?php
foreach($C['Sections'] as &$Section)
{?>
<hr />
</section>

<section>
<h3 id="log-<?php echo $Section['ID']; ?>"><span><small><?php echo $Section['Date'] ?></small> par <?php echo $Section['Auteur'] ?></span></h3>
<?php echo $Section['Header'];
echo $Section['Content'];
}