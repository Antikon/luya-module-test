<?php

namespace antikon\luyamoduletest\models;



use luya\admin\helpers\I18n;
use luya\admin\ngrest\base\NgRestModel;
use luya\admin\ngrest\plugins\Color;
use luya\admin\ngrest\plugins\SelectArray;
use luya\admin\ngrest\plugins\SelectArrayGently;
use luya\admin\ngrest\plugins\SortRelationArray;
use luya\admin\ngrest\plugins\ToggleStatus;
use luya\helpers\ArrayHelper;
use luya\helpers\Html;
use yii\base\InvalidArgumentException;

/**
 * Class for some Users.
 *
 *
 * @author Anton Ikonnikov <antikon2@yandex.ru>
 * @since 1.0.0
 */
class People extends NgRestModel
{

    // ------------ Cache -------
    /**
     * @var array Array with all users
     */
    public static $_peoplesArray = [];

    /**
     * @inheritDoc
     */
    public $i18n = ['name', 'surname', 'middle_name', 'initials'];

    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return '{{%luyamoduletest_people}}';
    }

    /**
     * @inheritDoc
     */
    public static function ngRestApiEndpoint()
    {
        return 'api-luyamoduletest-people';
    }

    /**
     * @inheritDoc
     */
    public function __construct($config = [])
    {

        parent::__construct($config);
    }



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
    public function attributeLabels()
    {
        return [
            //'zoom_email'     => 'Zoom email'),
            'sequentialIndex'     => '#',
            'id'                  => 'ID',
            'email'               => 'Email',
            'surname'             => 'Surname',
            'name'                => 'Name',
            'middle_name'         => 'Middle name',
            'degree_id'           => 'Degree',
            'fullName'            => 'Full Name',

        ];
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            // Required

            [
                [
                    //'zoom_email',
                    'email',
                    'surname',
                    'name',
                    'gender'
                ],
                'required',
                'on' => [
                    self::SCENARIO_DEFAULT,
                ]
            ],

            // Filters

            [
                [
                    //'zoom_email',
                    'email',

                    'surname',
                    'name',
                    'middle_name',
                ],
                'trim',
                'skipOnEmpty'     => true,
            ],


            // Field Specific
            [
                [
                    //'zoom_email',
                    'email'
                ], 'email'
            ],
            [
                [
                    'email'
                ], 'unique'
            ],

            [
                [
                    'gender',
                    'degree_id',
                ],
                'integer'
            ],

            [
                [
                    //'zoom_email',
                    'email',

                    'surname',
                    'name',
                    'middle_name',
                ],
                'string',
                'max' => 255,
            ],

            [
                ['degree_id'],
                'exist',
                'skipOnEmpty'     => true,
                'targetClass'     => Degrees::class,
                'targetAttribute' => ['degree_id' => 'id']
            ],

            // Defaults
            ['degree_id', 'default', 'value' => null],

        ];
    }

    /**
     * @inheritDoc
     */
    public function ngRestAttributeTypes()
    {
        return [

            //'zoom_email'       => 'text',
            'email'       => 'text',
            'surname'     => 'text',
            'name'        => 'password',
            'middle_name' => 'text',
            'gender'      => [
                'class'     => ToggleStatus::class,
                'cellColor' => "gender ? '#456789' : color",
                //'data'      => self::getGendersArray(),

                //'initValue' => 0
            ],

            'degree_id' => [
                'class'     => SelectArrayGently::class,
                'data'      => Degrees::getDegreesArray(),
                'cellColor' => 'gender ? "red" : "rgba(127, 255, 76, .2)"', //'item.color', //item.degree_id + item.gender > 2 ? '#123456' : '#987654'",//'item.degree_id == 1 ? "#457884" : "#545634"',
                'sortField' => [
                    'asc'  => ['(cdegrees.degree_short->"$.' . \Yii::$app->composition->langShortCode . '")' => SORT_ASC,],
                    'desc' => ['(cdegrees.degree_short->"$.' . \Yii::$app->composition->langShortCode . '")' => SORT_DESC,],
                ],
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function extraFields()
    {
        return [
            'degreeText',
            'fullName',
            'emailWithLink',
            'color'
        ];
    }

    /**
     * @inheritDoc
     */
    public function ngRestExtraAttributeTypes()
    {
/*
        $lang            = $this->language;
        $defaultLanguage = $this->defaultLanguage;
*/
        return [
            'sequentialIndex' => [
                'index',
                'sortField' => false
            ],

            'fullName' => [
                'text',
                /*
                'sortField' => [
                    'asc'  => [
                        '(surname->"$.' . $lang . '")'                => SORT_ASC,
                        '(name->"$.' . $lang . '")'                   => SORT_ASC,
                        '(middle_name->"$.' . $lang . '")'            => SORT_ASC,
                        '(surname->"$.' . $defaultLanguage . '")'     => SORT_ASC,
                        '(name->"$.' . $defaultLanguage . '")'        => SORT_ASC,
                        '(middle_name->"$.' . $defaultLanguage . '")' => SORT_ASC,
                    ],
                    'desc' => [
                        '(surname->"$.' . $lang . '")'                => SORT_DESC,
                        '(name->"$.' . $lang . '")'                   => SORT_DESC,
                        '(middle_name->"$.' . $lang . '")'            => SORT_DESC,
                        '(surname->"$.' . $defaultLanguage . '")'     => SORT_DESC,
                        '(name->"$.' . $defaultLanguage . '")'        => SORT_DESC,
                        '(middle_name->"$.' . $defaultLanguage . '")' => SORT_DESC,
                    ],
                ]
                */
            ],

            'emailWithLink' => [
                'html',
                'sortField' => 'email'
            ],
            'color' => [
                'color',
                'hideInList' => true
            ]

        ];
    }

    /**
     * @inheritDoc
     */
    public function ngRestFullQuerySearch($query)
    {
        return parent::ngRestFullQuerySearch($query)->joinWith(['degree cdegrees']);
    }

    /**
     * @inheritDoc
     */
    public function genericSearchFields()
    {
        return [
            'surname',
            'name',
            'middle_name',
            'email',
        ];
    }

    /**
     * @inheritDoc
     */
    public function ngRestScopes()
    {
        return [
            [
                'list',
                [
                    'sequentialIndex',
                    'fullName',
                    'email',
                    'emailWithLink',
                    'gender',
                    'degree_id',
                    'color'
                ]
            ],
            [
                'create',
                [
                    'email',
                    'surname',
                    'name',
                    'middle_name',
                    'gender',
                    'degree_id',
                ]
            ],
            [
                'update',
                [
                    'email',
                    'surname',
                    'name',
                    'middle_name',
                    'gender',
                    'degree_id',
                ]
            ],
            ['delete', true],
        ];
    }



    /**
     * @relation Returns user's degree object.
     *
     */
    public function getDegree()
    {
        return $this->hasOne(Degrees::class, ['id' => 'degree_id']);
    }



    /**
     * Returns sorted list of users. User id is a key, full name is a value.
     *
     * @param array $options Not used now.
     * @return array
     * @see ConfHelper::makeFullName()
     */
    public static function getPeoplesArray($options = [])
    {
        // Hit the cache?
        if (!empty(self::$_peoplesArray)) {
            return self::$_peoplesArray;
        }

        $lang            = \Yii::$app->composition->langShortCode;
        $people      = self::find()->asArray()
                           ->select(['id', 'surname', 'name', 'middle_name'])
                           ->orderBy(
                               [
                                   '(surname->"$.' . $lang . '")'     => SORT_ASC,
                                   '(name->"$.' . $lang . '")'        => SORT_ASC,
                                   '(middle_name->"$.' . $lang . '")' => SORT_ASC,
                               ]
                           )
                           ->all();
        $resultArray = [];
        foreach ($people as $key => $data) {
            $fullName = self::makeFullName($data, $lang);
            $resultArray[$data['id']] = $fullName;
        }

        self::$_peoplesArray = $resultArray;

        return $resultArray;
    }





    /**
     * Returns list of genders.
     *
     * @return array
     */
    public static function getGendersArray()
    {
        return [
            0 => 'Male',
            1 => 'Female',
        ];
    }








    /**
     * Returns user's full name (surname, name and middle_name)
     *
     * @return string
     * @see ConfHelper::makeFullName()
     */
    public function getFullName()
    {
        return self::makeFullName($this);
    }


    public function getColor()
    {
        return '#67F792';
    }


    /**
     * Returns user's email with hyperlink to it.
     *
     * @return string
     */
    public function getEmailWithLink()
    {
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $html = '<a href="mailto:' . $this->email . '">' . Html::encode($this->email) . '</a>';
        } else {
            $html = '<span class="error">' . Html::encode($this->email) . '</span>';
        }

        return $html;
    }

    /**
     * Returns gender name
     *
     * @return string
     */
    public function getGenderName()
    {
        return ArrayHelper::getValue(self::getGendersArray(), $this->gender);
    }


    public static function makeFullName ($user, string $lang = null, $normalOrder = false)
    {
        if ($user instanceof People) {

            $user = ArrayHelper::toArray($user, [
                'antikon\luyamoduletest\models\People' => [
                    'name',
                    'middle_name',
                    'surname',
                ],
            ]);

        }


        if (!is_array($user)) throw new InvalidArgumentException('Data must be array or instance of People');

        $langShortCode = $lang ? $lang : \Yii::$app->composition->langShortCode;
        $defaultLanguage = 'en';

        $nameArr = ArrayHelper::getValue($user, 'name');
        if (is_array($nameArr) === true) {
            $convertedName = ArrayHelper::getValue($nameArr, $langShortCode);
            if (!$convertedName) {
                $convertedName = ArrayHelper::getValue($nameArr, $defaultLanguage);
            }
        } else {
            $convertedName = I18n::decodeFindActive($nameArr, I18n::decodeFindActive($nameArr, $nameArr, $defaultLanguage), $langShortCode);
        }

        $middleNameArr = ArrayHelper::getValue($user, 'middle_name');
        if (is_array($middleNameArr) === true) {
            $convertedMiddleName = ArrayHelper::getValue($middleNameArr, $langShortCode);
            if (!$convertedMiddleName) {
                $convertedMiddleName = ArrayHelper::getValue($middleNameArr, $defaultLanguage);
            }
        } else {
            $convertedMiddleName = I18n::decodeFindActive($middleNameArr, I18n::decodeFindActive($middleNameArr, $middleNameArr, $defaultLanguage), $langShortCode);
        }


        $surnameArr = ArrayHelper::getValue($user, 'surname');
        if (is_array($surnameArr) === true) {
            $convertedSurname = ArrayHelper::getValue($surnameArr, $langShortCode);
            if (!$convertedSurname) {
                $convertedSurname = ArrayHelper::getValue($surnameArr, $defaultLanguage);
            }
        } else {
            $convertedSurname = I18n::decodeFindActive($surnameArr, I18n::decodeFindActive($surnameArr, $surnameArr, $defaultLanguage), $langShortCode);
        }

        if ($normalOrder === true) {
            $output = trim(trim($convertedName.' '.$convertedMiddleName).' '.$convertedSurname);
        } else {
            $output = trim($convertedSurname.' '.trim($convertedName.' '.$convertedMiddleName));
        }


        return $output;
    }

}