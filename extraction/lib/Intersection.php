<?php

require 'lib/Facebook.php';
require 'lib/IntersectionDAO.php';

class Intersection
{
  protected $facebook;

  /**
   * Constructor
   *
   * @param string $fb_app_id
   * @param string $fb_app_secrete
   */
  public function __construct($fb_app_id, $fb_app_secrete)
  {
    $this->facebook = new Facebook(array(
      'appId'  => $fb_app_id,
      'secret' => $fb_app_secrete,
      'cookie' => true,
    ));
  }

  /**
   * Getter
   */
  public function getFacebook()
  {
    return $this->facebook;
  }

  /**
   * Get Facebook Login URL
   *
   * @return string the facebook login url
   */
  public function getFacebookLoginUrl()
  {
      return $this->getFacebook()->getLoginUrl(array(
        'scope' => 'email, offline_access, user_birthday, user_groups, user_notes, user_events'
      ));
  }

  /**
   * Get facebook user access token
   *
   * @param string $uid
   * @return string $token
   */
  public function getFacebookUserAccessToken($uid)
  {
      return IntersectionDAO::getFacebookUserAccessToken($uid);
  }

  /**
   * Is Connected
   *
   * @return boolean true if user is connected
   */
  public function isUserConnected()
  {
    return $this->getFacebook()->getUser();
  }

  /**
   * 
   * @return boolean true is the current user is known by intersection application
   */
  public function isUserKnown()
  {
    $uid =$this->getFacebook()->getUser();

    return IntersectionDAO::isUserKnown($uid);
  }

  /**
   * Add New User
   *
   * @throws Exception
   * @return boolean true is the user is well added
   */
  public function addNewUser()
  {
    if($this->isUserKnown()) {
      throw new Exception('The user is already known');
    }

    $userData = $this->getFacebook()->api(sprintf(
      '/%s',
      $this->getFacebook()->getUser()
    ));

    return IntersectionDAO::storeUser(array(
      'facebook_user_id'      => $this->getFacebook()->getUser(),
      'facebook_access_token' => $this->facebook->getAccessToken(),
      'first_name'            => $userData['first_name'],
      'last_name'             => $userData['last_name'],
      'mail'                  => $userData['email'],
      'gender'                => $userData['gender']
    ));
  }
  
  /**
   * Get All Users
   *
   * @return  array 
   */
  public static function getUsers()
  {
  	return IntersectionDAO::getFacebookUsers();
  }

  /**
   * @param string $uid
   * @param string $token
   * @return string
   */
  public function getProfilData($uid, $token)
  {
      return self::execUrl(self::getProfilApiUrl($uid, $token));
  }

  /**
   * @param string $uid
   * @param string $token
   * @return string
   */
  public function getFriendsData($uid, $token)
  {
      return self::execUrl(self::getFriendsApiUrl($uid, $token));
  }

  /**
   * @param string $uid
   * @param string $token
   * @return string
   */
  public function getFormatedUserData($uid, $token)
  {
      $profil = json_decode($this->getProfilData($uid, $token), true);
      $friends = json_decode($this->getFriendsData($uid, $token), true);

      return array_merge($profil, $friends);
  }

  /**
   * @param array $user1
   * @param array $user2
   * @return string
   */
  public function getFormatedData($user1, $user2)
  {
      $_user1 = $this->getFormatedUserData($user1['fb_user_id'], $user1['fb_access_token']);
      $_user2 = $this->getFormatedUserData($user2['fb_user_id'], $user2['fb_access_token']);

      return json_encode(array(
        'user1'   => $_user1,
        'user2'   => $_user2,
        'common'  => array()
      ));
  }

  /**
   * Execute a given url and return the response content
   *
   * @param string $url
   * @return string the requested url page content;
   */
  public static function execUrl($url)
  {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      // Start buffering
      //ob_start();
      $data = curl_exec($ch);
      // End buffering and clean output
      //ob_end_clean(); 
      curl_close($ch);

      return $data;
  }

  /**
   * @param string $uid
   * @param string $token
   * @return string (url)
   */
  public static function getProfilApiUrl($uid, $token)
  {
    return sprintf("https://graph.facebook.com/%s?access_token=%s", $uid, $token);
  }

  /**
   * @param string $uid
   * @param string $token
   * @return string (url)
   */
  public static function getFriendsApiUrl($uid, $token)
  {
    return sprintf("https://graph.facebook.com/%s/friends?access_token=%s", $uid, $token);
  }

  /**
   * @param string $uid
   * @param string $token
   * @return string (url)
   */
  public static function getGroupsApiUrl($uid, $token)
  {
    return sprintf("https://graph.facebook.com/%s/groups?access_token=%s", $uid, $token);
  }

  /**
   * @param string $uid
   * @param string $token
   * @return string (url)
   */
  public static function getEventsApiUrl($uid, $token)
  {
    return sprintf("https://graph.facebook.com/%s/events?access_token=%s", $uid, $token);
  }
}