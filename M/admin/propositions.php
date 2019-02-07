<?php
/**
* ModÃ¨le : admin/propositions
* But : administrer les propositions
* Page spÃ©ciale directement basÃ©e sur EasySQL.
*/

$C['PageTitle']='Administration des propositions';
$C['CanonicalURL']='/admin/Propositions';

define('EASYSQL_PREFIXE','OMNI_');
define('EASYSQL_WORKTABLE','Propositions');
include(LIB_PATH . '/EasySQL2.php');

EasySQL::setMeta('FKCAPTION',array(EASYSQL_WORKTABLE=>array('AjoutPar'=>'Auteurs.Auteur','ReservePar'=>'Auteurs.Auteur','OmniID'=>'Omnilogismes.Titre')));

$C['EasySQL'] = new EasySQL(EASYSQL_WORKTABLE);

echo '<p><a href="/admin/">Retour administration</a></p>';
$C['EasySQL']->getAdminInterface(array('UPDATE','DELETE'));

exit();