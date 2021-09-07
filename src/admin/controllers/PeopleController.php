<?php

namespace antikon\luyamoduletest\admin\controllers;



use antikon\conference\frontend\Module;
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


    public function actionIndex(
        $inline = false,
        $relation = false,
        $arrayIndex = false,
        $modelClass = false,
        $modelSelection = false
    ) {
        $output = '';

        $output .= $this->renderPartial(
            'angularDirectives',
            [
            ]
        );


        $output .= parent::actionIndex($inline, $relation, $arrayIndex, $modelClass, $modelSelection);

        return $output;
    }

}