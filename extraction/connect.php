<meta charset="utf-8" />
<html>
  <head>
    <title>
    Intersection 
    </title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <style>
        body{
          padding:30px;
        }
      </style>
  </head>
    <body>

    <?php	
    $loginUrl = $this->facebook->getLoginUrl(array('scope' => 'email,offline_access,publish_stream,user_birthday,
    user_groups, user_notes, user_events')); // ajout des User & Friends permission
    // Get the current access token
    $access_token = $this->facebook->getAccessToken();
    $logoutUrl  = $this->facebook->getLogoutUrl();
    ?>	

      <h1>Pour utiliser l'appli intersection connectez-vous à votre compte Facebook</a></h1>
      <h2>Page Facebook de l'appli: <a href="https://www.facebook.com/aspi.kirby"> Aspi Kirby local</a></h2>

    Se connecter via Facebook <br />
    <a href="<?=$loginUrl?>"><img src="http://powerful-hamlet-9859.herokuapp.com/pictures/facebook-connect-logo.jpg"></a>

	


    </body>
</html>
