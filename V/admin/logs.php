<?php
/**
* Vue : /admin/Logs
*/
//Logs
?>

<form method="post" action="">
<label for="file">Sélectionnez une date : </label>
<select name="file" id="file" onchange="this.parentNode.submit()">
<?php
foreach($C['LogsFile'] as $File=>$Caption)
{?>
	<option value="<?php echo $File ?>"><?php echo $Caption; ?></option>
<?php } ?>
</select>
<input type="submit" value="Voir" />
</form>
</section>
<section>
<table>
<caption>Logs du <?php Template::put('Date') ?></caption>
<thead>
<tr>
	<th>Heure</th>
	<th>Membre</th>
	<th>Action</th>
</tr>
</thead>
<tbody>
<?php
foreach($C['Logs'] as $Log)
{?>
	<tr>
		<td><?php echo $Log[0]; ?></td>
		<td><a title="<?php echo $Log[1] ?>" href="<?php echo Link::author(trim($Log[2])) ?>"><?php echo $Log[2] ?></a></td>
		<td><?php echo $Log[3] ?></td>
	</tr>
<?php
}
?>
</tbody>
</table>