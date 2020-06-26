<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\GamesBoard;

/**
 * GamesboardSearch represents the model behind the search form about `\backend\models\GamesBoard`.
 */
class GamesboardSearch extends GamesBoard
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'c_id', 'termin_id', 'member_id', 'court_id', 'slot_id', 'status_id', 'fines', 'tokens', 'late','coaching'], 'integer'],
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
        $query = GamesBoard::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        if ($this->tokens != -1) {
            $query->andFilterWhere([
                'tokens' => $this->tokens,
            ]);
        }
         if ($this->late != -1) {
            $query->andFilterWhere([
                'late' => $this->late,
            ]);
        }
        
        if ($this->coaching != -1) {
            $query->andFilterWhere([
                'late' => $this->coaching,
            ]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'c_id' => $this->c_id,
            'termin_id' => $this->termin_id,
            'member_id' => $this->member_id,
            'court_id' => $this->court_id,
            'slot_id' => $this->slot_id,
            'status_id' => $this->status_id,
            'fines' => $this->fines,
        ]);

        return $dataProvider;
    }
}
