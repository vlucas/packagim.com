<?php
namespace Entity\User;
use Spot;

class Session extends Spot\Entity
{
    // Table
    protected static $_datasource = "user_session";

    /**
     * Fields
     */
    public static function fields()
    {
        return array(
            'id' => array('type' => 'int', 'primary' => true, 'serial' => true),
            'user_id' => array('type' => 'int', 'index' => 'user_session', 'required' => true),
            'session_id' => array('type' => 'string', 'required' => true, 'index' => 'user_session'),
            'date_created' => array('type' => 'datetime', 'default' => new \DateTime())
        ) + parent::fields();
    }

    /**
     * Relations
     */
    public static function relations()
    {
        return array(
            // User session/login
            'user' => array(
                'type' => 'HasOne', // Actually a 'BelongsTo', but that is currently not implemented in Spot
                'entity' => 'Entity\User',
                'where' => array('id' => ':entity.user_id')
            )
        ) + parent::relations();
    }

    /**
     * Return only field info that we want exposed in API 'fields'
     */
    public static function formFields()
    {
        $fields = array(
            array('name' => 'email', 'type' => 'string', 'required' => true),
            array('name' => 'password', 'type' => 'password', 'required' => true)
        );
        return $fields;
    }

    /**
     * Return Entity\User object with valid session key
     */
    public static function findUserBySessionId($mapper, $sessionKey)
    {
        $user = false;
        if($sessionKey && strpos($sessionKey, ':') !== false) {
            list($user_id, $session_id) = explode(':', $sessionKey);
            // Also use cookie value as session id for edge cases where cookie id is different from session value
            if(isset($_COOKIE['PHPSESSID'])) {
                $session_id = [$session_id, $_COOKIE['PHPSESSID']]; // Will use SQL IN caluse for arrays automatically
            }
            $session = $mapper->first('Entity\User\Session', compact('user_id', 'session_id'));
            if($session) {
                $user = $session->user->entity();
            }
        }
        return $user;
    }
}
