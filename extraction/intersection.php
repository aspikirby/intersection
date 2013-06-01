<?php

require 'facebook.php';
require 'bdd.php';

class Intersection{
  public $facebook;
  public $b;
  //public $dbh; 

  public function __construct($appId, $appSecret) {
  // on crée notre objet Facebook
    $this->facebook = new Facebook(array(
    'appId'  => $appId,
    'secret' => $appSecret,
    'cookie' => true,
  ));
  }

  public function isConnected(){ //return true if user is connected
    $currentUser = $this->facebook->getUser();
    if(!$currentUser){
      return false;
    } else{
      return true;
    }
  }

  public function accessAuthorisation(){ //ask the user for access token authorisation
    require 'connect.php';
  }

/*public function userExist(){ //return true is the current user is in the data base
    require 'config.inc.php';
    $uid =$this->facebook->getUser();    
    try{
      $dbh = new PDO($dsn, $db_user, $db_password);
    }catch (PDOException $e) {
      echo 'Connexion échouée : ' . $e->getMessage();
    }
    $query="select facebook_id from user where facebook_id = '$uid'";
    if($dbh->exec($query) != null){
      return true;
    } else{      
      return false;
    }
  }*/

  public function userExist(){ //return true is the current user is in the data base
    $uid =$this->facebook->getUser(); 
    Bdd::bddExist($uid);
  }

  public function firstConnexion(){ //store user id, last name, first name, mail, gender access token, code and    state for his first connexion in the data base
    $uid =$this->facebook->getUser();
    $array = $this->facebook->api('/'.$uid);
    $last_name = $array['last_name'];
    $first_name = $array['first_name'];
    $mail = $array['email'];
    $gender = $array['gender'];
    $access_token=$this->facebook->getAccessToken();
    
    $b = new Bdd;
    $b->bddConnexion();
    $b->bddInsert();
    $b->bddRequest($last_name);
  }
}
?>
