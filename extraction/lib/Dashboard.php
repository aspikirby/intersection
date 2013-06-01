<?php

require 'config/config.inc.php';
require 'lib/Intersection.php';

$app = new Intersection($fb_app_id , $fb_app_secret);

	foreach($app->getUsers() as $user){
	echo "$user <br/>";
	}
?>