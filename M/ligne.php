<?php
/**
* Modèle : ligne
* But : Afficher ligne éditoriale, disclaimer, etc.
* Données à charger :
*	- Pour les lecteurs
*	- Pour les auteurs
*/

$C['PageTitle']='Précisions concernant le site Omnilogie.fr : ses articles, ses lecteurs, ses auteurs.';
$C['CanonicalURL']='/Ligne';

Typo::addOption(ALLOW_SECTIONING);//Autoriser les balises de titres pour cette page.

$Lecteurs =<<<LECTEUR
\section{Contenu}

Le contenu proposé sur le site \l[/]{Omnilogie} est à utiliser avec précaution.
Ce contenu reflète la pensée de ses auteurs, qui ne satisfait pas forcément à un point de vue neutre. Omnilogie et ses responsables se dédient de toute responsabilité quant aux opinions personnelles ou politiques exprimées par les membres de façon implicite et explicite.
De même, les informations techniques / scientifiques / ethnologiques / ... exprimées dans les articles ne sauraient être tenues pour correctes sans vérification appropriée ; même si vous pouvez partir avec un à priori positif, ces informations sont là pour vous instruire et n'ont pas force de loi. Le contenu des articles est normalement validé tout au long de son parcours dans la chaîne administrative, mais il reste possible qu'une erreur technique se soit glissée dans l'omnilogisme.

\section{Contenant}

Nous essayons de minimiser les fautes d'orthographe, mais il reste possible que certaines se glissent à travers les mailles du filet.

Merci de signaler toutes fautes d'orthographe ou de fond que vous rencontreriez via le \l[/Contact]{formulaire de contact}.
LECTEUR;

$Auteurs = <<<AUTEURS
Avant de rédiger vos omnilogismes, voici quelques conseils à respecter pour le bien de tous.
Le non-respect de ces consignes pourra entraîner le refus de vos articles.

\section{Concernant le fond}

De préférence, votre article doit traiter d'un sujet intéressant, culturellement enrichissant. Évitez les légendes urbaines (le mot OK vient de 0 Kill, Fuck signifie Fornication under consent of the king), résidus de sagesse populaire dont le seul intérêt est de montrer à quel point le peuple est "sage".

Ne vous spécialisez pas dans un domaine : restez polyvalent, et essayez de ne pas écrire tous vos articles sur un même thème. Si vous vous spécialisiez, vous seriez forcés d'entrer dans des détails pointus pour vos articles, ce qui n'est ni le but, ni la motivation de ce site.
Vous pouvez, en revanche, créer une mini-série de quelques articles sur un même sujet, à condition d'apporter à chaque fois un élément nouveau. Les taggers se feront alors un plaisir de créer une nouvelle catégorie pour votre saga (si après plusieurs articles, votre catégorie n'a toujours pas été créée, n'hésitez pas à \l[/Contact]{contacter les administrateurs}. Vous pouvez aussi aider les taggers en faisant précéder votre article d'un titre explicite, tel que "Les armes à feu (1)  Le pistolet et le revolver", "Les armes à feu (2)  La carabine et le fusil").

Essayez de ne pas dépasser la limite d'une page par article : des articles trop longs sont signe d'un trop grand approfondissement du sujet, ou de multiples sujets traités en une seule fois : fractionnez alors votre article, quitte à en créer une mini série.
Il n'y a pas de limite minimale : tentez tout de même d'écrire plus de deux phrases, sinon il s'agit probablement d'une anecdote qui a plus sa place dans les liens "le saviez-vous" que dans les articles.
Ni trop peu, ni pas assez : à vous de trouver le juste milieu selon votre sujet.

\section{À propos du copier-coller}

Le copier-coller est à bannir des articles. Reformulez, élaguez, simplifiez ou recoupez vos informations, mais de grâce ne volez pas le travail des autres ! (même celui de Wikipedia : inspirez vous-en \i{à la rigueur}, mais ne recopiez pas). Sinon, quel intérêt ? Autant aller lire l'article sur le site original...
D'autant qu'Omnilogie n'est pas du tout là pour remplacer Wikipédia : là où Wikipédia se veut complète et exhaustive, Omnilogie tente de donner des anecdotes ou des petits faits croustillants qui n'ont pas leur place pour un article dédié de Wikipédia.

\section{Concernant la forme}
\subsubsection{Typographie}

Une grande partie du travail de mise en forme a été simplifiée : les espaces insécables sont placés automatiquement autour des signes de ponctuations appropriés, les ligatures (oe, AE) sont automatiques.
Les guillemets typographiques sont remplacés par des guillemets à la française, et les guillemets imbriqués sont gérés automatiquement s'ils sont placés correctement autour du mot (de "cette" façon, sans espace entre le symbole et le mot). De même pour les incises - ainsi - qui prennent la mise en forme demandée par le code typographique à condition d'être encadrée par des espaces. Vous trouverez plus de renseignements sur la page dédiée à la Typographie : \l[http://neamar.fr/Res/Typographe/]{du Typographe de Neamar}.
Il est cependant certaines choses qui ne peuvent être automatisées. Il en va ainsi des majuscules accentuées, et des fautes d'orthographe. Vous vous adressez à un public d'adultes, et en conséquent, le langage SMS ou phonétique est banni. Des fautes trop nombreuses dans le contenu d'un article peuvent entraîner son refus : les censeurs ne sont pas là pour traduire du kikoolol au français, mais pour mettre en forme l'article.

\subsubsection{Balises}

La \l[http://neamar.fr/Res/Typographe/Aide/]{liste des balises du Typographe} est disponible depuis toutes les interfaces de rédaction, en cliquant sur l'icone en forme de point d'interrogation. Afin de garder les articles concis, les balises de titres ont été bannies, en dehors de cela, vous pouvez utiliser tout ce qui aidera à clarifier votre message : note de bas de page, formule en LaTeX...

\section{Une fois posté}
\subsubsection{Refusé}

Si votre message est refusé, un mail vous en avertit, avec une copie de l'omnilogisme. Lisez bien ce mail, qui vous indiquera pour quelle raison votre texte n'a pas été accepté : il est fort probable que ce soit simplement parce qu'il est trop long, ou qu'il y a trop de fautes... il vous suffit alors de corriger votre article, et de le renvoyer.
Au moment de la rédaction de ces lignes, très peu d'articles ont été refusés définitivement ; et dans la majorité des cas il s'agit de doublons. Ne perdez donc pas espoir, votre article paraîtra un jour !

\subsubsection{Mal taggé}

Les taggers se chargent d'associer vos articles à différents catégories de l'\l[/Liste/]{arbre}. Si vous trouvez que les critères associés à l'article ne conviennent pas, \l[/Contact]{faites-le savoir}. Si vos raisons sont justifiées, les mots clés seront modifiés.

\subsubsection{Écrire plus pour cultiver plus}

Par défaut, vous recevez un rappel par mail une fois par mois (désactivable). Mais vous pouvez écrire à tout moment un omnilogisme en vous resservant du dernier mail reçu, ou en utilisant le lien permanent donné sur chaque mail pour l'écriture en ligne. Vos contributions sont toujours les bienvenues !

\section{Conseils}
\subsubsection{Le choix du sujet}

Vous venez de recevoir un mail vous incitant à écrire un omnilogisme ; ou vous souhaitez vous lancer dans l'aventure.
Ne cherchez pas un sujet "sur le coup" : laissez plutôt le mail dans un coin de votre boite mail, et attendez quelques 24 heures : il est fort probable que pendant ce laps de temps, vous aurez eu une conversation au cours de laquelle vous vous serez dit "Voilà qui ferait un article intéressant". Les articles trouvés de cette façon sont souvent de meilleure qualité que ceux pour lesquels on force le cerveau à trouver un sujet : ces derniers articles sont souvent trop "scolaires".

Bons omnilogismes à tous,

Neamar, pour les administrateurs.
AUTEURS;

$Administrateurs=<<<ADMINISTRATEURS
Le terme administrateur a deux sens sur Omnilogie : il désigne à la fois les membres ayant plus de pouvoir que les autres (c'est injuste, mais c'est la vie), et les membres disposant des supers pouvoirs - plus communément nommés admins.

Les administrateurs sont nombreux et ont des tâches distinctes : de la correction des fautes à l'ajout de bannières et d'anecdotes en passant par le taggage, il y en a pour tous les goûts !
Vous pouvez les reconnaître grâce à leur petit bandeau coloré apposé sur leurs noms.

Si vous souhaitez rejoindre leurs rangs, n'hésitez pas à postuler : on a toujours besoin de sang frais ! \l[/Contact]{Envoyez CV et lettre de motivation} (humour).
ADMINISTRATEURS;

$Licence=<<<LICENCE
\section{Avant parution}

Tant qu'un article n'est pas paru, l'auteur peut le modifier à sa guise. Notez cependant que toute modification remet le statut de l'omnilogisme sur "\\texttt{Indetermine}" : l'omnilogisme ne paraitra qu'une fois (re-)lu et validé par un administrateur.

Les censeurs et administrateurs s'efforcent de rendre les articles les plus agréables possibles : nous tentons d'effectuer les modifications les plus minimes pour garder intacte la pensée de l'auteur, mais dans certains cas nous modifions des phrases entières pour faciliter la lecture et la compréhension de l'article.
Si des données sont ajoutées ou supprimées (modification \b{plus importante qu'une simple reformulation}), l'auteur est contacté pour savoir s'il accepte les modifications.

\section{Après parution}

Une fois paru, les articles ne sont plus modifiables par leurs auteurs pour éviter l'affichage d'informations potentiellement incorrectes. Omnilogie.fr se réserve le droit de faire paraitre ces articles sur n'importe quel site ou support externe (livre...), mais s'engage à citer le pseudonyme de l'auteur pour toute reproduction intégrale de l'oeuvre. Si le contenu devait être utilisé autre part que sur un site, les auteurs concernés en seraient bien entendu avisés.

\section{Suppression}

L'auteur ou tout ayant droit peut demander la suppression d'un article : celle-ci sera effectuée le plus rapidement possible si les raisons de la suppression sont justifiées.

\section{Citer un article sur un site / blog...}

Vous pouvez citer tout ou partie d'un omnilogisme (et vous y êtes même invités !) sous condition de faire un lien vers la page du site que vous jugez la plus appropriée. La mention de l'auteur n'est obligatoire qu'en cas de recopie intégrale ou quasi-intégrale de l'article ; cependant essayez de ne pas l'oublier !

\section{Concernant les images}

Les images utilisées pour illustrer les omnilogismes ont été choisies par les membres. Vous pouvez à tout moment \l[/Contact]{effectuer une demande} pour supprimer une image du site si vous en possédez les droits.
LICENCE;

$Pub=<<<PUB
Omnilogie permet aux auteurs d'utiliser leur compte Google Adsense afin d'être indirectement rémunéré pour leurs articles.

\section{Adsense, c'est quoi ?}
Google Adsense est une plateforme publicitaire affichant des annonces ciblées avec le contenu. La rétribution se fait au clic, et le paiement a lieu tous les mois.

\section{Comment ça marche ?}
L'auteur indique dans son \l[/membres/]{espace membre sur Omnilogie} son identifiant Adsense, sous la forme \\texttt{\\verbatim{pub-9999999999999999}}.
Une fois ces données spécifiées, tous les articles du membre parus après le premier octobre 2011 afficheront une bannière publicitaire dont l'argent des clics sera directement reversée au compte Adsense spécifié.

\section{Comment m'inscrire ?}
Rendez-vous sur \l[https://www.google.com/adsense]{Google Adsense} et créez votre compte.
À l'issue de cette procédure, connectez-vous à Adsense : sur la page d'accueil, vous trouverez une "référence éditeur". Recopiez-là dans le champ approprié sur \l[/membres/]{votre espace membre}. Puis rédigez des omnilogismes et faites-les connaître !

\section{Combien ça rapporte ?}
Les conditions d'utilisation interdisent de donner des chiffres précis. Comptez entre 2 et 15 euros par article selon sa popularité (indication non contractuelle).

\section{Autour de la fonctionnalité...}
En cas d'abus, n'hésitez pas à nous \l[/Contact]{contacter}.
Vous pouvez à tout moment supprimer votre identifiant, auquel cas les publicités ne s'afficheront plus.
Omnilogie ne prend aucun pourcentage et la transaction est effectuée directement avec votre compte.
Respectez la politique de Google Adsense.
Sur la page d'accueil, la publicité affichée appartient à Omnilogie, et non à l'auteur. Ses bénéfices permettent la location du serveur.
Attention, ceci est un plus pour gratifier les rédacteurs et n'est en aucun cas une excuse pour voler le contenu d'autres sites.
PUB;


$Plus=<<<PLUS
Les articles publiés sur Omnilogie sont accessibles depuis Google.
Pour indiquer que vous êtes l'auteur de l'article, vous pouvez afficher votre avatar Google+ en face des résultats de recherche, comme \l[https://support.google.com/webmasters/answer/1408986?hl=fr]{indiqué sur cette page}.

\section{Comment participer ?}
Rendez-vous sur \l[http://plus.google.com/me/about/edit/co]{cette page de Google Plus}. Récupérez dans l'URL qui s'affiche votre numéro ou votre nom (par exemple  \\verbatim{https://plus.google.com/106281922635718248176} ou \\verbatim{https://plus.google.com/+NeamarTucote} (uniquement cette partie, sans la suite).
Dans la partie "Auteur de" de la page G+, ajoutez un nouveau lien contenant l'adresse vers votre page publique Omnilogie -- par exemple \\verbatim{http://omnilogie.fr/Omnilogistes/Neamar/}.

Depuis \l[/membres/]{votre espace membre}, copiez-collez la valeur récupérée dans le champ "Google Plus" et enregistrez. Si le champ redevient vide, votre URL n'est pas valide.

\section{Tests}
Afin de tester la configuration finale, rendez-vous sur \\l[http://www.google.com/webmasters/tools/richsnippets]{l'outil de test Google} pour vérifier que vos articles affichent bien votre image.

En cas de problème, \l[/Contact]{contactez les admins}.
PLUS;


Typo::setTexte($Lecteurs);
$C['Lecteurs'] = Typo::Parse();

Typo::setTexte($Auteurs);
$C['Auteurs'] = Typo::Parse();

Typo::setTexte($Administrateurs);
$C['Administrateurs'] = Typo::Parse();

Typo::setTexte($Licence);
$C['Licence'] = Typo::Parse();

Typo::setTexte($Pub);
$C['Pub'] = Typo::Parse();

Typo::setTexte($Plus);
$C['Plus'] = Typo::Parse();