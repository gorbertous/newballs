<?php

namespace common\models;

use Yii;
use yii\base\Model;
use common\rbac\models\Authitem;
use yii\data\ActiveDataProvider;

/**
 * Class UserSearch
 *
 * @package common\models
 */
class UserSearch extends User
{
    /**
     * @var int
     */
    public $c_id;

    /**
     * @var int
     */
    public $member_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'firstname', 'lastname', 'email', 'status', 'item_name', 'member_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param  array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        // get user role
        $userrole = array_keys(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId()))[0];
        // get child roles
        $childroles = array_keys(Yii::$app->authManager->getChildRoles($userrole));
        // we make sure that consultant can not see users with developer role
        $query = User::find()
            ->joinWith('role')
            ->joinWith('members')
            ->where(['item_name' => $childroles])
            ->andWhere(['members.c_id' => $this->c_id,]);

        $this->load($params);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => ['defaultOrder' => ['id' => SORT_ASC]],
        ]);
        // make item_name (Role) sortable
        $dataProvider->sort->attributes['item_name'] = [
            'asc'  => ['item_name' => SORT_ASC],
            'desc' => ['item_name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['member_id'] = [
            'asc'  => ['members.Lastname' => SORT_ASC, 'members.Firstname' => SORT_ASC],
            'desc' => ['members.Lastname' => SORT_DESC, 'members.Firstname' => SORT_DESC],
        ];

        if (!($this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id'                  => $this->id,
            //'status' => $this->status,
            'members.c_id' => $this->c_id,
            'members.member_id' => $this->member_id,
            'created_at'          => $this->created_at,
            'updated_at'          => $this->updated_at,
        ]);

        if ($this->status != -2) {
            $query->andFilterWhere(['status' => $this->status]);
        }

        $query->andFilterWhere(['like', 'user.username', $this->username])
            ->andFilterWhere(['like', 'user.email', $this->email])
            ->andFilterWhere(['like', 'members.firstname', $this->firstname])
            ->andFilterWhere(['like', 'members.lastname', $this->lastname])
            //->andFilterWhere(['like', 'CONCAT(members.Firstname, members.Lastname)', $this->member_id])
            ->andFilterWhere(['like', 'item_name', $this->item_name]);

        return $dataProvider;
    }

    /**
     * Returns the array of possible user roles.
     * NOTE: used in user/index view.
     *
     * @return mixed
     */
    public static function getRolesList()
    {
        $roles = [];

        foreach (Authitem::getChildroles() as $item_name) {
            $roles[$item_name->name] = $item_name->name;
        }

        return $roles;
    }
}
