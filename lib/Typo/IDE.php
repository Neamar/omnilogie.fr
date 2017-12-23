<!-- jQuery -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<!-- markItUp! -->
<script type="text/javascript" src="http://neamar.fr/lib/markitup/jquery.markitup.pack.typo.js" defer></script>
<!-- markItUp! skin -->
<link rel="stylesheet" type="text/css" href="http://neamar.fr/lib/markitup/style.typo.css" />
<!--  markItUp! toolbar skin -->
<link rel="stylesheet" type="text/css" href="http://neamar.fr/lib/markitup/sets/Typo/style.css" />


<script type="text/javascript">
<!--
$(document).ready(function()	{
	$('.typo_textarea').markItUp(TypoSettings);
});

var Previews=[];
-->
</script>

<?php
function renderIDE($Texte,$Param)
{
	$_Param=array(
	'Name'=>'texte',
	'Rows'=>10,
	'Cols'=>25,
	'Preview'=>false,
	);
	$Param=array_merge($_Param,$Param);

	if($Param['Preview']!=false)
		echo '<script type="text/javascript">
	Previews["' . $Param['Name'] . '"]=["' . $Param['Preview']['URL'] . '","' . $Param['Preview']['ID'] . '"];
</script>';
else
	?>
		<br />
		<label for="<?php echo $Param['Name']; ?>">Votre texte :</label><br />
		<textarea name="<?php echo $Param['Name']; ?>" id="<?php echo $Param['Name']; ?>" class="typo_textarea" cols="<?php echo $Param['Cols']; ?>" rows="<?php echo $Param['Rows']; ?>" style="width:98%;"><?php echo $Texte; ?></textarea>
	<?php
}
?>