<section id="catCloud" class="menu">
<h3>Nuage de cat�gories</h3>

<?php
foreach($C['Menus']['catCloud'] as $Category)
	echo '<span style="font-size:' . $Category['Size'] . '">' . $Category['Link'] . '</span> ';
?>
</section>
