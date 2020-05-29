<?php

namespace backend\models;

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
            [['id', 'member_id', 'season_id', 'token_stats', 'player_stats_scheduled', 'player_stats_played', 'player_stats_cancelled', 'coaching_stats', 'status_stats'], 'integer'],
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
        $query = PlayerStats::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'member_id' => $this->member_id,
            'season_id' => $this->season_id,
            'token_stats' => $this->token_stats,
            'player_stats_scheduled' => $this->player_stats_scheduled,
            'player_stats_played' => $this->player_stats_played,
            'player_stats_cancelled' => $this->player_stats_cancelled,
            'coaching_stats' => $this->coaching_stats,
            'status_stats' => $this->status_stats,
        ]);

        return $dataProvider;
    }
}
