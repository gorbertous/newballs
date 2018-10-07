<?php

namespace common\helpers;

use backend\widgets\GridView;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use backend\models\Texts;

/**
 * Trait TraitIndex
 *
 * @author gorbertous
 * @package common\helpers
 */
trait TraitIndex
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
                        return Html::button('<i class="fa fa-eye"></i>', [
                            'value' => Url::to($url),
                            'class' => 'btn btn-default btn-style showModalButton',
                            'title' => $currentBtn['view_label'] . ' ' . $model->titleSuffix
                        ]);
                    };
                    break;

                // ################# //

                case '{print_card_qr}':
                    $buttons['print_card_qr'] = function ($url, $model) use ($currentBtn) {
                        preg_match_all('!\d+!', $url, $getInt);
                        $link = explode('/',  $url);
                        return Html::a('<i class="fa fa-id-card"></i>', 'authorizations/print/' . $getInt[0][0] . '?mode=print&view=card', [
                            'class'     => 'btn btn-default btn-style',
                            'title'     => $currentBtn['print_label'] . ' ' . $model->titleSuffix,
                            'data-pjax' => '0',
                            'target'    => '_blank'
                        ]);
                    };
                    break;

                // ################# //

                case '{update}':
                    $buttons['update'] = function ($url, $model) use ($currentBtn) {
                        if (Yii::$app->user->can('writer')) {
                            return Html::button('<i class="fa fa-pencil-square-o"></i>', [
                                'value' => Url::to($url),
                                'class' => 'btn btn-default btn-style showModalButton',
                                'title' => $currentBtn['mod_label'] . ' ' . $model->titleSuffix
                            ]);
                        }
                        return '';
                    };
                    break;

                // ################# //

                case '{delete}':
                    $buttons['delete'] = function ($url, $model) use ($currentBtn) {
                        if (Yii::$app->user->can('writer')) {
                            return Html::a('<i class="fa fa-trash"></i>', Url::to($url), [
                                'class'        => 'btn btn-default btn-style',
                                'title'        => $currentBtn['del_label'] . ' ' . $model->titleSuffix,
                                'data-confirm' => Yii::t('yii', 'Are you sure to delete this item?'),
                                'data-method'  => 'post'
                            ]);
                        }
                        return '';
                    };
                    break;

                // ################# //

                case '{print}':
                    $buttons['print'] = function ($url, $model) use ($currentBtn) {
                        return Html::a('<i class="fa fa-print"></i>', Url::to($url) . '?mode=print&view=one', [
                            'class'     => 'btn btn-default btn-style',
                            'title'     => $currentBtn['print_label'] . ' ' . $model->titleSuffix,
                            'data-pjax' => '0',
                            'target'    => '_blank'
                        ]);
                    };
                    break;

                // ################# //

                case '{printselect}':
                    $buttons['printselect'] = function ($url) use ($currentBtn) {
                        return Html::button('<i class="fa fa-print"></i>', [
                            'value' => Url::to($url),
                            'class' => 'btn btn-default showModalButton btn-style',
                            'title' => Yii::t('app', 'List of printable documents')
                        ]);
                    };
                    break;

                // ################# //

                case '{passresetemail}':
                    $buttons['passresetemail'] = function ($url) use ($currentBtn) {
                        if (Yii::$app->user->can('writer')) {
                            return Html::a('<i class="fa fa-envelope-o"></i>', Url::to($url), [
                                'class' => 'btn btn-default btn-style',
                                'title' => Yii::t('appMenu', 'Send password reset email')
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
        return Html::a('<i class="fa fa-print"></i> ' . $currentBtn['print_btntext'],
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
            return Html::button('<i class="fa fa-plus"></i>', [
                'value' => Url::toRoute($currentBtn['create']),
                'class' => 'showModalButton btn btn-success',
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
       
        return Html::a('<i class="fa fa-repeat"></i>', [
            Url::to(($currentBtn['link']))
        ], [
                'data-pjax' => 0,
                'class'     => 'btn btn-default',
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
        return Html::Button('<span class="fa fa-refresh"></span>&nbsp;' .
            '<span id="syncmessagespan">' . ($pendinguploads == 0 ? '' : $pendinguploads . ' Up') . '</span>', [
                'class'     => 'btn btn-info pull-right',
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
            'i_' . Yii::$app->controller->id, Yii::t('modelattr', 'Legal context')
        );

        // PANEL HEADER + FILTERS
        return
            '<span class="' . $context_array['ti_icon1'] . '"></span> ' . $context_array['title1'] . '&nbsp;' .
            '<span class="' . $context_array['ti_icon2'] . '"></span> ' . $context_array['title2'] . ' ' . $infobtn;
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
                $lefttoolbar[] = Html::a('<span class="' . $btn['fa_icon'] . '"></span> ' . $btn['button_title'], [$btn['link']], [
                    'class'     => 'btn ' . ($btn === $currentBtn ? 'btn-primary disabled' : 'btn-default'),
                    'data-pjax' => 0,
                    'data-role' => 'link'
                ]);

            }

        }
        return $lefttoolbar;
    }

    /**
     * @param $params
     * @param $context_array
     * @param $currentBtn
     * @param array $gridfilter
     * @throws \Exception
     */
    public static function echoGridView(&$params, &$context_array, &$currentBtn, $gridfilter = [])
    {
        // extract $dashboardfilter and $gridfilter
        /* @var $dataProvider ActiveRecord */
        /* @var $filterModel ActiveRecord */
        /* @var $columns array */
        /* @var $panelBeforeTemplate string */
        /* @var $toolbar array */
        /* @var $exportdataProvider ActiveRecord */
        /* @var $exportcolumns array */
        extract($params);

        if (empty($header)) {
            $header = self::getHeader($context_array);
        }

        if (!empty($gridfilter)) {
            $header .= '<h4>';
            foreach ($gridfilter as $element) {
                $header .= '<span class="label label-default small ' . $element['box-color'] . '">' .
                    '<i class="fa fa-filter" aria-hidden="true"></i> ' .
                    $element['filtertitle'] . '</span>&nbsp;';
            }
            $header .= '</h4>';
        }

        // PANELBEFORETEMPLATE
        if (empty($panelBeforeTemplate)) {
            $panelBeforeTemplate = '{lefttoolbar}' .

                '<div class="pull-right btn-toolbar kv-grid-toolbar" role="toolbar">' .
                '{toolbar}' .
                '</div>' .

                '{before}' .

                '<div class="clearfix"></div>';
        }

        $lefttoolbar = self::getLefttoolbar($context_array, $currentBtn);

        // RIGHT TOOLBAR
        $toolbar[] = '{export}';
        $toolbar[] = '{toggleData}';

        Pjax::begin(['id' => 'pjax-gridview-container', 'enablePushState' => true]);

        echo GridView::widget([
                'dataProvider'   => $dataProvider,
                'columns'        => $columns,
                'responsiveWrap' => false,

                'id' => 'gridview-costum-id',

                'panel' => [
                    'type'    => Gridview::TYPE_DEFAULT,
                    'heading' => $header,
                ],

                'exportConfig' => [
                    Gridview::EXCEL => [],
                    Gridview::PDF => [
                        'config' => [
                            'methods' => [
                                'SetHeader' => [
                                    ['odd' => '', 'even' => '']
                                ],
                                'SetFooter' => [
                                    ['odd' => '', 'even' => '']
                                ],
                            ],
                        ]
                    ],
                    Gridview::CSV   => [],
                    Gridview::HTML  => []
                ],

                'export' => [
                    'label' => Yii::t('yii', 'Export'),
                    'fontAwesome' => true,
                    'showConfirmAlert' => false,
                    //'exportcolumns'      => $exportcolumns,
                ],

                'responsive'          => true,
                'panelBeforeTemplate' => $panelBeforeTemplate,
                'toolbar'             => $toolbar,
                'itemLabelSingle'     => Yii::t('modelattr', 'record'),
                'itemLabelPlural'     => Yii::t('modelattr', 'records'),

                'replaceTags' => [
                    '{lefttoolbar}' => join(' ', $lefttoolbar)
//                    '{lefttoolbar}' => ''
                ]
            ] + (!empty($filterModel) ? ['filterModel' => $filterModel] : [])
        );

        Pjax::end();
    }

    /**
     * @return int|mixed
     */
    public static function getMandantidOrLibrary0()
    {
        return (Yii::$app->controller->action->id == 'library') ? 0 : Yii::$app->session->get('mandant_id');
    }
}
