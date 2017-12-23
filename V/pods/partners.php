<section id="partners" class="footer">
<h3>Partenaires</h3>
<ul>
<?php
foreach($C['Menus']['partners'] as $URL=>$Caption)
	echo '<li><a href="' . $URL . '">' . $Caption . '</a></li>' . "\n";
?>
</ul>
</section>
