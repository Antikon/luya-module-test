<?php

namespace antikon\luyamoduletest\admin\controllers;

use luya\admin\ngrest\base\Controller;

/**
 * Conf Degrees Controller.
 *
 * @author Anton Ikonnikov <antikon2@yandex.ru>
 * @since 1.0.0
 */
class DegreesController extends Controller
{
    /**
     * @inheritDoc
     */
    public $modelClass = 'antikon\luyamoduletest\models\Degrees';
}