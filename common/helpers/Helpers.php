<?php

namespace common\helpers;

use Yii;
use yii\helpers\Html;
use backend\models\Config;

/**
 * Class Helpers
 * @package common\helpers
 */
class Helpers
{
    /**
     * checks if a path exists, if not creates it recursively
     *
     * @param $path
     * @return bool
     */
    public static function createPath($path)
    {
        if (is_dir($path)) {
            return true;
        }

        $prev_path = substr($path, 0, strrpos($path, DIRECTORY_SEPARATOR, -2) + 1);
        $return = self::createPath($prev_path);
        return ($return && is_writable($prev_path)) ? mkdir($path) : false;
    }

    /**
     * If $value is empty return empty value if not returns the actual value
     *
     * @param string $value
     * @return string
     */
    public static function isEmpty($value)
    {
        return !empty($value) ? $value : '';
    }

    /**
     * Validates integer
     *
     * @param int $value
     * @return bool ( 0's, floats and negative values will return false )
     */
    public static function isInt($value)
    {
        return (intval($value) > 0);
    }
    
    /**
     * Audit tab
     *
     * @return string
     */
    public static function getAuditTab()
    {
        $tab = '<li><a href="#audit" data-toggle="tab">' .Yii::t('appMenu', 'Audit') . '</a></li>';
        return Yii::$app->user->can('admin') ? $tab : '';
    }
    
    /**
     * Audit tab content
     * @param object $model
     * @return string
     */
    public static function getAuditTabContent($model)
    {
        $tabcontent = '<div class="tab-pane well" id="audit"><div class="row"><div class="col-md-12">';
        $tabcontent .=  \asinfotrack\yii2\audittrail\widgets\AuditTrail::widget([
                                'model' => $model,
                                // some of the optional configurations
                                'userIdCallback' => function ($userId, $model) {
                                        return empty($userId) ? '' : \common\models\User::findOne($userId)->fullname;
                                },
                                'changeTypeCallback'=>function ($type, $model) {
                                        return Html::tag('span', strtoupper($type), ['class'=>'label label-info']);
                                },
                                'dataTableOptions'=>['class'=>'table table-condensed table-bordered'],
                        ]);
        $tabcontent .= '</div></div></div>';                        
        
        return Yii::$app->user->can('admin') ? $tabcontent : '';
    }

    /**
     * Returns the buttons for the modals
     *
     * @param \yii\db\ActiveRecord $model
     * @param int|null             $id
     * @param string|null          $printType
     * @param array                $buttons
     *
     * @return string
     */
    public static function getModalFooter($model, int $id = null, string $printType = null, array $buttons)
    {
        $html = '<div class="clear"></div>' .
                '<div class="form-group pull-right">';

        foreach ($buttons['buttons'] as $btnKey => $btnValue) {
            switch ($btnValue) {
                case 'create_update':
                    $html .= Html::submitButton('<span class="fa fa-check"></span>&nbsp;' . ($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update')), [
                        'class' => 'btn btn-success'
                    ]);
                    break;

                case 'save':
                    $html .= '&nbsp;' . Html::a('<span class="fa fa-floppy-o"></span>&nbsp;' . Yii::t('app', 'Save'), [
                            'print',
                            'id'   => $id,
                            'mode' => 'save',
                            'view' => $printType
                        ], [
                            'class'  => 'btn btn-info',
                            'target' => '_blank'
                        ]);
                    break;

                case 'print':
                    $html .= '&nbsp;' . Html::a('<span class="fa fa-print"></span>&nbsp;' . Yii::t('app', 'Print'), [
                            'print',
                            'id'   => $id,
                            'mode' => 'print',
                            'view' => $printType
                        ], [
                            'class'  => 'btn btn-primary',
                            'target' => '_blank'
                        ]);
                    break;

                case 'cancel':
                    $html .= '&nbsp;' . Html::Button('<span class="fa fa-times"></span>&nbsp;' . Yii::t('app', 'Cancel'), [
                            'class'        => 'btn btn-danger',
                            'data-dismiss' => 'modal'
                        ]);
                    break;

                default:
                    $html .= '';
            }
        }

        $html .= '</div>' .
                 '<div class="clear"></div>';

        if (Yii::$app->request->get('mode') !== 'print') {
            return $html;
        }
        return '';
    }

    /**
     * @param $key1
     * @param $key2
     * @param int|null $mandant_id
     * @param int|null $user_id
     * @return mixed|null
     */
    public static function getConfig($key1, $key2, int $mandant_id = null, int $user_id = null)
    {
        if ($mandant_id === null) {
            $mandant_id = Yii::$app->session->get('mandant_id');
        }

        if ($user_id === null) {
            $config = Config::findOne([
                'ID_Mandant' => $mandant_id,
                'Key1'       => $key1,
                'Key2'       => $key2
            ]);

            // fallback to our library mandant
            if (empty($config)) {
                $config = Config::findOne([
                    'ID_Mandant' => 0,
                    'Key1'       => $key1,
                    'Key2'       => $key2
                ]);
            }
        } else {
            $config = Config::findOne([
                'ID_Mandant' => $mandant_id,
                'ID_User'    => $user_id,
                'Key1'       => $key1,
                'Key2'       => $key2
            ]);

            // fallback to our library mandant
            if (empty($config)) {
                $config = Config::findOne([
                    'ID_Mandant' => 0,
                    'ID_User'    => $user_id,
                    'Key1'       => $key1,
                    'Key2'       => $key2
                ]);
            }
        }

        if ($config !== null) {
            return !empty($config->Value) ? $config->Value : null;
        }

        return null;
    }

    /**
     * @param $key1
     * @param $key2
     * @param int|null $mandant_id
     * @param $value
     * 
     * @return bool
     */
    public static function setConfig($key1, $key2, int $mandant_id = null, $value): bool
    {
        if ($mandant_id === null) {
            $mandant_id = Yii::$app->session->get('mandant_id');
        }

        $config = Config::find()
            ->where(['ID_Mandant' => $mandant_id,
                     'Key1'       => $key1,
                     'Key2'       => $key2
            ])
            ->one();

        // doesn't exist create new
        if (empty($config)) {
            $config = new Config();
            $config->ID_Mandant = $mandant_id;
            $config->Key1 = $key1;
            $config->Key2 = $key2;
            $config->Value = $value;
        } else {
            //update the value only
            $config->Value = $value;
        }

        return $config->save(false);
    }

    /**
     * @param array $fields
     * @param array $params
     * @return string
     */
    public static function generateHtmlTable(array $fields, array $params): string
    {
        $params['options']['font-size'] = empty($params['options']['font-size']) ? '13px' : $params['options']['font-size'];
        $params['options']['thWidth'] = empty($params['options']['thWidth']) ? '140px' : $params['options']['thWidth'];

        $table = "";

        $count = 0;

        $table .= '<table class="table lgv-table no-border b-right b-bottom no-first-width thead-centered" style="margin: 0; padding: 0; font-size: ' . $params['options']['font-size'] . ';">';

        // create thead
        if (!empty($fields['thead'])) {
            $table .= '<thead>';
            $table .= '<tr>';

            foreach ($fields['thead'] as $key) {
                if (!empty($key)) {
                    $table .= '<th style="width: ' . $params['options']['thWidth'] . ';">' . $key . '</th>';
                }
            }

            $table .= '</tr>';
            $table .= '</thead>';
        }

        // create tbody
        if (!empty($fields['tbody'])) {
            $table .= '<tbody>';
            $table .= '<tr>';

            foreach ($fields['tbody'] as $key => $value) {
                if (!empty($value)) {
                    $count++;

                    if (!isset($params['options']['deleteArrayKeys'])) {
                        $table .= '<th style="width: ' . $params['options']['thWidth'] . ';">' . $key . '</th>';
                    }

                    $table .= '<td>' . $value . '</td>';

                    if ($count % $params['options']['numberColumns'] === 0) {
                        $table .= '</tr><tr>';
                    }
                }
            }

            $table .= '</tr>';
            $table .= '</tbody>';
        }

        $table .= '</table>';

        return str_replace('<tr></tr>', '', $table);
    }

    /**
     * @return string
     */
    public static function getTinyMceLanguage()
    {
        switch (Yii::$app->language) {
            case 'fr':
                $language = 'fr_FR';
                break;
            case 'en':
                $language = 'en_GB';
                break;
            case 'de':
                $language = 'de';
                break;
            default:
                $language = 'en_GB';
                break;
        }

        return $language;
    }

    /**
     * @param int $max_height
     * @return array
     */
    public static function getTinyMceClientOptions($max_height = -1)
    {
        $clientOptions = [
            'plugins'       => [
                'paste', 'lists', 'table', 'pagebreak', 'autoresize'
            ],
            'menu'          => [],
            'statusbar'     => false,
            'paste_as_text' => true,
            'toolbar'       => "undo redo pastetext | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table pagebreak"
            // 'table_default_border' => 1,
            // 'table_default_cellspacing' => 0,
            // 'table_default_cellpadding' => 0,
            // 'readonly' => true
        ];

        if ($max_height > 0) {
            $clientOptions['autoresize_max_height'] = $max_height;
        }

        return $clientOptions;
    }

    /**
     * @param int $max_height
     * @return array
     */
    public static function getTinyMceClientOptionsHTML($max_height = -1)
    {
        $clientOptions = [
            'plugins'       => [
                'paste', 'lists', 'table', 'pagebreak', 'autoresize', 'code'
            ],
            'menu'          => [],
            'statusbar'     => false,
            'paste_as_text' => true,
            'toolbar'       => "undo redo pastetext | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table pagebreak | code"
            // 'table_default_border' => 1,
            // 'table_default_cellspacing' => 0,
            // 'table_default_cellpadding' => 0,
            // 'readonly' => true
        ];

        if ($max_height > 0) {
            $clientOptions['autoresize_max_height'] = $max_height;
        }

        return $clientOptions;
    }

    /**
     * Replace language-specific characters by ASCII-equivalents.
     * @param string $s
     * @return string
     */
    public static function normalise($s)
    {
        $replace = array(
            'ъ' => '-', 'Ь' => '-', 'Ъ' => '-', 'ь' => '-',
            'Ă' => 'A', 'Ą' => 'A', 'À' => 'A', 'Ã' => 'A', 'Á' => 'A', 'Æ' => 'A', 'Â' => 'A', 'Å' => 'A', 'Ä' => 'Ae',
            'Þ' => 'B',
            'Ć' => 'C', 'ץ' => 'C', 'Ç' => 'C',
            'È' => 'E', 'Ę' => 'E', 'É' => 'E', 'Ë' => 'E', 'Ê' => 'E',
            'Ğ' => 'G',
            'İ' => 'I', 'Ï' => 'I', 'Î' => 'I', 'Í' => 'I', 'Ì' => 'I',
            'Ł' => 'L',
            'Ñ' => 'N', 'Ń' => 'N',
            'Ø' => 'O', 'Ó' => 'O', 'Ò' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'Oe',
            'Ş' => 'S', 'Ś' => 'S', 'Ș' => 'S', 'Š' => 'S',
            'Ț' => 'T',
            'Ù' => 'U', 'Û' => 'U', 'Ú' => 'U', 'Ü' => 'Ue',
            'Ý' => 'Y',
            'Ź' => 'Z', 'Ž' => 'Z', 'Ż' => 'Z',
            'â' => 'a', 'ǎ' => 'a', 'ą' => 'a', 'á' => 'a', 'ă' => 'a', 'ã' => 'a', 'Ǎ' => 'a', 'а' => 'a', 'А' => 'a', 'å' => 'a', 'à' => 'a', 'א' => 'a', 'Ǻ' => 'a', 'Ā' => 'a', 'ǻ' => 'a', 'ā' => 'a', 'ä' => 'ae', 'æ' => 'ae', 'Ǽ' => 'ae', 'ǽ' => 'ae',
            'б' => 'b', 'ב' => 'b', 'Б' => 'b', 'þ' => 'b',
            'ĉ' => 'c', 'Ĉ' => 'c', 'Ċ' => 'c', 'ć' => 'c', 'ç' => 'c', 'ц' => 'c', 'צ' => 'c', 'ċ' => 'c', 'Ц' => 'c', 'Č' => 'c', 'č' => 'c', 'Ч' => 'ch', 'ч' => 'ch',
            'ד' => 'd', 'ď' => 'd', 'Đ' => 'd', 'Ď' => 'd', 'đ' => 'd', 'д' => 'd', 'Д' => 'D', 'ð' => 'd',
            'є' => 'e', 'ע' => 'e', 'е' => 'e', 'Е' => 'e', 'Ə' => 'e', 'ę' => 'e', 'ĕ' => 'e', 'ē' => 'e', 'Ē' => 'e', 'Ė' => 'e', 'ė' => 'e', 'ě' => 'e', 'Ě' => 'e', 'Є' => 'e', 'Ĕ' => 'e', 'ê' => 'e', 'ə' => 'e', 'è' => 'e', 'ë' => 'e', 'é' => 'e',
            'ф' => 'f', 'ƒ' => 'f', 'Ф' => 'f',
            'ġ' => 'g', 'Ģ' => 'g', 'Ġ' => 'g', 'Ĝ' => 'g', 'Г' => 'g', 'г' => 'g', 'ĝ' => 'g', 'ğ' => 'g', 'ג' => 'g', 'Ґ' => 'g', 'ґ' => 'g', 'ģ' => 'g',
            'ח' => 'h', 'ħ' => 'h', 'Х' => 'h', 'Ħ' => 'h', 'Ĥ' => 'h', 'ĥ' => 'h', 'х' => 'h', 'ה' => 'h',
            'î' => 'i', 'ï' => 'i', 'í' => 'i', 'ì' => 'i', 'į' => 'i', 'ĭ' => 'i', 'ı' => 'i', 'Ĭ' => 'i', 'И' => 'i', 'ĩ' => 'i', 'ǐ' => 'i', 'Ĩ' => 'i', 'Ǐ' => 'i', 'и' => 'i', 'Į' => 'i', 'י' => 'i', 'Ї' => 'i', 'Ī' => 'i', 'І' => 'i', 'ї' => 'i', 'і' => 'i', 'ī' => 'i', 'ĳ' => 'ij', 'Ĳ' => 'ij',
            'й' => 'j', 'Й' => 'j', 'Ĵ' => 'j', 'ĵ' => 'j', 'я' => 'ja', 'Я' => 'ja', 'Э' => 'je', 'э' => 'je', 'ё' => 'jo', 'Ё' => 'jo', 'ю' => 'ju', 'Ю' => 'ju',
            'ĸ' => 'k', 'כ' => 'k', 'Ķ' => 'k', 'К' => 'k', 'к' => 'k', 'ķ' => 'k', 'ך' => 'k',
            'Ŀ' => 'l', 'ŀ' => 'l', 'Л' => 'l', 'ł' => 'l', 'ļ' => 'l', 'ĺ' => 'l', 'Ĺ' => 'l', 'Ļ' => 'l', 'л' => 'l', 'Ľ' => 'l', 'ľ' => 'l', 'ל' => 'l',
            'מ' => 'm', 'М' => 'm', 'ם' => 'm', 'м' => 'm',
            'ñ' => 'n', 'н' => 'n', 'Ņ' => 'n', 'ן' => 'n', 'ŋ' => 'n', 'נ' => 'n', 'Н' => 'n', 'ń' => 'n', 'Ŋ' => 'n', 'ņ' => 'n', 'ŉ' => 'n', 'Ň' => 'n', 'ň' => 'n',
            'о' => 'o', 'О' => 'o', 'ő' => 'o', 'õ' => 'o', 'ô' => 'o', 'Ő' => 'o', 'ŏ' => 'o', 'Ŏ' => 'o', 'Ō' => 'o', 'ō' => 'o', 'ø' => 'o', 'ǿ' => 'o', 'ǒ' => 'o', 'ò' => 'o', 'Ǿ' => 'o', 'Ǒ' => 'o', 'ơ' => 'o', 'ó' => 'o', 'Ơ' => 'o', 'œ' => 'oe', 'Œ' => 'oe', 'ö' => 'oe',
            'פ' => 'p', 'ף' => 'p', 'п' => 'p', 'П' => 'p',
            'ק' => 'q',
            'ŕ' => 'r', 'ř' => 'r', 'Ř' => 'r', 'ŗ' => 'r', 'Ŗ' => 'r', 'ר' => 'r', 'Ŕ' => 'r', 'Р' => 'r', 'р' => 'r',
            'ș' => 's', 'с' => 's', 'Ŝ' => 's', 'š' => 's', 'ś' => 's', 'ס' => 's', 'ş' => 's', 'С' => 's', 'ŝ' => 's', 'Щ' => 'sch', 'щ' => 'sch', 'ш' => 'sh', 'Ш' => 'sh', 'ß' => 'ss',
            'т' => 't', 'ט' => 't', 'ŧ' => 't', 'ת' => 't', 'ť' => 't', 'ţ' => 't', 'Ţ' => 't', 'Т' => 't', 'ț' => 't', 'Ŧ' => 't', 'Ť' => 't', '™' => 'tm',
            'ū' => 'u', 'у' => 'u', 'Ũ' => 'u', 'ũ' => 'u', 'Ư' => 'u', 'ư' => 'u', 'Ū' => 'u', 'Ǔ' => 'u', 'ų' => 'u', 'Ų' => 'u', 'ŭ' => 'u', 'Ŭ' => 'u', 'Ů' => 'u', 'ů' => 'u', 'ű' => 'u', 'Ű' => 'u', 'Ǖ' => 'u', 'ǔ' => 'u', 'Ǜ' => 'u', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'У' => 'u', 'ǚ' => 'u', 'ǜ' => 'u', 'Ǚ' => 'u', 'Ǘ' => 'u', 'ǖ' => 'u', 'ǘ' => 'u', 'ü' => 'ue',
            'в' => 'v', 'ו' => 'v', 'В' => 'v',
            'ש' => 'w', 'ŵ' => 'w', 'Ŵ' => 'w',
            'ы' => 'y', 'ŷ' => 'y', 'ý' => 'y', 'ÿ' => 'y', 'Ÿ' => 'y', 'Ŷ' => 'y',
            'Ы' => 'y', 'ž' => 'z', 'З' => 'z', 'з' => 'z', 'ź' => 'z', 'ז' => 'z', 'ż' => 'z', 'ſ' => 'z', 'Ж' => 'zh', 'ж' => 'zh'
        );

        return strtr($s, $replace);
    }

    /**
     * renumbers the post variables, used in case we use dynamic model and some
     * subforms are deleted .e.g.:
     * 1. create 3 subforms -> post is [ form_0, form_1, form_2 ]
     * 2. delete first form -> post is [ form_1, form_2 ]
     * 3. validation does not pass if there are rules in the subforms
     * 4. pass Yii::$app->request->post() to this function
     * 5. returns [ form_0, form_1 ]
     * 
     * @param array $post -> collection of post data used for $model->load()
     * 
     * @return array
     */
    public static function reindex($post) :array
    {
        $index = 0;
        $ret = [];

        foreach ($post as $key => $value) {
            if (is_string($key)) {
                $newKey = $key;
            } else {
                $newKey = $index++;
            }
            $ret[$newKey] = is_array($value) ? self::reindex($value) : $value;
        }

        return $ret;
    }
}
