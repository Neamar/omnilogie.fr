<section id="twitter" class="menu">
<h3>Twitter en direct</h3>

<?php
//Simplifier l'écriture
$T = $C['Menus']['twitter'];
?>

<p><a href="http://twitter.com/<?php echo $T['User'] ?>"><?php echo $T['User'] ?></a> <?php echo $T['Tweet'] ?><br />

<small>Post� <a href="http://twitter.com/<?php echo $T['User'] ?>/status/<?php echo $T['ID'] ?>">il y a <?php echo $T['Ecart'] ?> heure<?php echo ($T['Ecart']>1?'s':'')?></a>.</small></p>
</section>
