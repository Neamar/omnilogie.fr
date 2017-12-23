<?php
/**
* Vue : admin/showTag
* Layout : toutes les sections d'administration disponibles
*/
?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {

	//Construire la taxinomie
	$('#menus div').css('paddingLeft','10px');
	$('#menus div div').css('borderLeft','1px solid gray');
	$('#menus h3').css('cursor','pointer')
		.click(function(){$(this).next().toggle('slow');});

	$('#menus input').change(function()
	{
		Actuel = $(this);

		$('#menus input[value=' + Actuel.attr('value') + ']').attr('checked',Actuel.attr('checked'));

		if(Actuel.attr('checked'))
			$('#menus input[value=' + Actuel.attr('value') + '] + br + div').hide('slow');
		else
			$('#menus input[value=' + Actuel.attr('value') + '] + br + div').show('slow');
    });
    $('#menus input[checked="checked"]').trigger('change');

	//L'article suit le scroll
	var ScrollDiv = $('.omnilogisme');

	ScrollDiv.css('height',$(window).height()-30);
	ScrollDiv.css('overflow','auto');

	var BaseValue = ScrollDiv.offset().top - 10;
	$(window).scroll(function(e)
	{
		S = $(this).scrollTop();
		if(S > BaseValue)
		{
			ScrollDiv.stop()
				.animate({'marginTop':S-BaseValue},350);
		}
	});
	$(window).scroll();

	//Le formulaire autour
	$('#menus').wrap('<form method="post" action=""></form>');
	$('#menus section:first').before('<input type="submit" value="Enregistrer" /><input type="submit" value="Enregistrer et passer" name="goNext" />');
	$('#menus section:last').after('<input type="submit" value="Enregistrer" /><input type="submit" value="Enregistrer et passer" name="goNext" />');
});
</script>


<?php Template::put('Header') ?>

<div role="main" class="omnilogisme">
<?php Template::put('Article') ?>
</div>