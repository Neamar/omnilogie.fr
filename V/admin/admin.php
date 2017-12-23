<?php
/**
* Vue : admin/admin
* Layout : Statut, Titre
*/

?>

<h3><span>Administration d'article</span></h3>

<?php echo Admin::getVue($C['Articles'],array('Admin','adminVueCallback')); ?>