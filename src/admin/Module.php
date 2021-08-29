<?php

namespace antikon\luyamoduletest\admin;

/**
 * Luyamoduletest Admin Module.
 *
 * File has been created with `module/create` command. 
 * 
 * @author Anton Ikonnikov (antikon2@yandex.ru)
 * @since 1.0.0
 */
class Module extends \luya\admin\base\Module
{

    /**
     * @inheritDoc
     */
    public $apis = [


        'api-luyamoduletest-people' => 'antikon\luyamoduletest\admin\apis\PeopleController',
        'api-luyamoduletest-degrees' => 'antikon\luyamoduletest\admin\apis\DegreesController',


    ];


    /**
     * @inheritDoc
     */
/*
    public function extendPermissionRoutes()
    {

    }
*/

    /**
     * @inheritDoc
     */
  /*
    public function getAdminAssets()
    {
        return [
            'antikon\conference\admin\assets\ConferenceAsset',
            //'antikon\conference\admin\assets\FileUploadAsset',
        ];
    }
*/


    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritDoc
     */
    public function getMenu()
    {
        return (new \luya\admin\components\AdminMenuBuilder($this))


            ->node('Test Module', 'people')

            ->group('Test group')

            ->itemApi('People',  'luyamoduletest/people/index',          'people',   'api-luyamoduletest-people')
            ->itemApi('Degrees', 'luyamoduletest/degrees/index',         'school',   'api-luyamoduletest-degrees');



    }



    /**
     * @inheritDoc
     */
    public static function onLoad()
    {

    }
}