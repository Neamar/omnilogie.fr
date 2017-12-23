<?php
$content = Typo_parseLines($envContent);

$envContent = '
<div class="figure">
	' . $content . '
	<span class="legend">' . $Env[2] . '</span>
</div>';

?>