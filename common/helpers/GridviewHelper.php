<?php

namespace common\helpers;


use Yii;
//use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\export\ExportMenu;
use backend\models\Texts;

/**
 * Trait GridviewHelper
 *
 * @author gorbertous
 * @package common\helpers
 */
class GridviewHelper
{
    
    /**
     * @param $template
     * @param $currentBtn
     * @param null $urlCreator
     * @return array
     */
    public static function getActionColumn($template, &$currentBtn, $urlCreator = null)
    {
        $atemplate = [];
        $buttons = [];

        preg_match_all('/\{([^}]+)\}/', $template, $atemplate);

        foreach ($atemplate[0] as $btn) {

            switch ($btn) {

                case '{view}':
                    $buttons['view'] = function ($url, $model) use ($currentBtn) {
                        return Html::button('<i class="fas fa-eye"></i>', [
                            'value' => Url::to($url),
                            'class' => 'btn btn-outline-secondary showModalButton',
                            'title' => $currentBtn['view_label'] .  ' ' . $model->titleSuffix
                        ]);
                    };
                    break;

                // ################# //

                case '{print_card_qr}':
                    $buttons['print_card_qr'] = function ($url, $model) use ($currentBtn) {
                        preg_match_all('!\d+!', $url, $getInt);
                        $link = explode('/',  $url);
                        return Html::a('<i class="fas fa-id-card"></i>', 'authorizations/print/' . $getInt[0][0] . '?mode=print&view=card', [
                            'class'     => 'btn btn-outline-secondary btn-style',
                            'title'     => $currentBtn['print_label'] . ' ' . $model->titleSuffix,
                            'data-pjax' => '0',
                            'target'    => '_blank'
                        ]);
                    };
                    break;

                // ################# //

                case '{update}':
                    $buttons['update'] = function ($url, $model) use ($currentBtn) {
//                        if (Yii::$app->user->can('writer')) {
                            return Html::button('<i class="fas fa-edit"></i>', [
                                'value' => Url::to($url),
                                'class' => 'btn btn-outline-secondary showModalButton',
                                'title' => $currentBtn['mod_label'] . ' ' . $model->titleSuffix
                            ]);
//                        }
                        return '';
                    };
                    break;

                // ################# //

                case '{delete}':
                    $buttons['delete'] = function ($url, $model) use ($currentBtn) {
//                        if (Yii::$app->user->can('writer')) {
                            return Html::a('<i class="fas fa-trash-alt"></i>', Url::to($url), [
                                'class'        => 'btn btn-outline-secondary',
                                'title'        => $currentBtn['del_label'] . ' ' . $model->titleSuffix,
                                'data-confirm' => Yii::t('yii', 'Are you sure to delete this item?'),
                                'data-method'  => 'post'
                            ]);
//                        }
                        return '';
                    };
                    break;

                // ################# //

                case '{print}':
                    $buttons['print'] = function ($url, $model) use ($currentBtn) {
                        return Html::a('<i class="fas fa-print"></i>', Url::to($url) . '?mode=print&view=one', [
                            'class'     => 'btn btn-outline-secondary',
                            'title'     => $currentBtn['print_label'] . ' ' . $model->titleSuffix,
                            'data-pjax' => '0',
                            'target'    => '_blank'
                        ]);
                    };
                    break;

                // ################# //

                case '{printselect}':
                    $buttons['printselect'] = function ($url) use ($currentBtn) {
                        return Html::button('<i class="fas fa-print"></i>', [
                            'value' => Url::to($url),
                            'class' => 'btn btn-outline-secondary showModalButton',
                            'title' => Yii::t('app', 'List of printable documents')
                        ]);
                    };
                    break;

                // ################# //

                case '{passresetemail}':
                    $buttons['passresetemail'] = function ($url) use ($currentBtn) {
                        if (Yii::$app->user->can('writer')) {
                            return Html::a('<i class="fas fa-envelope"></i>', Url::to($url), [
                                'class' => 'btn btn-outline-secondary',
                                'title' => Yii::t('app', 'Send password reset email')
                            ]);
                        }
                        return '';
                    };
                    break;

            }

        }

        return [
            'class'          => 'yii\grid\ActionColumn',
            'template'       => '<div class="btn-group">' . $template . '</div>',
            'buttons'        => $buttons,
            'urlCreator'     => $urlCreator,
            'contentOptions' => [
                'style' => 'width: 220px; min-width: 200px; text-align: center;'
            ]
        ];
    }

    /**
     * get the title of the page
     *
     * @param $context_array
     * @return string
     */
    public static function getTitle(&$context_array)
    {
        return $context_array['title1'] . ' ' . $context_array['title2'];
    }

    /**
     * get the current button array based on the current route
     *
     * @param $context_array
     * @param bool $overwriteroute
     * @return array
     */
    public static function getCurrentBtn(&$context_array, $overwriteroute = false)
    {
        $route = Yii::$app->controller->route;
        

        if ($overwriteroute) {
            // special case for the library (kartik treeview)
            $route = $overwriteroute;
        }

        foreach ($context_array as $ca) {
            if (is_array($ca)) {
                if (isset($ca['link'])) {
                    $link = trim($ca['link'], '/');
                    if ($link == $route) {
                        return $ca;
                    }
                }

                if (isset($ca['lib_link'])) {
                    $link = trim($ca['lib_link'], '/');
                    if ($link == $route) {
                        return $ca;
                    }
                }
            }
        }

        return [];
    }

    /**
     * @param $currentBtn
     * @return string
     */
    public static function getPrinta(&$currentBtn)
    {
        return Html::a('<i class="fas fa-print"></i> ' . $currentBtn['print_btntext'],
            Url::toRoute([
                $currentBtn['print_btnlink'],
                'id'   => 0,
                'mode' => 'print',
                'view' => 'all'
            ]), [
                'class'     => 'btn btn-success',
                'title'     => $currentBtn['print_label'],
                'data-pjax' => '0',
                'target'    => '_blank'
            ]
        );
    }

    /**
     * @param $currentBtn
     * @return string
     */
    public static function getNewbutton(&$currentBtn)
    {
       
        if (Yii::$app->user->can('writer')) {
            return Html::button('<i class="fas fa-plus"></i>', [
                'value' => Url::toRoute($currentBtn['create']),
                'class' => 'btn btn-success showModalButton',
                'title' => $currentBtn['new_label']
            ]);
        }

        return '';
    }

    
    /**
     * @param $currentBtn
     * @return string
     */
    public static function getResetgrida(&$currentBtn)
    {
       
        return Html::a('<i class="fas fa-redo"></i>', [
            Url::to(($currentBtn['link']))
        ], [
                'data-pjax' => 0,
                'class'     => 'btn btn-secondary',
                'title'     => Yii::t('diag', 'Reset Grid')
            ]
        );
    }

    /**
     * @param int $pendinguploads
     * @return string
     */
    public static function getLangsyncbutton($pendinguploads = 0)
    {
        return Html::Button('<i class="fas fa-sync"></i>&nbsp;' .
            '<span id="syncmessagespan">' . ($pendinguploads == 0 ? '' : $pendinguploads . ' Up') . '</span>', [
                'class'     => 'btn btn-primary float-right',
                'id'        => 'syncmessage',
                'data-pjax' => '0'
            ]
        );
    }

    /**
     * @param $context_array
     *
     * @return string
     *
     * @throws \Exception
     */
    public static function getHeader(&$context_array)
    {
        // INFO BUTTON
        // display info button with text from texts table
        $infobtn = Texts::infoPopoverLg(
            'i_' . Yii::$app->controller->id, Yii::t('modelattr', 'Help text')
        );

        // PANEL HEADER + FILTERS
        return
            '<i class="' . $context_array['ti_icon1'] . '"></i> ' . $context_array['title1'] . '&nbsp;' .
            '<i class="' . $context_array['ti_icon2'] . '"></i> ' . $context_array['title2'] . ' '. $infobtn;
    }

    /**
     * @param $context_array
     * @param $currentBtn
     * @return array
     */
    public static function getLefttoolbar(&$context_array, &$currentBtn)
    {

        $lefttoolbar = [];
        
        // LEFT TOOLBAR
        foreach ($context_array as $btn) {
            if (is_array($btn) && isset($btn['button_title'])) {
                $lefttoolbar[] = Html::a('<i class="' . $btn['fa_icon'] . '"></i> ' . $btn['button_title'], [$btn['link']], [
                    'class'     => 'btn ' . ($btn === $currentBtn ? 'btn-primary disabled' : 'btn-default'),
                    'data-pjax' => 0,
                    'data-role' => 'link'
                ]);

            }

        }
        return $lefttoolbar;
    }
    
      /**
     * @return string
     */
    public static function getPanelBefore()
    {
        $panelBeforeTemplate = '{lefttoolbar}' .
            '<div class="float-right btn-toolbar kv-grid-toolbar" role="toolbar">' .
            '{toolbar}' .
            '</div>' .

            '{before}' .

            '<div class="clearfix"></div>';
        return $panelBeforeTemplate;
    }
    
      /**
     * @return string
     */
    public static function getExportMenu($dataProvider, $gridColumn)
    {
        return ExportMenu::widget([ 
            'dataProvider' => $dataProvider, 
            'columns' => $gridColumn, 
            'target' => ExportMenu::TARGET_BLANK, 
            'fontAwesome' => true, 
            'showConfirmAlert' => false,
            'showColumnSelector' => false,
            'exportConfig' => [
                ExportMenu::FORMAT_TEXT => false,
                ExportMenu::FORMAT_PDF => [
                    'pdfConfig' => [
                        'orientation' => 'L',
                    ],
                ],
            ],
            'dropdownOptions' => [ 
                'label' => Yii::t('yii', 'Export'),
                'class' => 'btn btn-outline-secondary', 
                'itemsBefore' => [ 
                    '<li class="dropdown-header">Export All Data</li>', 
                ], 
            ], 
        ]);
    }

   
}
