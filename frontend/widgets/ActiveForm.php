<?php

namespace backend\widgets;

use Yii;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use kartik\widgets\FileInput;
use kartik\checkbox\CheckboxX;
use kartik\datecontrol\DateControl;
use dosamigos\tinymce\TinyMce;
use common\helpers\Helpers;

/**
 * Class ActiveForm
 *
 * @author gorbertous
 * @since 1.0.0
 * @package backend\widgets
 */
class ActiveForm extends \kartik\widgets\ActiveForm
{
    /**
     * @see \yii\widgets\ActiveForm::field()
     *
     
     * @param \yii\base\Model $model
     * @param string $attribute
     * @param array $options
     *
     * @return \backend\widgets\ActiveField
     */
    protected function overwriteField($model, $attribute, $options = [])
    {
//        $this->fieldClass = 'backend\widgets\ActiveField';
//        $this->fieldConfig['class'] = 'backend\widgets\ActiveField';
        /* @var $newfield \backend\widgets\ActiveField */
        $newfield = parent::field($model, $attribute, $options);
//        $newfield->permission = $permission;
        return $newfield;
    }

    /**
     * @see overwriteField()
     *
     * @param \yii\base\Model $model
     * @param string $attribute
     * @param array $options
     *
     * @return \yii\widgets\ActiveField::textInput()
     */
    public function hrwTextInputMax(&$model, $attribute, $options = [])
    {
        $options['maxlength'] = true;
        return $this->overwriteField($model, $attribute)->textInput($options);
    }

    /**
     * @see overwriteField()
     *
     * @param $attribute
     * @param array $options
     *
     * @return array
     */
    public function hrwTextInputMaxOptions($attribute, $options = [])
    {
        $options['maxlength'] = true;
        return $options;
    }

    /**
     * @see overwriteField()
     *
     * @param $model
     * @param $attribute
     * @param array $options
     *
     * @return \yii\widgets\ActiveField::textarea()
     */
    public function hrwTextAreaMax(&$model, $attribute, $options = [])
    {
        $modelattribute = $attribute;
        // hack for dynamic models
        if (substr($attribute, 0, 1) == '[') {
            $modelattribute = substr($attribute, strpos($attribute, ']') + 1);
        }
        $h = ceil(substr_count($model->$modelattribute, PHP_EOL)) + 2;
        if ($h > 20) $h = 20;
        $options['maxlength'] = true;
        $options['rows'] = $h;
        return $this->overwriteField($model, $attribute)->textarea($options);
    }

    /**
     * @see overwriteField()
     *
     * @param $model
     * @param $attribute
     * @param array $options
     *
     * @return \yii\widgets\ActiveField::widget()
     */
    public function hrwSelect2(&$model, $attribute, $options)
    {
        if (!isset($options['options']['placeholder'])) {
            $options['options']['placeholder'] = '';
        }

        return $this->overwriteField($model, $attribute)->widget(Select2::class, $options);
    }

    /**
     * @param $model
     * @param $attribute
     * @param array $options
     *
     * @return array
     */
    public function hrwSelect2Options(&$model, $attribute, array $options)
    {
        if (!isset($options['options']['placeholder'])) {
            $options['options']['placeholder'] = '';
        }

        return $options;
    }

    /**
     * @see overwriteField()
     *
     * @param $model
     * @param $attribute
     * @param array $options
     *
     * @return \yii\widgets\ActiveField::widget()
     */
    public function hrwDepDropselect2(&$model, $attribute, $options = [])
    {
        if (!isset($options['type'])) {
            $options['type'] = DepDrop::TYPE_SELECT2;
        }
        if (!isset($options['pluginOptions']['placeholder'])) {
            $options['pluginOptions']['placeholder'] = '';
        }
        if (!isset($options['pluginOptions']['loadingText'])) {
            $options['pluginOptions']['loadingText'] = '...';
        }
        if (!isset($options['options']['placeholder'])) {
            $options['options']['placeholder'] = false;
        }

        return $this->overwriteField($model, $attribute)->widget(DepDrop::class, $options);
    }

    /**
     * @see overwriteField()
     *
     * @param $model
     * @param $attribute
     *
     * @throws \Exception
     * @throws \yii\base\UserException
     *
     * @return FileInput|string
     */
    public function hrwFileInput(&$model, $attribute)
    {
        /* @var $model \frontend\models\Mandants */
        $options = $model->getwidgetconfig($attribute);
       

        if (!isset($options['options']['id'])) {
            $options['options']['id'] = mt_rand();
        }

        return FileInput::widget($options);
    }

    /**
     * @see overwriteField()
     *
     * @param $model
     * @param $attribute
     *
     * @return \yii\widgets\ActiveField::checkbox()
     */
    public function hrwCheckbox(&$model, $attribute)
    {
      
        return $this->overwriteField($model, $attribute)->checkbox(['class' => 'input-lg']);
    }

    /**
     * @see overwriteField()
     *
     * @param $model
     * @param $attribute
     * @param array $options
     * @param null $disabled
     *
     * @return \yii\widgets\ActiveField::widget()
     */
    public function hrwCheckboxX(&$model, $attribute, $options = [], $disabled = null)
    {
       
        if (!isset($options['pluginOptions']['threeState'])) {
            $options['pluginOptions']['threeState'] = false;
        }
        if (!isset($options['autoLabel'])) {
            $options['autoLabel'] = true;
        }

        // if ($disabled === null) {
        //     if (!self::stringendswith_in_array($attribute, self::$editableFields)) {
        //         $options['disabled'] = true;
        //     }
        // } else {
        //     if ($disabled) {
        //         $options['disabled'] = true;
        //     } else {
        //         unset($options['disabled']);
        //     }
        // }

        return $this->overwriteField($model, $attribute)->widget(CheckboxX::class, $options)->label(false);
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     *
     * @param $model
     * @param $attribute
     * @param array $options
     * @param null $disabled
     *
     * @return array
     */
    public function hrwCheckboxXOptions(&$model, $attribute, $options = [], $disabled = null)
    {
       

        if (!isset($options['pluginOptions']['threeState'])) {
            $options['pluginOptions']['threeState'] = false;
        }
        if (!isset($options['autoLabel'])) {
            $options['autoLabel'] = true;
        }

        // if ($disabled === null) {
        //     if (!self::stringendswith_in_array($attribute, self::$editableFields)) {
        //         $options['disabled'] = true;
        //     }
        // } else {
        //     if ($disabled) {
        //         $options['disabled'] = true;
        //     } else {
        //         unset($options['disabled']);
        //     }
        // }

        return $options;
    }

    /**
     * @see overwriteField()
     *
     * @param $model
     * @param $attribute
     * @param array $options
     *
     * @return string|\yii\widgets\ActiveField
     */
    public function hrwTinyMce(&$model, $attribute, $options = [])
    {
       
        // enabled, send tinyMce
        if (!isset($options['language'])) {
            $options['language'] = Helpers::getTinyMceLanguage();
        }
        if (!isset($options['clientOptions'])) {
            $options['clientOptions'] = Helpers::getTinyMceClientOptionsHTML(350);
        }
        return $this->overwriteField($model, $attribute)->widget(TinyMce::class, $options);
    }


    /**
     * @see overwriteField()
     *
     * @param $model
     * @param $attribute
     * @param array $options
     *
     * @return \yii\widgets\ActiveField::widget()
     */
    public function hrwDateControl(&$model, $attribute, $options = [])
    {
        
        if (!isset($options['type'])) {
            $options['type'] = DateControl::FORMAT_DATE;
        }

        return $this->overwriteField($model, $attribute)->widget(DateControl::class, $options);
    }

    /**
     * @see overwriteField()
     *
     * @param $model
     * @param $attribute
     * @param array $options
     *
     * @return \yii\widgets\ActiveField::widget()
     */
    public function hrwTimeControl(&$model, $attribute, $options = [])
    {
        if (!isset($options['type'])) {
            $options['type'] = DateControl::FORMAT_TIME;
        }
        return $this->overwriteField($model, $attribute)->widget(DateControl::class, $options);
    }

    public function hrwIsReadOnlyCheckboxX(&$model)
    {
        if ($model->ID_Source == null) {
            return $this->field($model, 'IsReadonly')->hiddenInput()->label(false);
        } else {
            return $this->hrwCheckboxX($model, 'IsReadonly');
        }
    }
}