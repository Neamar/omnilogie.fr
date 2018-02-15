<?php
if(md5($_GET['code'])==getenv("ACCESS_TOKEN_SECRET")) {
  External::tweet("Oh. Bonjour ? Ça faisait longtemps ;)");
  echo "DONE";
} else {
  echo "ENOD";
}
