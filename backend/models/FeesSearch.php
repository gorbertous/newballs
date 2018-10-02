<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Fees;

/**
 * FeesSearch represents the model behind the search form about `\backend\models\Fees`.
 */
class FeesSearch extends Fees
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'c_id', 'mem_type_id'], 'integer'],
            [['mem_fee'], 'number'],
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
        $query = Fees::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'c_id' => $this->c_id,
            'mem_type_id' => $this->mem_type_id,
            'mem_fee' => $this->mem_fee,
        ]);

        return $dataProvider;
    }
}
