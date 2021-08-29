<?php

namespace antikon\luyamoduletest\admin\controllers;



use luya\admin\ngrest\base\Controller;

/**
 * Conf People Controller.
 *
 * @author Anton Ikonnikov <antikon2@yandex.ru>
 * @since 1.0.0
 */
class PeopleController extends Controller
{
    /**
     * @inheritDoc
     */
    public $modelClass = 'antikon\luyamoduletest\models\People';

}