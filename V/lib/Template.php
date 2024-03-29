<!DOCTYPE html>
<html lang="fr">
<head>
	<link rel="alternate" type="application/rss+xml" title="Flux RSS des articles" href="/flux.rss" />
	<link rel="stylesheet" media="all" href="/CSS/omni.responsive.min.css?v5514" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<title><?php Template::put('PageTitle') ?></title>

	<link rel="canonical" href="https://omnilogie.fr<?php Template::put('CanonicalURL') ?>" />
	<script async defer data-domain="omnilogie.fr" src="https://t.neamar.fr/js/pls.js"></script>
	<script>window.plausible = window.plausible || function() { (window.plausible.q = window.plausible.q || []).push(arguments) }</script>

	<script type="text/javascript" src="/images/script.js" async></script>
	<script type="text/x-mathjax-config">
	MathJax.Hub.Config({
		tex2jax: {inlineMath: [['\\(','\\)']]}
	});
	</script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.2/MathJax.js?config=TeX-MML-AM_CHTML" async></script>
	<?php echo implode("\n", $C['head']); ?>

	<?php
	if(isset($C['OpenGraph'])) {
		foreach($C['OpenGraph'] as $property => $content) {
			echo '<meta property="' . $property . '" content="' . $content . '" />' . "\n";
		}
	}
	?>
</head>
<body>
<!--En-tête du site-->
<div id="fond-top">

	<div id="fond-top-c">
<div id="top">
<header role="banner" id="banner">
		<a href="/">
			<div id="logo">
				<p>Omnilogie.fr</p>
				<p>Le manuel des castors seniors</p>
			</div>
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
		<form action="//www.google.fr/cse" id="cse-search-box" role="search" accept-charset="utf-8">
			<input type="hidden" name="cx" value="partner-pub-4506683949348156:5njwqc-hgy2" />
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
			<p id="ribbon"><small><a href="/Ligne" accesskey="8">Ligne éditoriale</a> | <a href="/Contact" accesskey="7">Contact</a> | <a href="/flux.rss">RSS</a></small></p>
		</div>
	</div>
</footer>
</body>
</html>
<?php flush() ?>
