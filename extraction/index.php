<?php

require 'config/config.inc.php';
require 'lib/Intersection.php';

$app = new Intersection($fb_app_id , $fb_app_secret);

if(!$app->isUserConnected()) {
  include 'connect.php';
} else {
  if(!$app->isUserKnown()) {
    echo "add";
    $app->addNewUser();
  }
  //TODO display user graph
  echo "Notre jolie application lien avec visualisation";
}
?>