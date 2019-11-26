<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Members;

/**
 * MembersSearch represents the model behind the search form about `\backend\models\Members`.
 */
class MembersSearch extends Members
{
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'user_id', 'c_id', 'mem_type_id', 'grade_id', 'gender', 'is_admin', 'is_organiser', 'is_active', 'has_paid', 'is_visible', 'ban_scoreupload', 'coaching', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['title', 'firstname', 'lastname', 'email', 'photo', 'orig_photo', 'phone', 'phone_office', 'phone_mobile', 'address', 'zip', 'city', 'co_code', 'nationality', 'dob'], 'safe'],
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
        $query = Members::find()->where(['c_id' => Yii::$app->session->get('c_id')]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pageSize' => 50 ],
            'sort'  => [
                'defaultOrder' => [
                    'lastname' => SORT_ASC
                ],
            ]
        ]);
        
//        $dataProvider->setSort([
//            'attributes' => [
//                'member_id',
//                'player_stats' => [
//                    'asc' => ['COUNT(member_id2)' =>SORT_ASC ],
//                    'desc' => ['COUNT(member_id)' => SORT_DESC],
//                    'default' => SORT_ASC
//                ],
//                'token_stats' => [
//                    'asc' => ['COUNT(member_id)' =>SORT_ASC ],
//                    'desc' => ['COUNT(member_id)' => SORT_DESC],
//                    'default' => SORT_ASC
//                ],
//                'status_stats' => [
//                    'asc' => ['COUNT(member_id)' =>SORT_ASC ],
//                    'desc' => ['COUNT(member_id)' => SORT_DESC],
//                    'default' => SORT_ASC
//                ],
//            ]
//        ]);

        $this->load($params);
        
     

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->is_active != -1) {
            $query->andFilterWhere([
                'is_active' => $this->is_active
            ]);
        }

        if ($this->is_admin != -1) {
            $query->andFilterWhere([
                'is_admin' => $this->is_admin,
            ]);
        }
        if ($this->has_paid != -1) {
            $query->andFilterWhere([
                'has_paid' => $this->has_paid,
            ]);
        }
        if ($this->is_organiser != -1) {
            $query->andFilterWhere([
                'is_organiser' => $this->is_organiser,
            ]);
        }

        if ($this->is_visible != -1) {
            $query->andFilterWhere([
                'is_visible' => $this->is_visible,
            ]);
        }
        if ($this->coaching != -1) {
            $query->andFilterWhere([
                'coaching' => $this->coaching,
            ]);
        }

        $query->andFilterWhere([
            'member_id'       => $this->member_id,
            'user_id'         => $this->user_id,
            'c_id'            => $this->c_id,
            'mem_type_id'     => $this->mem_type_id,
            'grade_id'        => $this->grade_id,
            'dob'             => $this->dob,
            'ban_scoreupload' => $this->ban_scoreupload,
            'gender'          => $this->gender,
//            'token_stats'          => $this->token_stats,
//            'count(member_id)'          => $this->player_stats,
//            'status_stats'          => $this->status_stats,
        ]);

        $query->andFilterWhere(['=', 'nationality', $this->nationality]);
        
//        if (isset($this->player_stats)) {
//             $query->having(['=', 'COUNT(member_id)', $this->player_stats]);
//        }

        return $dataProvider;
    }

}
