<?php
header('Content-Type:application/json');

if(isset($_GET['fb_user_1']) && isset($_GET['fb_user_2'])) {
    require 'config/config.inc.php';
    require 'lib/Intersection.php';

    $app = new Intersection($fb_app_id , $fb_app_secret);

    $user1 = array(
        'fb_user_id' => $_GET['fb_user_1'],
        'fb_access_token' => $app->getFacebookUserAccessToken($_GET['fb_user_1'])
    );

    $user2 = array(
        'fb_user_id' => $_GET['fb_user_2'],
        'fb_access_token' => $app->getFacebookUserAccessToken($_GET['fb_user_2'])
    );

    echo $app->getFormatedData($user1, $user2);
}
?>