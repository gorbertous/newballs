<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TagsSearch represents the model behind the search form about `backend\models\Tags`.
 */
class TagsSearch extends Tags
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_id'], 'integer'],
            [['name_FR', 'name_EN', 'name_DE'], 'safe'],
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
        $query = Tags::find();

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
            'tag_id' => $this->tag_id,
        ]);

        $query->andFilterWhere(['like', 'name_FR', $this->name_FR])
            ->andFilterWhere(['like', 'name_EN', $this->name_EN])
            ->andFilterWhere(['like', 'name_DE', $this->name_DE]);

        return $dataProvider;
    }
}
