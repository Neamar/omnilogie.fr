<?php
if($_GET['code']==getenv("ACCESS_TOKEN_SECRET")) {
  External::tweet("Oh. Bonjour ? Ça faisait longtemps ;)");
}
