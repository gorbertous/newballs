<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\rbac\models\Authitem;

/**
 * common\models\AdminusersSearch represents the model behind the search form about `common\models\User`.
 */
class AdminusersSearch extends User
{

    /**
     * @var int
     */
    public $member_id;
    public $c_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'status', 'item_name', 'member_id', 'c_id'], 'safe'],
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
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find()
                ->joinWith('role')
                ->innerJoinWith('members');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => ['defaultOrder' => ['id' => SORT_ASC]],
        ]);

        $this->load($params);
        
        //var_dump($this->c_id);

        $dataProvider->sort->attributes['c_id'] = [
            'asc'  => ['members.c_id' => SORT_ASC],
            'desc' => ['members.c_id' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['member_id'] = [
            'asc'  => ['members.lastname' => SORT_ASC, 'members.firstname' => SORT_ASC],
            'desc' => ['members.lastname' => SORT_DESC, 'members.firstname' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['item_name'] = [
            'asc'  => ['item_name' => SORT_ASC],
            'desc' => ['item_name' => SORT_DESC],
        ];


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id'                => $this->id,
            'members.member_id' => $this->member_id,
            'members.c_id'      => $this->c_id,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ]);

        if ($this->status != -2) {
            $query->andFilterWhere(['status' => $this->status]);
        }

        $query->andFilterWhere(['like', 'user.username', $this->username])
                ->andFilterWhere(['like', 'user.email', $this->email])
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
