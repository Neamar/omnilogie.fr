<section id="lastArticles" class="menu">
<h3>Derniers articles parus</h3>

<?php
echo Formatting::makeList(Omni::getTrailers($C['Menus']['lastArticles']));
?>
</section>
