<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PlayerStats;

/**
 * PlayerStatsSearch represents the model behind the search form of `\backend\models\PlayerStats`.
 */
class PlayerStatsSearch extends PlayerStats
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'member_id', 'season_id', 'token_stats', 'scheduled_stats', 'played_stats', 'cancelled_stats', 'cancelled_stats', 'coaching_stats', 'nonscheduled_stats', 'noshow_stats', 'foundsub_stats'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = PlayerStats::find()
                ->joinWith('member')
                ->where(['members.c_id' => Yii::$app->session->get('c_id')]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pageSize' => 50 ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'player_stats.member_id' => $this->member_id,
            'season_id' => $this->season_id,
            'token_stats' => $this->token_stats,
            'scheduled_stats' => $this->scheduled_stats,
            'played_stats' => $this->played_stats,
            'cancelled_stats' => $this->cancelled_stats,
            'coaching_stats' => $this->coaching_stats,
            'nonscheduled_stats' => $this->nonscheduled_stats,
            'noshow_stats' => $this->noshow_stats,
            'foundsub_stats' => $this->foundsub_stats,
            
        ]);

        return $dataProvider;
    }
}
