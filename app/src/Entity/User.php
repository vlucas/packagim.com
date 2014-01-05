<?php
namespace Entity;
use App;
use Hyperspan;

class User extends App\Entity
{
    // Table
    protected static $_datasource = "users";
    protected static $_formFields = ['name'];
    protected static $_formFieldOptions = [
        'email' => ['type' => 'email'],
        'password' => ['type' => 'password']
    ];

    /**
     * Fields
     */
    public static function fields()
    {
        return [
            'id' => ['type' => 'int', 'primary' => true],
            'name' => ['type' => 'string', 'required' => true],
            'email' => ['type' => 'string'],
            'is_admin' => ['type' => 'boolean', 'default' => false],
            'date_created' => ['type' => 'datetime', 'default' => new \DateTime()],
            'date_modified' => ['type' => 'datetime', 'default' => new \DateTime()]
        ] + parent::fields();
    }

    /**
     * Is user logged-in?
     *
     * @return boolean
     */
    public function isLoggedIn()
    {
        return $this->__get('id') ? true : false;
    }

    /**
     * Is user admin? (Has all rights)
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return (boolean) $this->__get('is_admin');
    }

    /**
     * Hyperspan output
     */
    public function toResponse($view = null)
    {
        $app = app();

        $res = new Hyperspan\Response();
        $res->class = ['staff'];
        $res->title = $this->name;
        $res->setProperties(parent::dataExcept(['session', 'date_created', 'date_modified']));
        $res->addLink('self', $app->url('/users/' . $this->id . ($view ? '?view=' . $view : '')));
        return $res;
    }
}

