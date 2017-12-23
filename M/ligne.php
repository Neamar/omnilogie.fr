<?php
/**
* Mod�le : ligne
* But : Afficher ligne �ditoriale, disclaimer, etc.
* Donn�es � charger :
*	- Pour les lecteurs
*	- Pour les auteurs
*/

$C['PageTitle']='Pr�cisions concernant le site Omnilogie.fr : ses articles, ses lecteurs, ses auteurs.';
$C['CanonicalURL']='/Ligne';

Typo::addOption(ALLOW_SECTIONING);//Autoriser les balises de titres pour cette page.

$Lecteurs =<<<LECTEUR
\section{Contenu}

Le contenu propos� sur le site \l[/]{Omnilogie} est � utiliser avec pr�caution.
Ce contenu refl�te la pens�e de ses auteurs, qui ne satisfait pas forc�ment � un point de vue neutre. Omnilogie et ses responsables se d�dient de toute responsabilit� quant aux opinions personnelles ou politiques exprim�es par les membres de fa�on implicite et explicite.
De m�me, les informations techniques / scientifiques / ethnologiques / ... exprim�es dans les articles ne sauraient �tre tenues pour correctes sans v�rification appropri�e ; m�me si vous pouvez partir avec un � priori positif, ces informations sont l� pour vous instruire et n'ont pas force de loi. Le contenu des articles est normalement valid� tout au long de son parcours dans la cha�ne administrative, mais il reste possible qu'une erreur technique se soit gliss�e dans l'omnilogisme.

\section{Contenant}

Nous essayons de minimiser les fautes d'orthographe, mais il reste possible que certaines se glissent � travers les mailles du filet.

Merci de signaler toutes fautes d'orthographe ou de fond que vous rencontreriez via le \l[/Contact]{formulaire de contact}.
LECTEUR;

$Auteurs = <<<AUTEURS
Avant de r�diger vos omnilogismes, voici quelques conseils � respecter pour le bien de tous.
Le non-respect de ces consignes pourra entra�ner le refus de vos articles.

\section{Concernant le fond}

De pr�f�rence, votre article doit traiter d'un sujet int�ressant, culturellement enrichissant. �vitez les l�gendes urbaines (le mot OK vient de 0 Kill, Fuck signifie Fornication under consent of the king), r�sidus de sagesse populaire dont le seul int�r�t est de montrer � quel point le peuple est "sage".

Ne vous sp�cialisez pas dans un domaine : restez polyvalent, et essayez de ne pas �crire tous vos articles sur un m�me th�me. Si vous vous sp�cialisiez, vous seriez forc�s d'entrer dans des d�tails pointus pour vos articles, ce qui n'est ni le but, ni la motivation de ce site.
Vous pouvez, en revanche, cr�er une mini-s�rie de quelques articles sur un m�me sujet, � condition d'apporter � chaque fois un �l�ment nouveau. Les taggers se feront alors un plaisir de cr�er une nouvelle cat�gorie pour votre saga (si apr�s plusieurs articles, votre cat�gorie n'a toujours pas �t� cr��e, n'h�sitez pas � \l[/Contact]{contacter les administrateurs}. Vous pouvez aussi aider les taggers en faisant pr�c�der votre article d'un titre explicite, tel que "Les armes � feu (1) � Le pistolet et le revolver", "Les armes � feu (2) � La carabine et le fusil").

Essayez de ne pas d�passer la limite d'une page par article : des articles trop longs sont signe d'un trop grand approfondissement du sujet, ou de multiples sujets trait�s en une seule fois : fractionnez alors votre article, quitte � en cr�er une mini s�rie.
Il n'y a pas de limite minimale : tentez tout de m�me d'�crire plus de deux phrases, sinon il s'agit probablement d'une anecdote qui a plus sa place dans les liens "le saviez-vous" que dans les articles.
Ni trop peu, ni pas assez : � vous de trouver le juste milieu selon votre sujet.

\section{� propos du copier-coller}

Le copier-coller est � bannir des articles. Reformulez, �laguez, simplifiez ou recoupez vos informations, mais de gr�ce ne volez pas le travail des autres ! (m�me celui de Wikipedia : inspirez vous-en \i{� la rigueur}, mais ne recopiez pas). Sinon, quel int�r�t ? Autant aller lire l'article sur le site original...
D'autant qu'Omnilogie n'est pas du tout l� pour remplacer Wikip�dia : l� o� Wikip�dia se veut compl�te et exhaustive, Omnilogie tente de donner des anecdotes ou des petits faits croustillants qui n'ont pas leur place pour un article d�di� de Wikip�dia.

\section{Concernant la forme}
\subsubsection{Typographie}

Une grande partie du travail de mise en forme a �t� simplifi�e : les espaces ins�cables sont plac�s automatiquement autour des signes de ponctuations appropri�s, les ligatures (oe, AE) sont automatiques.
Les guillemets typographiques sont remplac�s par des guillemets � la fran�aise, et les guillemets imbriqu�s sont g�r�s automatiquement s'ils sont plac�s correctement autour du mot (de "cette" fa�on, sans espace entre le symbole et le mot). De m�me pour les incises - ainsi - qui prennent la mise en forme demand�e par le code typographique � condition d'�tre encadr�e par des espaces. Vous trouverez plus de renseignements sur la page d�di�e � la Typographie : \l[http://neamar.fr/Res/Typographe/]{du Typographe de Neamar}.
Il est cependant certaines choses qui ne peuvent �tre automatis�es. Il en va ainsi des majuscules accentu�es, et des fautes d'orthographe. Vous vous adressez � un public d'adultes, et en cons�quent, le langage SMS ou phon�tique est banni. Des fautes trop nombreuses dans le contenu d'un article peuvent entra�ner son refus : les censeurs ne sont pas l� pour traduire du kikoolol au fran�ais, mais pour mettre en forme l'article.

\subsubsection{Balises}

La \l[http://neamar.fr/Res/Typographe/Aide/]{liste des balises du Typographe} est disponible depuis toutes les interfaces de r�daction, en cliquant sur l'icone en forme de point d'interrogation. Afin de garder les articles concis, les balises de titres ont �t� bannies, en dehors de cela, vous pouvez utiliser tout ce qui aidera � clarifier votre message : note de bas de page, formule en LaTeX...

\section{Une fois post�}
\subsubsection{Refus�}

Si votre message est refus�, un mail vous en avertit, avec une copie de l'omnilogisme. Lisez bien ce mail, qui vous indiquera pour quelle raison votre texte n'a pas �t� accept� : il est fort probable que ce soit simplement parce qu'il est trop long, ou qu'il y a trop de fautes... il vous suffit alors de corriger votre article, et de le renvoyer.
Au moment de la r�daction de ces lignes, tr�s peu d'articles ont �t� refus�s d�finitivement ; et dans la majorit� des cas il s'agit de doublons. Ne perdez donc pas espoir, votre article para�tra un jour !

\subsubsection{Mal tagg�}

Les taggers se chargent d'associer vos articles � diff�rents cat�gories de l'\l[/Liste/]{arbre}. Si vous trouvez que les crit�res associ�s � l'article ne conviennent pas, \l[/Contact]{faites-le savoir}. Si vos raisons sont justifi�es, les mots cl�s seront modifi�s.

\subsubsection{�crire plus pour cultiver plus}

Par d�faut, vous recevez un rappel par mail une fois par mois (d�sactivable). Mais vous pouvez �crire � tout moment un omnilogisme en vous resservant du dernier mail re�u, ou en utilisant le lien permanent donn� sur chaque mail pour l'�criture en ligne. Vos contributions sont toujours les bienvenues !

\section{Conseils}
\subsubsection{Le choix du sujet}

Vous venez de recevoir un mail vous incitant � �crire un omnilogisme ; ou vous souhaitez vous lancer dans l'aventure.
Ne cherchez pas un sujet "sur le coup" : laissez plut�t le mail dans un coin de votre boite mail, et attendez quelques 24 heures : il est fort probable que pendant ce laps de temps, vous aurez eu une conversation au cours de laquelle vous vous serez dit "Voil� qui ferait un article int�ressant". Les articles trouv�s de cette fa�on sont souvent de meilleure qualit� que ceux pour lesquels on force le cerveau � trouver un sujet : ces derniers articles sont souvent trop "scolaires".

Bons omnilogismes � tous,

Neamar, pour les administrateurs.
AUTEURS;

$Administrateurs=<<<ADMINISTRATEURS
Le terme administrateur a deux sens sur Omnilogie : il d�signe � la fois les membres ayant plus de pouvoir que les autres (c'est injuste, mais c'est la vie), et les membres disposant des supers pouvoirs - plus commun�ment nomm�s admins.

Les administrateurs sont nombreux et ont des t�ches distinctes : de la correction des fautes � l'ajout de banni�res et d'anecdotes en passant par le taggage, il y en a pour tous les go�ts !
Vous pouvez les reconna�tre gr�ce � leur petit bandeau color� appos� sur leurs noms.

Si vous souhaitez rejoindre leurs rangs, n'h�sitez pas � postuler : on a toujours besoin de sang frais ! \l[/Contact]{Envoyez CV et lettre de motivation} (humour).
ADMINISTRATEURS;

$Licence=<<<LICENCE
\section{Avant parution}

Tant qu'un article n'est pas paru, l'auteur peut le modifier � sa guise. Notez cependant que toute modification remet le statut de l'omnilogisme sur "\\texttt{Indetermine}" : l'omnilogisme ne paraitra qu'une fois (re-)lu et valid� par un administrateur.

Les censeurs et administrateurs s'efforcent de rendre les articles les plus agr�ables possibles : nous tentons d'effectuer les modifications les plus minimes pour garder intacte la pens�e de l'auteur, mais dans certains cas nous modifions des phrases enti�res pour faciliter la lecture et la compr�hension de l'article.
Si des donn�es sont ajout�es ou supprim�es (modification \b{plus importante qu'une simple reformulation}), l'auteur est contact� pour savoir s'il accepte les modifications.

\section{Apr�s parution}

Une fois paru, les articles ne sont plus modifiables par leurs auteurs pour �viter l'affichage d'informations potentiellement incorrectes. Omnilogie.fr se r�serve le droit de faire paraitre ces articles sur n'importe quel site ou support externe (livre...), mais s'engage � citer le pseudonyme de l'auteur pour toute reproduction int�grale de l'oeuvre. Si le contenu devait �tre utilis� autre part que sur un site, les auteurs concern�s en seraient bien entendu avis�s.

\section{Suppression}

L'auteur ou tout ayant droit peut demander la suppression d'un article : celle-ci sera effectu�e le plus rapidement possible si les raisons de la suppression sont justifi�es.

\section{Citer un article sur un site / blog...}

Vous pouvez citer tout ou partie d'un omnilogisme (et vous y �tes m�me invit�s !) sous condition de faire un lien vers la page du site que vous jugez la plus appropri�e. La mention de l'auteur n'est obligatoire qu'en cas de recopie int�grale ou quasi-int�grale de l'article ; cependant essayez de ne pas l'oublier !

\section{Concernant les images}

Les images utilis�es pour illustrer les omnilogismes ont �t� choisies par les membres. Vous pouvez � tout moment \l[/Contact]{effectuer une demande} pour supprimer une image du site si vous en poss�dez les droits.
LICENCE;

$Pub=<<<PUB
Omnilogie permet aux auteurs d'utiliser leur compte Google Adsense afin d'�tre indirectement r�mun�r� pour leurs articles.

\section{Adsense, c'est quoi ?}
Google Adsense est une plateforme publicitaire affichant des annonces cibl�es avec le contenu. La r�tribution se fait au clic, et le paiement a lieu tous les mois.

\section{Comment �a marche ?}
L'auteur indique dans son \l[/membres/]{espace membre sur Omnilogie} son identifiant Adsense, sous la forme \\texttt{\\verbatim{pub-9999999999999999}}.
Une fois ces donn�es sp�cifi�es, tous les articles du membre parus apr�s le premier octobre 2011 afficheront une banni�re publicitaire dont l'argent des clics sera directement revers�e au compte Adsense sp�cifi�.

\section{Comment m'inscrire ?}
Rendez-vous sur \l[https://www.google.com/adsense]{Google Adsense} et cr�ez votre compte.
� l'issue de cette proc�dure, connectez-vous � Adsense : sur la page d'accueil, vous trouverez une "r�f�rence �diteur". Recopiez-l� dans le champ appropri� sur \l[/membres/]{votre espace membre}. Puis r�digez des omnilogismes et faites-les conna�tre !

\section{Combien �a rapporte ?}
Les conditions d'utilisation interdisent de donner des chiffres pr�cis. Comptez entre 2 et 15 euros par article selon sa popularit� (indication non contractuelle).

\section{Autour de la fonctionnalit�...}
En cas d'abus, n'h�sitez pas � nous \l[/Contact]{contacter}.
Vous pouvez � tout moment supprimer votre identifiant, auquel cas les publicit�s ne s'afficheront plus.
Omnilogie ne prend aucun pourcentage et la transaction est effectu�e directement avec votre compte.
Respectez la politique de Google Adsense.
Sur la page d'accueil, la publicit� affich�e appartient � Omnilogie, et non � l'auteur. Ses b�n�fices permettent la location du serveur.
Attention, ceci est un plus pour gratifier les r�dacteurs et n'est en aucun cas une excuse pour voler le contenu d'autres sites.
PUB;


$Plus=<<<PLUS
Les articles publi�s sur Omnilogie sont accessibles depuis Google.
Pour indiquer que vous �tes l'auteur de l'article, vous pouvez afficher votre avatar Google+ en face des r�sultats de recherche, comme \l[https://support.google.com/webmasters/answer/1408986?hl=fr]{indiqu� sur cette page}.

\section{Comment participer ?}
Rendez-vous sur \l[http://plus.google.com/me/about/edit/co]{cette page de Google Plus}. R�cup�rez dans l'URL qui s'affiche votre num�ro ou votre nom (par exemple  \\verbatim{https://plus.google.com/106281922635718248176} ou \\verbatim{https://plus.google.com/+NeamarTucote} (uniquement cette partie, sans la suite).
Dans la partie "Auteur de" de la page G+, ajoutez un nouveau lien contenant l'adresse vers votre page publique Omnilogie -- par exemple \\verbatim{http://omnilogie.fr/Omnilogistes/Neamar/}.

Depuis \l[/membres/]{votre espace membre}, copiez-collez la valeur r�cup�r�e dans le champ "Google Plus" et enregistrez. Si le champ redevient vide, votre URL n'est pas valide.

\section{Tests}
Afin de tester la configuration finale, rendez-vous sur \\l[http://www.google.com/webmasters/tools/richsnippets]{l'outil de test Google} pour v�rifier que vos articles affichent bien votre image.

En cas de probl�me, \l[/Contact]{contactez les admins}.
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