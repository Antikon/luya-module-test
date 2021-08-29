<?php

namespace antikon\luyamoduletest\admin\apis;

use luya\admin\ngrest\base\Api;

/**
 * Conf Degrees Controller.
 *
 * @author Anton Ikonnikov <antikon2@yandex.ru>
 * @since 1.0.0
 */
class DegreesController extends Api
{
    /**
     * @inheritDoc
     */
    public $modelClass = 'antikon\luyamoduletest\models\Degrees';
}