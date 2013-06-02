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
  
  /**
   * @param string $uid
   *
   * @return string $token
   */
  public function getUserToken($uid)
  {
    return IntersectionDAO::getUserToken($uid);
  }

