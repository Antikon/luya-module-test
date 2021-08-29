<?php

namespace antikon\luyamoduletest\admin\apis;

use luya\admin\ngrest\base\Api;

/**
 * Conf Abstracts Controller.
 *
 * @author Anton Ikonnikov <antikon2@yandex.ru>
 * @since 1.0.0
 */
class PeopleController extends Api
{
    /**
     * {@inheritdoc}
     */
    public $modelClass = 'antikon\luyamoduletest\models\People';

    /**
     * @inheritDoc
     */
    /*
    public function withRelations()
    {
        return [
            'index'  => ['requestedSection', 'requestedAddSection', 'finalSection', 'requestedType', 'finalType'],
            'list'   => ['requestedType', 'finalType'],
            'search' => ['requestedType', 'finalType'],
        ];
    }
    */

}