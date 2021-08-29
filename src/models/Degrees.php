<?php

namespace antikon\luyamoduletest\models;


use luya\admin\helpers\I18n;
use luya\admin\ngrest\base\NgRestModel;
use luya\helpers\ArrayHelper;


/**
 * Reference for Conference User Degrees.
 *
 *
 * @author Anton Ikonnikov <antikon2@yandex.ru>
 * @since 1.0.0
 */
class Degrees extends NgRestModel
{

    // ------------ Cache -------
    /**
     * @var array Array with all degrees
     */
    private static $_degreesArray = [];

    /**
     * @var array List of type ids related to Ph.D.
     */
    private static $_candidatesDegreesIds = [];

    /**
     * @var array List of type ids related to Dr.Sci.
     */
    private static $_doctorsDegreesIds    = [];


    /**
     * @inheritDoc
     */
    public $i18n = ['degree_name', 'degree_short'];

    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return '{{%luyamoduletest_degrees}}';
    }

    /**
     * @inheritDoc
     */
    public static function ngRestApiEndpoint()
    {
        return 'api-luyamoduletest-degrees';
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'Degree ID',
            'degree_order' => 'Degree order',
            'degree_name'  => 'Degree Name',
            'degree_short' => 'Short name',
            'is_candidate' => 'Candidate of sciences equivalent',
            'is_doctor'    => 'Doctor of sciences equivalent',
            'is_active'    => 'Is active?',
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            // Required
            [['degree_name', 'degree_short', 'degree_order'], 'required'],

            // Filters
            [['degree_name', 'degree_short'], 'filter', 'filter' => 'trim'],

            // Field Specific
            [['degree_order'], 'integer'],
            [['degree_order'], 'unique'],
            [['is_active', 'is_candidate', 'is_doctor'], 'boolean'],
            [['degree_name', 'degree_short'], 'string', 'max' => 255],

        ];
    }

    /**
     * @inheritDoc
     */
    public function ngRestAttributeTypes()
    {
        return [
            'degree_order' => 'number',
            'degree_name'  => 'text',
            'degree_short' => 'text',

            'is_candidate' => [
                'toggleStatus',
                'falseIcon' => '-',
            ],
            'is_doctor'    => [
                'toggleStatus',
                'falseIcon' => '-',
            ],
            'is_active'    => [
                'toggleStatus',
                'falseIcon' => '-',
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function ngRestScopes()
    {
        return [
            ['list', ['degree_order', 'degree_name', 'degree_short', 'is_candidate', 'is_doctor', 'is_active']],
            [
                ['create', 'update'],
                ['degree_order', 'degree_name', 'degree_short', 'is_candidate', 'is_doctor', 'is_active']
            ],
            ['delete', true],
        ];
    }

    /**
     * Returns list of Ph.D. similar degrees ids.
     *
     * @return array
     */
    public static function getCandidatesDegreesIds()
    {
        if (self::$_candidatesDegreesIds !== []) {
            $array = self::$_candidatesDegreesIds;
        } else {
            $array = self::find()
                         ->select(['id'])
                         ->where(['is_active' => 1])
                         ->andWhere(['is_candidate' => 1])
                         ->orderBy('degree_order')
                         ->column();

            $array = array_map(
                function ($value) {
                    return intval($value);
                },
                $array
            );

            self::$_candidatesDegreesIds = $array;
        }
        return $array;
    }


    /**
     * Returns list of Dr.Sci. similar degrees ids.
     *
     * @return array
     */
    public static function getDoctorsDegreesIds()
    {
        if (self::$_doctorsDegreesIds !== []) {
            $array = self::$_doctorsDegreesIds;
        } else {
            $array = self::find()
                         ->select(['id'])
                         ->where(['is_active' => 1])
                         ->andWhere(['is_doctor' => 1])
                         ->orderBy('degree_order')
                         ->column();

            $array = array_map(
                function ($value) {
                    return intval($value);
                },
                $array
            );

            self::$_doctorsDegreesIds = $array;
        }
        return $array;
    }




    /**
     * Returns an array of degrees names [id => name].
     *
     * If `'short' => true` is set in `options`, then name will be present in short version.
     * Otherwise, name will be in full length.
     *
     * @param array $options list of options
     * @return array
     */
    public static function getDegreesArray(array $options = [])
    {
        if (ArrayHelper::getValue($options, 'short') == true) {
            if ($r = ArrayHelper::getValue(self::$_degreesArray, 'short')) {
                return $r;
            } else {
                $array = self::find()
                             ->select(['degree_short'])
                             ->where(['is_active' => 1])
                             ->orderBy('degree_order')
                             ->indexBy('id')
                             ->column();

                $resultArray = [];
                foreach ($array as $key => $data) {
                    $resultArray[$key] = I18n::decodeFindActive($data, $data);
                }
                self::$_degreesArray['short'] = $resultArray;
            }
        } else {
            if ($r = ArrayHelper::getValue(self::$_degreesArray, 'full')) {
                return $r;
            } else {
                $array = self::find()
                             ->select(['degree_name'])
                             ->where(['is_active' => 1])
                             ->orderBy('degree_order')
                             ->indexBy('id')
                             ->column();

                $resultArray = [];
                foreach ($array as $key => $data) {
                    $resultArray[$key] = I18n::decodeFindActive($data, $data);
                }
                self::$_degreesArray['full'] = $resultArray;
            }
        }
        return $resultArray;
    }
}