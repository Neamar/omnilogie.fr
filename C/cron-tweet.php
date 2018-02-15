<?php
if($_GET['code']==getenv("ACCESS_TOKEN_SECRET")) {
  $RandomParams=Omni::buildParam();
  $RandomParams->Where = '!ISNULL(Sortie) AND Accroche <> "" AND Titre NOT LIKE "Top article%"';
  $RandomParams->Order = 'RAND()';
  $RandomParams->Limit = '1';
  $RandomArticle=Omni::getSingle($RandomParams);

  $texte = $RandomArticle->Accroche;
  $texte .= " https://omnilogie.fr" . Link::omni($RandomArticle->Titre);
  External::tweet($texte);
  echo $texte;
} else {
  echo "ENOD";
}

die();
