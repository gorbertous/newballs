<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PlayDates;

/**
 * PlayDatesSearch represents the model behind the search form about `\backend\models\PlayDates`.
 */
class PlaydatesSearch extends PlayDates
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['termin_id', 'c_id', 'location_id', 'active', 'season_id', 'session_id', 'courts_no', 'slots_no', 'created_by', 'updated_by'], 'integer'],
            [['termin_date'], 'safe'],
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
        $query = PlayDates::find()->where(['c_id' => Yii::$app->session->get('c_id')]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder' => [
                    'termin_id' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        if (strlen($this->termin_date) == 4) {
            $query->andFilterWhere(['=', 'YEAR(termin_date)', $this->termin_date]);
        } else {
            $query->andFilterWhere(['termin_date' => $this->termin_date]);
        }

        $query->andFilterWhere([
            'termin_id' => $this->termin_id,
            'location_id' => $this->location_id,
            'active' => $this->active,
            'season_id' => $this->season_id,
            'session_id' => $this->session_id,
            'courts_no' => $this->courts_no,
            'slots_no' => $this->slots_no,
        ]);

        return $dataProvider;
    }
}
