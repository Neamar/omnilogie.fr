<?php
/**
* Classe External, permettant de connecter Omnilogie au reste du monde en r�cup�rant des donn�es distantes.
* Pour acc�lerer le rendu des pages, la plupart des actions sont d�f�r�e en fin de script.
*
*/
//External

class External
{
	/**
	* R�cup�re une ressource distante.
	* @param URL:String l'url � t�l�charger
	* @param OutFile:String l'emplacement o� sauvegarder le fichier. Si null, on place tout dans le cache du site.
	* @param POST:array les donn�es POST � simuler
	* @param CacheTime:int la dur�e de cache en secondes. 0 d�sactive le cache
	* @return :String le chemin vers le fichier sauvegard�.
	*/
	public static function fetch($URL,$CacheTime=0)
	{

		if(Cache::modified(__CLASS__,$URL)  + $CacheTime < time())
		{
			$DL = curl_init();
			curl_setopt($DL, CURLOPT_URL,$URL);
			curl_setopt($DL, CURLOPT_RETURNTRANSFER, 1);
			Cache::set(__CLASS__,$URL,curl_exec($DL));
			curl_close($DL);
		}

		return Cache::get(__CLASS__,$URL);

		/*

		//D�terminer l'emplacement de sauvegarde.
		if(is_null($OutFile))
			$OutFile = '/Cache/' . md5($URL);

		if($CacheTime==0 || !is_file(PATH . $OutFile) || filemtime(PATH . $OutFile) + $CacheTime < time())
		{
			$DL = curl_init();
			curl_setopt($DL, CURLOPT_URL,$URL);
			curl_setopt($DL, CURLOPT_RETURNTRANSFER, 1);
			$Retour = curl_exec($DL);
			curl_close($DL);
			file_put_contents(PATH . $OutFile,$Retour);
		}

		return $OutFile;*/
	}

	/**
	* Tweete le message demand� sur le compte d�fini par TWITTER_PSEUDO et TWITTER_PASSWORD.
	* Le tweet se fait en fin de page, afin de ne pas ralentir l'affichage.
	* NOTE: Message sera encod� en UTF-8.
	* @param Message:String le message � envoyer. La taille sera tronqu�e si le message d�passe 135 caract�res.
	* @return
	*/
	public static function tweet($Message)
	{
		include_once(LIB_PATH . '/twitter/twitter.php');
		$consumerKey = "ucE1c2KU33Tlzsp5CYIQ";
		$consumerSecret = "04hubUqfRfMwR9VH7nhcUBYpbpEdXQuAPSNVEvpmXY";
		$accessToken = "111340166-RgRkRq35zwpKeNgPps6O7OPQIqT0rAghB0q9bzT1";
		$accessTokenSecret = "geGLiAcXuVxXYrPDmGiop5FXwtwbqzKJFU4OSQ6oTUg";
		$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

		$Message = utf8_encode($Message);
		$Message = mb_substr($Message, 0, 135, 'utf-8');
		$twitter->send($Message);
	}

	/**
	* Envoie un mail � $to.
	* L'envoi du mail se fait en fin de page, afin de ne pas ralentir l'affichage.
	* @param To:String le destinataire du message
	* @param Subject:String le sujet du mail
	* @param Message:String le message au format HTML.
	*/
	public static function mail($to,$subject,$message,$from='admin@omnilogie.fr')
	{
		// Disabled
		return;
		// Pour envoyer un mail HTML, l'en-t�te Content-type doit �tre d�fini
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// En-t�tes additionnels
		$headers .= 'From: Omnilogue <' . $from . '>' . "\r\n";//admin@omnilogie.fr est une liste de diffusion vers les administrateurs.

		register_shutdown_function("mail",$to, $subject, $message, $headers);

		$fichier = fopen(DATA_PATH . '/mail_logs', 'a'); //Ouvrir le fichier
		fputs($fichier, time() . '| ' . $to . '=>' . $subject);//Puis enregistrer les donn�es
		fputs($fichier, "\n");
		fclose($fichier); //Et fermer le fichier

		Event::log('Envoi de mail � ' . $to . ' : ' . $subject);
	}

	/**
	* Ajoute une notification dans l'agenda de la personne d�finie par GOOGLE_PSEUDO et GOOGLE_PASSWORD.
	* L'enregistrement de l'�v�nement se fait en fin de page, afin de ne pas ralentir l'affichage.
	* @param Title:String le titre de la notification
	* @param Desc:String la description associ�e.
	* @param Date:int timestamp la date � laquelle il faut ajouter l'�venement. NOW() + 2 minutes par d�faut.
	*/
	public static function notify($Title,$Desc='',$Date=null)
	{
		register_shutdown_function(array('External','_notify'),$Title,$Desc,$Date);
	}

	public static function _notify($Title,$Desc='',$Date=null)
	{
		//Aucune date sp�cifi�e : envoyer la notification im�mdiatement.
		if($Date==null)
			$Date=time() + 3*60 + 10 - date('s');
		$Title = utf8_encode($Title);
		$Desc = utf8_encode($Desc);

		//1 : r�gler le chemin vers la librairie Google
		include_once(LIB_PATH .  '/google.php');

		//2 : Charger ce qui nous interesse
		Zend_Loader::loadClass('Zend_Gdata_Calendar');

		//3 : connexion
		$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;

		$client = Zend_Gdata_ClientLogin::getHttpClient(GOOGLE_PSEUDO,GOOGLE_PASSWORD,$service);


		//4 : Insertion de l'�l�ment

		$startDate = date('Y-m-d',$Date);//2009-11-23
		$startTime = date('H:i',$Date);

		//Moduler le fuseau horaitre en fonction de l'heure d'�t� / d'hiver.

		if(date('I',$Date)==0)
			$tzOffset = '+01';
		else
			$tzOffset = '+02';

		$gdataCal = new Zend_Gdata_Calendar($client);
		$newEvent = $gdataCal->newEventEntry();

		$newEvent->title = $gdataCal->newTitle($Title);
		$newEvent->content = $gdataCal->newContent($Desc);

		$when = $gdataCal->newWhen();
		$when->startTime = "{$startDate}T{$startTime}:00.000{$tzOffset}:00";
		$newEvent->when = array($when);

		$event = $gdataCal->insertEvent($newEvent);
	}
}
