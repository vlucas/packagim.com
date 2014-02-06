<?php
namespace Entity;
use App;
use Hyperspan;

class Package extends App\Entity
{
    // Table
    protected static $_datasource = "packages";

    /**
     * Fields
     */
    public static function fields()
    {
        return [
            'id' => ['type' => 'int', 'primary' => true, 'serial' => true],
            'name' => ['type' => 'string', 'required' => true, 'unique' => 'name_source'],
            'description' => ['type' => 'string', 'required' => true],
            'url' => ['type' => 'string', 'required' => true],
            'download_count' => ['type' => 'int', 'default' => 0],
            'favorites_count' => ['type' => 'int', 'default' => 0],
            'source' => ['type' => 'string', 'required' => true, 'unique' => 'name_source'],
            'language' => ['type' => 'string', 'required' => true],
            'date_created' => ['type' => 'datetime', 'default' => new \DateTime()],
            'date_modified' => ['type' => 'datetime', 'default' => new \DateTime()]
        ] + parent::fields();
    }

    /**
     * Hyperspan output
     */
    public function toResponse($view = null)
    {
        $app = app();

        $res = new Hyperspan\Response();
        $res->class = ['package'];
        $res->title = $this->name;
        $res->setProperties(parent::dataExcept([]));
        $res->addLink('self', $app->url('package/' . $this->name));
        return $res;
    }
}

