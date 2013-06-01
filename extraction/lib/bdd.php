<?php

//require 'intersection.php';


class Bdd{

  public $dbh;
	
	
  public function bddConnexion() {
    require 'config/config.inc.php';
    try{
      $this->dbh = new PDO($dsn, $db_user, $db_password);
    }catch (PDOException $e) {
      echo 'Connexion échouée : ' . $e->getMessage();
    }
  }
		
  public function bddExist($uid){    
    $query="select facebook_id from user where facebook_id = '$uid'";
    $a=$this->dbh->prepare($query);
    $a->execute();
    if($a != null){
      return true;
    } else{      
      return false;
    }
  }

  public function bddInsert(){
    //$r = this->bddConnexion();	
    $query="insert into user(facebook_id,last_name, first_name,mail,gender,access_token) VALUES     ('$uid','$last_name','$first_name','$mail','$gender','$access_token')";
    $this->dbh->exec($query);
  }
	
  public function bddRequest(){
    //this->bddConnexion();
    $query ='SELECT * FROM user ORDER BY ID';
    $a=$this->dbh->prepare($query);
    $a->execute();
    while($result=$a->fetch(PDO::FETCH_OBJ)){
      $uid=$result->facebook_id;
      $access_token=$result->access_token;
      $last_name =$result->last_name;
      $first_name =$result->first_name;
      $mail=$result->mail;
      $gender =$result->gender;
      //echo .$result->last_name.'<br />';
      echo $uid, $last_name;
     }
     $a->closeCursor();
  }
}
?>
