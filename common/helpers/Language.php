<?php

namespace common\helpers;

use yii\helpers\Url;
//use yii\helpers\StringHelper;
use backend\models\Sourcemessage;
use yii;

/**
 * Language helper.
 *
 * Inserts the necessary JavaScripts for client side multilingual support into the content.
 *
 */
class Language
{

//    private static $_template = '<span class="language-item" data-category="{category}" data-hash="{hash}" data-language_id="{language_id}" data-params="{params}">{message}</span>';
    private static $_labeltemplate = '<span class="language-item" data-link="{url}">{message}</span>';
    private static $_master = 'balls-tennis.com';

    public static function MasterName()
    {
        return self::$_master;
    }

    public static function LocalName()
    {
        return Yii::$app->request->hostName;
    }

    public static function IsMaster()
    {
        return strtolower(self::LocalName()) === strtolower(self::MasterName());
    }

    public static function navbarmenuitem(): array
    {
        if (YII_ENV_DEV) {
            return [
                'label'   => '<i class="fa fa-globe fa-lg"></i>',
                'options' => ['class' => 'translate-toggle'],
            ];
        }
        return [];
    }

    /**
     * TRANSLATE TEXT WITH TRANSLATION MARKUP
     * @param string $category the message category.
     * @param string $message the message to be translated.
     * @param array $params the parameters that will be used to replace the corresponding placeholders in the message.
     * @param string $language the language code (e.g. `en-US`, `en`).
     * If this is null, the current [[\yii\base\Application::language|application language]] will be used.
     * @return string the translated message.
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        if (YII_ENV_DEV) {
            $source = SourceMessage::find()
                ->select('id')
                ->where(['category' => $category])
                ->andWhere(['message' => $message])
                ->one();
            if (!isset($source)) {
                // no translation available, create it
                $source = new SourceMessage();
                $source->category = $category;
                $source->message = $message;
                $source->save();
            }
            return strtr(self::$_labeltemplate, [
                '{url}'     => Url::toRoute(['message/update', 'id' => $source->id, 'id2' => $language ?: Yii::$app->language]),
                '{message}' => Yii::t($category, $message, $params, $language),
            ]);
        } else {
            return Yii::t($category, $message, $params, $language);
        }
    }

    /**
     * TRANSLATE TEXT WITHOUT MARKUP (STRIP TRANSLATE MARKUP)
     * @param string $category the message category.
     * @param string $message the message to be translated.
     * @param array $params the parameters that will be used to replace the corresponding placeholders in the message.
     * @param string $language the language code (e.g. `en-US`, `en`).
     * If this is null, the current [[\yii\base\Application::language|application language]] will be used.
     * @return string the translated message.
     */
    public static function ts($category, $message, $params = [], $language = null)
    {
        if (YII_ENV_DEV) {
            $message_id = Sourcemessage::find()
                ->select('ID')
                ->where(['category' => $category])
                ->andWhere(['message' => $message])
                ->scalar();
            return Yii::t($category, $message, $params, $language) . ' (' . $message_id . ')';
        } else {
            return Yii::t($category, $message, $params, $language);
        }
    }

    /**
     * HTML DECODE THE LABEL PART OF THE ACTIVEFIELD
     * @param activefield $field
     * @return string the html output that represents the field, with the label html decoded
     */
    public static function labdec($field)
    {
        $parts = $field->render();
        if (YII_ENV_DEV) {
            $label_prefix = substr($parts, 0, strpos($parts, '>', strpos($parts, '<label') + 6) + 1);
            $label_suffix = substr($parts, strpos($parts, '</label>'));
            $label = substr($parts, strlen($label_prefix), -strlen($label_suffix));
            return $label_prefix . htmlspecialchars_decode($label) . $label_suffix;
        } else {
            return $parts;
        }
    }

}
