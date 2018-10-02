<?php

namespace backend\models\base;

use Yii;
use kartik\popover\PopoverX;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * This is the model class for table "texts".
 *
 * @property int $text_id
 * @property string $code
 * @property string $text_EN
 * @property string $text_FR
 * @property string $text_DE
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 */
class Texts extends ActiveRecord
{
    
    use TraitBlameableTimestamp;
    use TraitContLang;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return self::ContLangRules([
            [['code'], 'required'],
            [['text'], 'string'],
            [['code'], 'string', 'max' => 50]
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'texts';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(
            self::BTLabels(), self::ContLangLabels(), [
            'code'       => Yii::t('app', 'Code')
        ]);
    }

    public static function ContLangAttributes()
    {
        return ['text'];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return self::BTbehaviors();
    }

    public function getTitleSuffix()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->ContLangFieldValue('text');
    }

    /**
     * @return string
     */
    public function getTextFB()
    {
        return $this->ContLangFieldValueFB('text');
    }

    /**
     * @inheritdoc
     * @return \backend\models\TextsQuery
     */
    public static function find()
    {
        /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
        return new \backend\models\TextsQuery(get_called_class());
    }

    /**
     * @param $code
     * @param null $part
     *
     * @return mixed|string
     */
    public static function getTextBlock($code, $part = null)
    {
        // try to find mandant specific text
        $model = self::find()
            ->andWhere(['code' => $code])
            ->one();

        if (empty($model)) {
            // if empty get library text
            $model = self::find()
                ->andWhere(['code' => $code])
                ->one();
        }

        if (empty($model)) {
            $text = '';
        } else {
            $text = $model->textFB;
        }

        if ($part !== null) {
            $atext = explode(":", $text);

            if ($part >= count($atext)) {
                $text = '';
            } else {
                $text = $atext[$part];
            }
        }

        return $text;
    }

    /**
     * @param $code
     *
     * @return string
     */
    public static function getHotlink($code)
    {
        // try to find mandant specific text
        $model = self::find()
            ->andWhere(['code' => $code])
            ->one();

        if (empty($model)) {
            // if empty get library text
            $model = self::find()
                ->andWhere(['code' => $code])
                ->one();
        }

        if (empty($model)) {
            $hotlink = '';
        } else {
            $hotlink = Html::button('<i class="fa fa-pencil"></i>' . $code, [
                'value' => Url::toRoute(['texts/update', 'id' => $model->text_id]),
                'class' => 'showModalButton btn btn-default btn-xs',
                'title' => Yii::t('app', 'Update')
            ]);
        }

        return $hotlink;
    }

    /**
     * @param $Code
     * @param $header
     *
     * @return string
     * @throws \Exception
     */
    public static function infoPopoverLg($Code, $header)
    {
        $content = self::getTextBlock($Code);
        $hotlink = self::getHotlink($Code);

        if (empty($hotlink)) {
            $hotlink = 'No text with Code <strong><u>' . $Code . '</u></strong>';
        }

        // provide hotlink to edit this text
        if (Yii::$app->user->can('team_member')) {
            return PopoverX::widget([
                'header'       => $header . ' ' . $hotlink,
                'type'         => PopoverX::TYPE_INFO,
                'size'         => PopoverX::SIZE_LARGE,
                'placement'    => PopoverX::ALIGN_BOTTOM,
                'content'      => $content,
                'toggleButton' => ['label' => '<i class="fa fa-info i info"></i>',
                                   'class' => 'btn-info-i'],
            ]);
        } else {
            if (empty($content)) {
                return '';
            } else {
                return PopoverX::widget([
                    'header'       => $header,
                    'type'         => PopoverX::TYPE_INFO,
                    'size'         => PopoverX::SIZE_LARGE,
                    'placement'    => PopoverX::ALIGN_BOTTOM,
                    'content'      => $content,
                    'toggleButton' => ['label' => '<i class="fa fa-info i info"></i>',
                                       'class' => 'btn-info-i'],
                ]);
            }
        }
    }

    /**
     * @param $Code
     * @param $roleClass
     *
     * @return string
     */
    public static function infoText($Code, $roleClass)
    {
        $content = self::getTextBlock($Code);

        if (empty($content)) {
            $content = 'No text with Code ' . $Code . '';
        }

        if (Yii::$app->user->can('team_member')) {
            $hotlink = self::getHotlink($Code);

            if (empty($hotlink)) {
                $hotlink = 'No text with Code <strong><u>' . $Code . '</u></strong>';
            }
        } else {
            $hotlink = '';
        }

        $html = <<<HTML
        <div class="alert alert-{$roleClass}">
            <span class="fa fa-info i info icon"></span>
    
            <span class="text">
            {$content} {$hotlink}
            </span>
    
            <div class="clearfix"></div>
        </div>
HTML;

        // provide hotlink to edit this text
        if (Yii::$app->user->can('team_member')) {
            return $html;
        } else {
            if (empty($html)) {
                return '';
            } else {
                return $html;
            }
        }
    }
}
