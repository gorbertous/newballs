<?php


namespace common\widgets;

use Yii;
use yii\helpers\Url;

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

        $html .= 
                 '<ul class="list-inline">' .
                 '<li class="list-inline-item"><i class="fas fa-globe"></i></li>';

        $html .= "<li class='list-inline-item selected'><a>{$this->displayLangs[$currentLang]}</a></li>";

        foreach (Yii::$app->urlManager->languages as $language) {
            // do not display the selected language
            if ($language !== $currentLang) {
                $params['language'] = $language;
                $html .= "<li class='list-inline-item'><a href='" . Url::to($params) . "'>{$this->displayLangs[$language]}</a></li>";
            }
        }

        $html .= 
                 '</ul>';

        return $html;
    }
}