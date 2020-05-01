<?php

namespace common\helpers;

use Yii;
use yii\widgets\ListView;
//use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use backend\models\Countries;
use backend\models\MembershipType;
use backend\models\Members;
use backend\models\ClubRoles;
use common\rbac\models\Authitem;

/**
 * Class ViewsHelper
 *
 * @package common\helpers
 */
class ViewsHelper
{

    /**
     * @param null $c_id
     * @param string $innerjoin
     * @param array $andWhere
     *
     * @return array
     */
    public static function getMembersList($c_id = null, string $innerjoin = '', array $andWhere = [])
    {
        $members = Members::find()
            ->select(['members.member_id', 'members.firstname', 'members.lastname'])
            ->where(['or',['members.c_id' => $c_id ?? Yii::$app->session->get('c_id')],['members.member_id' => 1]])
            ->orderBy(['lastname' => SORT_ASC, 'firstname' => SORT_ASC]);

        if (!empty($innerjoin)) {
            $members->innerJoinWith($innerjoin);
        }

        if (!empty($andWhere)) {
            $members->andWhere($andWhere);
        }

        if (!Yii::$app->user->can('writer')) {
            $members->andWhere(['members.member_id' => Yii::$app->session->get('member_id')]);
        }

        return ArrayHelper::map($members->all(), 'member_id', 'name');
    }
    
    /**
     * @param null $c_id
     * @param array $andWhere
     *
     * @return array
     */
    public static function getUserMembersList($c_id = null, array $andWhere = [])
    {
        $members = Members::find()
            ->select(['members.user_id','members.member_id', 'members.firstname', 'members.lastname'])
            ->innerJoinWith('user')
            ->where(['members.c_id' => $c_id ?? Yii::$app->session->get('c_id')])
            ->orderBy(['lastname' => SORT_ASC, 'firstname' => SORT_ASC]);

        if (!empty($andWhere)) {
            $members->andWhere($andWhere);
        }
        
        //dd($members);

        return ArrayHelper::map($members->all(), 'user_id', 'name');
    }

    /**
     * @param array $where
     *
     * @return array
     */
    public static function getCountriesList(array $where = [])
    {
        $fns = Countries::ContLangAllFieldValuesFBsql('text');

        $countries = Countries::find()
            ->select(['code', $fns])
            ->orderBy([Countries::ContLangFieldName('text') => SORT_ASC]);

        if (!empty($where)) {
            $countries->where($where);
        }

        return ArrayHelper::map($countries->all(), 'code', 'text_EN');
    }
    
      /**
     * @param array $where
     *
     * @return array
     */
    public static function getMemTypesList(array $andWhere = [] , $c_id = null)
    {
        $fns = MembershipType::ContLangAllFieldValuesFBsql('name');

        $types = MembershipType::find()
            ->select(['mem_type_id', $fns])
            ->orderBy([MembershipType::ContLangFieldName('name') => SORT_ASC]);
        if (!empty($c_id)) {
            $types->where(['c_id' => $c_id ?? Yii::$app->session->get('c_id')]);
        }
        if (!empty($andWhere)) {
            $types->andWhere($andWhere);
        }

        return ArrayHelper::map($types->all(), 'mem_type_id', 'name_EN');
    }
    
     /**
     * @param null type
     *
     * @return array
     */
    public static function getAuthitems($type = null)
    {
        $authitems = Authitem::find()
            ->orderBy(['type' => SORT_ASC, 'name' => SORT_ASC]);

        if (!empty($type)) {
            $authitems->andWhere(['type' => $type]);
        }

        return ArrayHelper::map($authitems->all(), 'name', 'name');
    }
    
     /**
     * @param null type
     *
     * @return array
     */
    public static function getClubRoles()
    {
        $roles = ClubRoles::find()
            ->orderBy(['role' => SORT_ASC]);

        return ArrayHelper::map($roles->all(), 'id', 'role');
    }
    
    
  
    /**
     * Display a alert box on the upper dashboard
     *
     * @param array $TDCategory an array as returned by the
     * class ToDoAccidentsCategory which contains
     * the settings for this todo category
     *
     * @param $Todo_counts
     *
     * @return string
     */
    public static function ToDoAlertBox(array $TDCategory, &$Todo_counts)
    {
        $count = ArrayHelper::getValue($Todo_counts, $TDCategory['category']) ?? 0;

        /** @var string $box_icon */
        /** @var string $title_singular */
        /** @var string $title_plural */
        /** @var string $box_linkurl */
        if ($count > 0 || $TDCategory['box_displayifzero']) {

            // by default box bg is blue
            $box_color = 'bg-blue';

            extract($TDCategory);

            $disptitle = $title_singular;

            if ($count > 1) {
                $disptitle = $title_plural;
            }

            if ($count == 0) {
                $box_color = 'bg-green';
            }

            $html1 = "<div class='col-lg-3 col-xs-6' id='box'>" .
                "<div class='small-box {$box_color}'>" .
                "<div class='inner'>" .
                "<h3>{$count}</h3>" .
                "<p>{$disptitle}</p>" .
                "</div>" .
                "<div class='icon'>" .
                "<i class='{$box_icon}'></i>" .
                "</div>";

            if (!empty($box_linktext)) {
                $html2 = "<a data-role='link' href='{$box_linkurl}' class='small-box-footer'>" .
                    "{$box_linktext} <i class='fa fa-arrow-circle-right'></i>" .
                    "</a>";
            } else {
                $html2 = "";
            }

            $html3 = "</div>" .
                "</div>";

            return $html1 . $html2 . $html3;
        }

        return '';
    }

    /**
     * Display a multi-line info box on the upper dashboard
     *
     * @param array $TDCategory
     *
     * @return string
     */
    public static function ToDoMultilineBox(array $TDCategory)
    {
        // by default box bg is blue
        $box_color = 'bg-blue';
        $disptitle = $box_icon = $box_linktext = '';

        extract($TDCategory);

        /** @var $box_linkurl string */
        $html1 = "<div class='col-lg-3 col-xs-6' id='box'>" .
            "<div class='small-box {$box_color}'>" .
            "<div class='inner'>" .
            "<p>{$disptitle}</p>" .
            "</div>" .
            "<div class='icon'>" .
            "<i class='{$box_icon}'></i>" .
            "</div>";

        if (!empty($box_linktext)) {
            $html2 = "<span href='{$box_linkurl}' class='small-box-footer'>" .
                "{$box_linktext}" .
                "</span>";
        } else {
            $html2 = "";
        }

        $html3 = "</div>" .
            "</div>";

        return $html1 . $html2 . $html3;
    }

    /**
     * @param string $boxtitle the title of the box
     * @param array $categories is an array as returned by the
     *              class ToDoAccidentsCategory::getConstants which contains
     *              the different todo category names
     * @throws \Exception
     */
    public static function ToDoTaskListBox(string $boxtitle, $categories)
    {
        $todo = Todo::find()
            ->where(['c_id' => Yii::$app->session->get('c_id')])
            ->andWhere(['Category' => $categories]);

        if (!Yii::$app->user->can('writer')) {
            $todo->andWhere(['member_id' => Yii::$app->session->get('member_id')]);
        } elseif (!empty(array_keys(Yii::$app->session->get('Filter_workers_ids')))) {
            $todo->andWhere(['member_id' => array_keys(Yii::$app->session->get('Filter_workers_ids'))]);
        }

        $Todo_count = $todo->count();

        if ($Todo_count > 0) {
            $title = $boxtitle . ' - ' . Yii::t('appMenu', 'To Do List');

            $dataprovider = new ActiveDataProvider([
                'query'      => $todo->orderBy(['Deadline' => SORT_ASC]),
                'pagination' => [
                    'pageSize' => 7
                ]
            ]);

            $html1 = "<div class='box box-solid box-primary'>" .
                "<div class='box-header'>" .
                "<i class='fa fa-list'></i>" .
                "<h3 class='box-title'>{$title}</h3> " .
                "<span class='label label-info'>{$Todo_count}</span>" .
                "</div>" .
                "<div class='box-body'>";

            $html2 = ListView::widget([
                'dataProvider' => $dataprovider,
                'options'      => [
                    'tag' => false
                ],
                'layout'       => '<span class="pull-right">{summary}</span>' .
                    '<div class="clearfix"></div>' .
                    '{pager}' .
                    '<div class="clearfix"></div>' .
                    '<ul class="list-group">{items}</ul>',
                'itemOptions'  => [
                    'tag' => false
                ],
                'itemView'     => function ($model) {
                    return $model->ToDoTaskListitem;
                }
            ]);

            $html3 = "</div>" .
                "</div>";

            Pjax::begin(['enablePushState' => false, 'enableReplaceState' => false]);
            echo $html1 . $html2 . $html3;
            //    Pjax::end();
        }
    }

    /**
     * Display a Rss box on the lower dashboard
     *
     * @param string $boxtitle is the title of the box
     *
     * @param array $categories is an array as returned by the
     * class ToDoAccidentsCategory::getConstants which contains
     * the different todo category names
     *
     * @param $boxcolor
     * @param $displaymode
     * @return string
     * @throws \Exception
     */
    public static function RssListBox(string $boxtitle, $categories, $boxcolor, $displaymode): string
    {
        $rss = Rss::find()
            ->where(['category' => $categories])
            ->limit(5);

        $Rss_count = $rss->count();

        if ($Rss_count > 0) {
            $title = $boxtitle;
            $dataprovider = new ActiveDataProvider([
                'query'      => $rss
                    ->orderBy(['Pubdate' => SORT_DESC]),
                'pagination' => false
            ]);
            $html1 = <<<HTML
    <div class="box box-solid $boxcolor">
        <div class="box-header">
            <i class="fa fa-rss"></i>
            <h3 class="box-title">$title</h3>
           
        </div>
        <div class="box-body">
            <ul class="todo-list pre-scrollable">
HTML;
            $html2 = ListView::widget([
                'dataProvider' => $dataprovider,
                'options'      => ['tag' => false],
                'layout'       => "{items}",
                'itemOptions'  => ['tag' => false],
                'itemView'     =>
                    function ($model) use ($displaymode) {
                        switch ($displaymode) {
                            case 0:
                                return '<li>' .
                                    Html::a($model->Title, $model->Link, ['style' => "padding-indent: 50px;"]) . $model->Label .
                                    '</li>';
                                break;
                            case 1:
                                return '<li>' .
                                    Html::a($model->Title . ' <span class="badge">' . substr($model->Category, 0, 2) . '</span>', $model->Link, ['style' => "padding-indent: 50px;"]) . $model->Label .
                                    '</li>';
                                break;
                            default:
                                return '';
                        }
                    }]);
            $html3 = <<<HTML
            </ul>
        </div>
    </div>
HTML;
            return $html1 . $html2 . $html3;
        }
        return '';
    }

}
