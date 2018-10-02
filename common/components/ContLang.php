<?php

namespace common\components;

use yii\base\BaseObject;

/**
 * Class ContLang
 *
 * @package common\components
 */
class ContLang extends BaseObject
{

    /**
     * list of available ISO language codes.
     * for all these languages, we need separate fields in the models
     *
     * @var array
     */
    public $languages = ['EN', 'FR', 'DE'];

    /**
     * if nothing is defined at mandant level, the mandant falls back to these
     * languages
     *
     * @var array
     */
    public $defaultClubLanguages = ['FR', 'DE', 'EN'];

    /**
     * list of all available languages for this mandant
     * set in frontend/controllers/SiteController::actionSetupSession
     *
     * -> Yii::$app->session->set('club_languages')
     */
    /**
     * Currently displayed language for this mandant
     * set in frontend/controllers/TraitController::beforeAction
     * every time a index page is displayed (because the UI language might be
     * changed with the codemix component)
     * after a test if UI language is available for this mandant
     * otherwise fall back to the main language
     *
     * -> Yii::$app->session->set('_content_language')
     */
}
