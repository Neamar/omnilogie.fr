<?php
/**
* Optimiser la base de donn�es
* Event::NOUVELLE_CATEGORIE
*
* @standalone
* Optimiser la base de donn�es
*/
SQL::query('OPTIMIZE TABLE OMNI_Anecdotes, OMNI_Auteurs, OMNI_Categories, OMNI_Forum, OMNI_Liens, OMNI_Mails, OMNI_Modifs, OMNI_More, OMNI_Omnilogismes, OMNI_Propositions, OMNI_Redirection');