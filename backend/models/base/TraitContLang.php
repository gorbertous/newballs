<?php

namespace backend\models\base;

use Yii;

/**
 * Trait TraitContLang
 *
 * how to implement in the model:
 *
 * - @property string $Name_FR
 * - @property string $Name_DE
 * - @property string $Name_EN
 *
 * - use TraitContLang;
 * - add specific rules:
 *   return self::ContLangRules([ existing_rules ]);
 * - add label translation
 *   self::BTLabels(), self::ContLangLabels(), existing_labels and remove BT labels
 * - add list of fields where we need more languages:
 *   public static function ContLangAttributes() {
 *       return ['Name', 'Description'];
 *   }
 * - add getter for field
 *   public function getName() {
 *       return $this['Name'.Yii::$app->session->get('_content_language')];
 *   }
 * - add getter for field with fallback
 *   public function getNameFB() {
 *       return $this->ContLangFieldValueFB('Name');
 *   }
 * - run import/migrate to add all the missing fields to the tables
 *
 * how to implement in the searchmodel:
 * - add specific rules:
 *   return self::ContLangRules([ existing_rules ]);
 * - replace andFilterWhere with a like:
 *   Workunits::ContLangConcat('Name')
 *
 * how to implement in the index gridview:
 * - no need to include the label
 * - change column definition
 *   'value' => function($model) {
 *       return $model->ContLangFieldValueFB('Name');
 *   },
 *
 * @package backend\models\base
 *
 * @gorbertous
 */
trait TraitContLang
{
    /**
     * @param string $fieldname
     * @return string
     */
    public function ContLangFieldValue(string $fieldname)
    {
        if (is_a(Yii::$app, 'yii\web\Application')) {
            return $this[$fieldname . Yii::$app->session->get('_content_language')];
        } else {
            return $this[$fieldname . '_' . strtoupper(Yii::$app->language)];
        }
    }

    /**
     * getter for attribute $fieldname, according to current language
     *
     * -> if current language is not empty return current language
     * -> fallback1 if primary language is not empty return primary language
     * -> fallback2 return FR language
     *
     * @param string $fieldname
     * @return string
     */
    public function ContLangFieldValueFB(string $fieldname): string
    {
        if (is_a(Yii::$app, 'yii\web\Application')) {
            $curlan = Yii::$app->session->get('_content_language');
            $fb1lan = Yii::$app->session->get('_fallback1_language');
            $fb2lan = Yii::$app->session->get('_fallback2_language');
        } else {
            $curlan = $fb1lan = $fb2lan = '_' . strtoupper(Yii::$app->language);
        }
        
        if (!empty($this[$fieldname . $curlan])) {
            return $this[$fieldname . $curlan];
        } elseif (!empty($this[$fieldname . $fb1lan])) {
            return '#' . $this[$fieldname . $fb1lan] . '#';
        } else {
            return '#' . $this[$fieldname . $fb2lan] . '#';
        }
    }

    /**
     * getter for attribute $fieldname SQL expression, according to current language
     *
     * -> if current language is not empty return current language
     * -> fallback1 if primary language is not empty return primary language
     * -> fallback2 return FR language
     *
     * @param string $fieldname
     * @param bool $stripasname
     * @return string
     */
    public static function ContLangFieldValueFBsql(string $fieldname, bool $stripasname = false): string
    {
        if (is_a(Yii::$app, 'yii\web\Application')) {
            $curlan = Yii::$app->session->get('_content_language');
            $fb1lan = Yii::$app->session->get('_fallback1_language');
            $fb2lan = Yii::$app->session->get('_fallback2_language');
        } else {
            $curlan = $fb1lan = $fb2lan = '_' . strtoupper(Yii::$app->language);
        }
        if (($curlan == $fb1lan) && ($curlan == $fb2lan)) {
            return $fieldname . $curlan;
        } elseif ($fb1lan == $fb2lan) {
            return "COALESCE(NULLIF(" . $fieldname . $curlan . ",''), "
                . "CONCAT('#'," . $fieldname . $fb1lan . ",'#'))" .
                ($stripasname ? '' : ' as ' . $fieldname . '_EN');
        } else {
            return "COALESCE(NULLIF(" . $fieldname . $curlan . ",''), "
                . "NULLIF(CONCAT('#'," . $fieldname . $fb1lan . ",'#'),'##'), "
                . "CONCAT('#'," . $fieldname . $fb2lan . ",'#'))" .
                ($stripasname ? '' : ' as ' . $fieldname . '_EN');
        }
    }

    /**
     * getter for helper db tables
     *
     * UI $languages drop-down options, use current Yii::$app->language
     * -> fallback return FR language
     *
     * @param string $fieldname
     * @param bool $stripasname
     * @return string
     */
    public static function ContLangAllFieldValuesFBsql(string $fieldname, bool $stripasname = false): string
    {
        $curlan = $fb1lan = $fb2lan = '_' . strtoupper(Yii::$app->language);
        $club_languages = Yii::$app->contLang->defaultClubLanguages;
        if (count($club_languages) > 0) {
            $fb2lan = '_' . array_pop($club_languages);
        }
        if (count($club_languages) > 0) {
            $fb1lan = '_' . array_pop($club_languages);
        }
        
     
        if (($curlan == $fb1lan) && ($curlan == $fb2lan)) {
            return $fieldname . $curlan;
        } elseif ($fb1lan == $fb2lan) {
            return "COALESCE(NULLIF(" . $fieldname . $curlan . ",''), "
                . "CONCAT('#'," . $fieldname . $fb1lan . ",'#'))" .
                ($stripasname ? '' : ' as ' . $fieldname . '_EN');
        } else {
            return "COALESCE(NULLIF(" . $fieldname . $curlan . ",''), "
                . "NULLIF(CONCAT('#'," . $fieldname . $fb1lan . ",'#'),'##'), "
                . "CONCAT('#'," . $fieldname . $fb2lan . ",'#'))" .
                ($stripasname ? '' : ' as ' . $fieldname . '_EN');
        }
    }

    /**
     * getter for attribute $fieldname SQL expression, according to current language
     *
     * @param string $fieldname
     * @return string
     */
    public static function ContLangFieldName(string $fieldname): string
    {
        if (is_a(Yii::$app, 'yii\web\Application')) {
            $curlan = Yii::$app->session->get('_content_language');
        } else {
            $curlan = '_' . strtoupper(Yii::$app->language);
        }

        return $fieldname . $curlan;
    }

    /**
     * returns array(EN, DE, FR) for use in the copylibrary
     *
     * @param string $fieldname
     * @param null $value
     * @return array
     */
    public static function ContLangAllFieldNames(string $fieldname, $value = null): array
    {
        $concat = [];

        if ($value === null) {
            // return normal array with value = fieldname
            foreach (Yii::$app->contLang->languages as $iso) {
                $concat[] = $fieldname . '_' . $iso;
            }
        } else {
            // return associative array with key = fieldname and value = parameter
            foreach (Yii::$app->contLang->languages as $iso) {
                $concat[$fieldname . '_' . $iso] = $value;
            }
        }

        return $concat;
    }


    /**
     * merges the model rules with the ContLangAttributes
     *
     * @param array $rules
     * @return array
     */
    public function ContLangRules(array $rules): array
    {
        $addattr = self::ContLangAttributes();

        $manlan = [strtoupper(Yii::$app->language)];
       
        if (is_a(Yii::$app, 'yii\web\Application')) {
//            if ($this->hasAttribute('ID_Mandant') && $this->ID_Mandant == 0) {
                $manlan = Yii::$app->contLang->languages;
//            } else {
//                $manlan = Yii::$app->session->get('club_languages');
//            }
        }
        $prilan = array_shift($manlan);
        
        foreach ($rules as $krule => $vrule) {
            if (!is_array($vrule[0])) {
                // we have a single field
                $vrule[0] = [$vrule[0]];
            }
            if ($vrule[1] == 'required') {
                foreach ($vrule[0] as $kfield => $vfield) {
                    if (in_array($vfield, $addattr)) {
                        // make only the main language required
                        $rules[$krule][0][$kfield] = $vfield . '_' . $prilan;
                    }
                }
            } else {
                foreach ($vrule[0] as $kfield => $vfield) {
                    if (in_array($vfield, $addattr)) {
                        // replace by the main language
                        $rules[$krule][0][$kfield] = $vfield . '_' . $prilan;
                        // add the additional languages
                        foreach ($manlan as $addlan) {
                            $rules[$krule][0][] = $vfield . '_' . $addlan;
                        }
                    }
                }
            }
        }
        return $rules;
    }

    /**
     * merges the model labels with the ContLangAttributes
     *
     * @return array
     */
    public function ContLangLabels(): array
    {
        $addattr = self::ContLangAttributes();

        if (is_a(Yii::$app, 'yii\web\Application')) {
            $curlan = Yii::$app->session->get('_content_language');
//            if ($this->hasAttribute('ID_Mandant') && $this->ID_Mandant == 0) {
//                $manlan = Yii::$app->contLang->languages;
//            } else {
                $manlan = Yii::$app->session->get('club_languages');
//            }

        } else {
            $curlan = '_' . strtoupper(Yii::$app->language);
            $manlan = [strtoupper(Yii::$app->language)];
        }

        $labels = [];

        foreach ($addattr as $fieldname) {
            $trans = Yii::t('modelattr', $fieldname);
            foreach ($manlan as $iso) {
                if ($curlan == '_' . $iso) {
                    $labels[$fieldname . '_' . $iso] = $trans;
                } else {
                    $labels[$fieldname . '_' . $iso] = $trans . '-' . $iso;
                }
            }
        }
        return $labels;
    }

    /**
     * returns CONCAT(EN, DE, EN) for use in the Searchmodels
     *
     * @param string $fieldname
     * @return string
     */
    public static function ContLangConcat(string $fieldname): string
    {
        $concat = [];
        if (is_a(Yii::$app, 'yii\web\Application')) {
            $manlan = Yii::$app->session->get('club_languages');
        } else {
            $manlan = [strtoupper(Yii::$app->language)];
        }
        
        $manlan = [strtoupper(Yii::$app->language)];

        foreach ($manlan as $iso) {
            $concat[] = 'COALESCE(' . $fieldname . '_' . $iso . ',\'\')';
        }

        return 'CONCAT(' . join(',', $concat) . ')';
    }

}
