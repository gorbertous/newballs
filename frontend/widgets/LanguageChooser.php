<?php

namespace frontend\widgets;

use Yii;
use yii\helpers\Url;

/**
 * Class LanguageChooser
 * @package frontend\widgets
 */
class LanguageChooser extends \yii\base\Widget
{
    /**
     * If we have more languages just add it
     *
     * @var array
     */
    protected $displayLangs = [
        'de' => 'Deutsch',
        'en' => 'English',
        'fr' => 'Français'
    ];

    /**
     * Displays all the languages and shows the current selected language as the first element on the menu
     *
     * @inheritdoc
     */
    public function run()
    {
        $currentLang  = Yii::$app->language;
        $route        = Yii::$app->controller->route;
        $params       = Yii::$app->request->get();
        array_unshift($params, $route);

        $html = '';

        $html .= '<nav id="languages">' .
                 '<ul>' .
                 '<li><i class="fa fa-globe"></i></li>';

        $html .= "<li class='selected'><a>{$this->displayLangs[$currentLang]}</a></li>";

        foreach (Yii::$app->urlManager->languages as $language) {
            // do not display the selected language
            if ($language !== $currentLang) {
                $params['language'] = $language;
                $html .= "<li><a href='" . Url::to($params) . "'>{$this->displayLangs[$language]}</a></li>";
            }
        }

        $html .= '</nav>' .
                 '</ul>';

        return $html;
    }
}