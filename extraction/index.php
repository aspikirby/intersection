<?php
require 'intersection.php';
//require 'bdd.php';

$appId='303297209800476';//l'ID de notre api
$appSecret='e4cfd069ca23e10583e9fbe4ccf7a136';//la clé secrète de notre api

$b = new Bdd();
$b->bddConnexion();
$b->bddRequest();

$intersection = new Intersection($appId, $appSecret);


if(!$intersection->isConnected()){
  $intersection->accessAuthorisation();
}

if(!$intersection->userExist()){
  $intersection->firstConnexion();
}


echo 'On à bien récupéré vos données merci! Appel du fichier des utilisateurs pour se comparer';



?>

<body>
   
  <table border="1" cellpadding="20" cellspacing="1" width="1000px">
  <CAPTION> DONNEES DE LA TABLE USER </CAPTION>
  <tr><td>FACEBOOK-ID</td>
  <td width="20%">TOKEN</td>
  <td width="20%">LAST NAME</td>
  <td width="20%">FIRST NAME</td>
  <td width="20%">MAIL</td>
  <td width="20%">GENDER</td>
  <td width="20%">CODE</td>
  <td width="20%">STATE</td>
  <td width="20%">CHOIX</td>
  </tr>

   <tr>
    <td>$uid</td>
    <td>$access_token</td>
    <td>$last_name</td>
    <td>$first_name</td>
    <td>$mail</td>
    <td>$gender</td>
    <td>$code</td>
    <td>$state</td>
    <td><input type='checkbox'value='choix' name='checkbox[]'></td>
    </tr>"
    
    <input type="submit" value="Valider">

</body>
<!--   
$checkboxes = isset($_POST['checkbox']) ? $_POST['checkbox'] : array();
foreach($checkboxes as $value) {
echo 'test3';
echo'<form action = \"select.php\" enctype=\"multipart/form-data\" method= \"post\">';
echo 'test4';   
-->

