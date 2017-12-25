<!DOCTYPE html>
<html>
<head>
	<meta charset="iso-8859-1" />
	<link rel="alternate" type="application/rss+xml" title="Flux RSS des articles" href="/flux.rss" />
  	<link rel="stylesheet" media="all" href="/CSS/omni<?php if(!isset($_SESSION['Membre']['Pseudo']) || $_SESSION['Membre']['Pseudo']!='Licoti'){ echo '.min'; }?>.css" />

	<title><?php Template::put('PageTitle') ?></title>
	<!--[if IE]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link rel="canonical" href="https://omnilogie.fr<?php Template::put('CanonicalURL') ?>" />
	<script type="text/javascript">
	var inits=[];
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-4257957-3']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
	</script>
	<script type="text/javascript" src="/images/script.js" defer="defer"></script>
	<?php echo implode("\n", $C['head']); ?>
</head>
<body>
<!--En-tête du site-->
<div id="fond-top">

	<div id="fond-top-c">
<div id="top">
<header role="banner" id="banner">
    <a href="/">
	<hgroup id="logo">
	  <h1>Omnilogie.fr</h1>
	  <h2>Le manuel des castors seniors</h2>
	</hgroup>
    </a>
</header>
<?php flush() ?>

<nav id="bar">
	<?php Template::includeFile('pods/topBar.php'); ?>
</nav>

<nav id="menu">
	<ul>
		<li id="accueil"><a title="Accueil" accesskey="1" href="/">Accueil <span>Les derniers articles parus</span></a></li>
		<li id="article"><a title="Liste des articles" accesskey="2" href="/O/">Articles <span>La liste des articles parus sur le site</span></a></li>
		<li id="categorie"><a title="Liste des catégories" href="/Liste/"  accesskey="3">Catégories <span>Liste des catégories d'articles</span></a></li>
		<li id="tops"><a title="Best-of Omnilogie" href="/Top">Top <span>Sélection des meilleurs articles</span></a></li>
	</ul>
</nav>

<!--Outil de recherche-->
<div id="zoom">
</div>
  <nav id="search">
<form action="//www.google.fr/cse" id="cse-search-box" role="search">
	<input type="hidden" name="cx" value="partner-pub-4506683949348156:5njwqc-hgy2" />

	<input type="hidden" name="ie" value="ISO-8859-1" />
	<input type="text" name="q" size="20" placeholder="Rechercher" accesskey="4" />
	<input type="submit" name="sa" value="Go !" />
</form>
  </nav>
</div><!--fin du top-->
	</div>
</div>

<div id="global-f">
<div id="global"><!-- page principale -->
<!--Les menus de navigation-->
<aside id="menus">
	<?php Template::includeFile('lib/Pods.php'); ?>
  <hr id="end_content2" />
</aside>
</div><!-- fin global-f -->
<?php flush() ?>

<div id="content-g2"><!-- englobe toutes les sections -->
<?php if(isset($C['Message'])) {?><aside class="<?php echo (isset($C['MessageClass'])?$C['MessageClass']:'erreur') ?>"><?php Template::put('Message'); ?></aside><?php } ?>
<section id="content">
	<div id="fond-cont"></div>
	<?php Template::includeView(); ?>
</section>
<hr id="end_content" />
</div><!-- fin content-g2 -->
</div><!-- fin global -->

<footer>
 <div id="footer-f"></div>
  <div id="footer-g">
	<aside id="footers">
		<?php Template::includeFile('lib/Footers.php'); ?>
	</aside>
    <div id="bas">
		<p id="ribbon"><small><a href="/Ligne" accesskey="8">Ligne éditoriale</a> | <a href="/Contact" accesskey="7">Contact</a> | <a href="http://omnilogie.fr/flux.rss">RSS</a> | <a href="/Mail">Recevoir l'article par mail</a> | <a href="/App">Applications smartphone Omnilogie</a></small></p>
		<p id="html5-valid">HTML5 valid</p>
    </div>
  </div>
</footer>

</body>
</html>
<?php flush() ?>
