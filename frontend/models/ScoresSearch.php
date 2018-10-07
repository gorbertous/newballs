<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Scores;

/**
 * ScoresSearch represents the model behind the search form about `\backend\models\Scores`.
 */
class ScoresSearch extends Scores
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['score_id', 'termin_id', 'court_id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['set_one', 'set_two', 'set_three', 'set_four', 'set_five'], 'safe'],
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
        $query = Scores::find()->joinWith('termin')->where(['play_dates.c_id' => Yii::$app->session->get('c_id')]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder' => [
                    'score_id' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'score_id' => $this->score_id,
            'termin_id' => $this->termin_id,
            'court_id' => $this->court_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'set_one', $this->set_one])
            ->andFilterWhere(['like', 'set_two', $this->set_two])
            ->andFilterWhere(['like', 'set_three', $this->set_three])
            ->andFilterWhere(['like', 'set_four', $this->set_four])
            ->andFilterWhere(['like', 'set_five', $this->set_five]);

        return $dataProvider;
    }
}
