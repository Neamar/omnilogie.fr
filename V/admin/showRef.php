<?php
/**
* Vue : admin/Ref
* Layout : Titre,
* Article
* Liens
*/
?>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/themes/start/jquery-ui.css" type="text/css" rel="stylesheet" />
        

<h3><span>Référencement d'article</span></h3>
<p>Lien vers l'article tel qu'il sera affiché : <?php Template::put('LienDirect'); ?><br />
<p><a href="/admin/Edit/">Liste des articles à modifier</a></p>
<p><a href="<?php echo Link::omni($C['Titre'],'/admin/'); ?>">Administrer l'article</a></p>
</section>

<section>
<h3><span>Formulaire de référencement</span></h3>
<form method="post" action="">
<label for="titre">Titre :</label><br />
<input type="text" name="titre" id="titre" value="<?php Template::put('titre','Valeurs') ?>" /><br />

<hr />
<?php
Typo::setTexte($C['Valeurs']['article']);
Typo::renderIDE(array('Name'=>'article','Rows'=>17,'Preview'=>array('URL'=>'/membres/Apercu','ID'=>'Typo_preview')));
?>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js"></script>

<div id="modal-window">
	<h3><span>Référencement interactif</span></h3>
	<p>Texte sélectionné : <tt id="select-preview"></tt></p>
	<select id="liste-article"></select><br />
	<ul id="liste-similaire" style="max-height:300px; overflow:auto;"></ul>
	<p>Code : <tt id="render-preview"><span style="color:#f00000;">\ref</span>[<span id="render-preview-ref" style="color:#00e000"></span>]{<span style='color:#0000d0;' id="render-preview-text">Énorme</span>}</tt></p>
</div>
<script type="text/javascript">
var Articles = [<?php echo implode(',',$C['Articles']) ?>];

var ArticleRef = '?';
function prepareRef(Titre)
{
	ArticleRef = Titre.replace(/ /g,'_').replace(/&/,'%26');
	$('#render-preview-ref').text(ArticleRef);
	$('button:first').click();
}

function insertAtCaret(txtarea,text)
{
	var scrollPos = txtarea.scrollTop; var strPos = 0; var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ? "ff" : (document.selection ? "ie" : false ) ); if (br == "ie") { txtarea.focus(); var range = document.selection.createRange(); range.moveStart ('character', -txtarea.value.length); strPos = range.text.length; } else if (br == "ff") strPos = txtarea.selectionStart; var front = (txtarea.value).substring(0,strPos); var back = (txtarea.value).substring(txtarea.selectionEnd,txtarea.value.length); txtarea.value=front+text+back; strPos = strPos + text.length; if (br == "ie") { txtarea.focus(); var range = document.selection.createRange(); range.moveStart ('character', -txtarea.value.length); range.moveStart ('character', strPos); range.moveEnd ('character', 0); range.select(); } else if (br == "ff") { txtarea.selectionStart = strPos; txtarea.selectionEnd = strPos; txtarea.focus(); } txtarea.scrollTop = scrollPos;
}
	
$(function()
{
	$( "#modal-window" ).dialog({
			autoOpen: false,
			title: 'Boîte de référencement',
			height: 500,
			width: '50%',
			modal: true,
			buttons: {
				"Référencer": function() {
					insertAtCaret($('textarea#article')[0],'\\ref[' + ArticleRef + ']{' + Texte + '}');
					$( this ).dialog( "close" );
				},
				"Annuler": function() {
					$( this ).dialog( "close" );
				}
			}
		});
		
	var Texte = '';
	$('textarea#article').select(function(e)
	{
		Texte = e.target.value.substr(e.target.selectionStart,e.target.selectionEnd-e.target.selectionStart);
		$('#select-preview').text(Texte);
		$('#render-preview-text').text(Texte);
		$('#render-preview-ref').text(ArticleRef);
		$.get('/admin/RefSearch/' + Texte.replace(/ /g,'_'),function(data)
		{
			$('#liste-similaire').html(data);
		});
		$( "#modal-window" ).dialog('open');
	});

	var SelectHTML = '';
	for(var i=0;i<Articles.length;i++)
	{
		SelectHTML += '<option value="' + Articles[i] + '">' + Articles[i] + '</option>\n';
	}
	$('#liste-article')
		.css('width',$(window).width()/4 - 20)
		.html(SelectHTML)
		.change(function(){prepareRef(this.value); });
});
</script>

<hr />

<p>C'est tout bon ? Cliquez ici pour enregistrer vos modifications.</p>
<input type="submit" value="Enregistrer les changements" id="save_change"/>

</form>

<hr />

<p>Aperçu de l'article modifié :</p>
<div id="Typo_preview">
<p>Cliquez sur l'icone <img src="http://neamar.fr/lib/markitup/sets/Typo/images/preview.png" alt="Flèche" class="TexPic"> au dessus de l'interface d'édition pour afficher ici un aperçu de votre texte.<br />
Version actuelle :</p>
<div class="omnilogisme">
<?php Template::put('Apercu') ?>
</div>
</div>

<script type="text/javascript" src="/images/lock.js"></script>
