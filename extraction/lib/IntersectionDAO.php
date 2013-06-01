<?php

require 'lib/Configuration.php';

/**
 * IntersectionDAO (Singleton DP)
 */
class IntersectionDAO
{
    private static $_instance;
    private $pdo;

    /**
     * Constructor
     *
     * @throws PDOException
     */ 
    protected function __construct()
    {
        $this->pdo = new PDO(
            Configuration::get('dsn'),
            Configuration::get('db_user'),
            Configuration::get('db_password')
        );
    }

    /**
     * Getter
     */
    public function getPDO()
    {
        return $this->pdo;
    }

    /**
     * Get Instance
     */
    public static function getInstance ()
    {
        if (!(self::$_instance instanceof self)) {   
            self::$_instance = new self();
        }
 
        return self::$_instance;
    }

    /**
     * Is User Known
     *
     * @param string $uid 
     * @return boolean true if the user is found in the database false otherwise
     */
    public static function isUserKnown($uid)
    {
        $query = sprintf("SELECT id` FROM `user` WHERE `facebook_user_id` = '%s'", $uid);

        return self::getInstance()->getPDO()->prepare($query)->columnCount() > 0;
    }

    /**
     * Store user
     *
     * @param array $userData
     * @return boolean true if the user was well inserted
     */
    public static function storeUser($user_data)
    {
        $now = new DateTime('now');
        $query = sprintf("INSERT INTO user(facebook_user_id, facebook_access_token, last_name, first_name, mail, gender, created_at) VALUES('%s','%s','%s','%s','%s','%s', '%s')",
            $user_data['facebook_user_id'],
            $user_data['facebook_access_token'],
            $user_data['last_name'],
            $user_data['first_name'],
            $user_data['mail'],
            $user_data['gender'],
            $now->format('Y-m-d H:i:s')
        );

        return self::getInstance()->getPDO()->exec($query) > 0;
    }
}