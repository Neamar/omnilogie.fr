<?php
/**
* Layout :
* - Article
* Menu : répartition par statut
*/
//Afficher les articles

?>

<p id="member-actions">
<?php if(!isset($_SESSION['Membre']['ID']))
{ ?>
Bienvenue sur Omnilogie.fr ! <a href="/membres/Inscription">Devenez rédacteur !</a> <a href="/membres/Connexion">Connexion</a>
<?php
}
else
{
?>
<a href="/membres/Connexion"><img src="/CSS/img/deco.png" alt="Déconnexion" />	</a> <a href="/membres/"><?php echo $_SESSION['Membre']['Pseudo']; ?></a> <a href="/membres/Redaction">Écrire un nouvel article</a>
<?php
if(Member::is($_SESSION['Membre']['Pseudo'],'any')){ ?><a href="/admin/" class="admin">Admin</a><?php }
}
?>
</p>

<p id="social"><a href="http://twitter.com/Omnilogie"><img class="Top_l" alt="Twitter" title="Suivez @Omnilogie sur Twitter !" src="/CSS/img/twitter.png" width="32" height="31" /></a><a href="http://feeds.feedburner.com/Omnilogie"><img class="Top_l" alt="RSS" title="Restez informés des derniers articles avec le flux RSS !" src="/CSS/img/rss.png" width="32" height="31" /></a></p>

<?php if(!isset($_SESSION['Membre']['ID']))
{ ?>
<p id="next-article">Prochain omnilogisme : <time datetime="<?php Template::put('nextArticleDateTime','Snippet'); ?>"><?php Template::put('nextArticle','Snippet'); ?></time></p>
<?php
}
else
{
	$style = '';
	if($C['Snippet']['toBePublished'] < 3)
		$style=' style="color:red;"';
?>
	<p id="next-article"<?php echo $style; ?>>Articles dans la file d'attente de parution : <?php Template::put('toBePublished','Snippet'); ?></p>
<?php
}
