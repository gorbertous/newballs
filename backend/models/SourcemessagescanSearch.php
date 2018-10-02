<?php

namespace backend\models;

use yii\data\ActiveDataProvider;

/**
 * backend\models\SourcemessagescanSearch represents the model behind the search form about `backend\models\Sourcemessagescan`.
 */
class SourcemessagescanSearch extends Sourcemessagescan
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'source_message_id', 'valid', 'loccount', 'new', 'blacklisted'], 'integer'],
            [['category', 'message'], 'safe'],
        ];
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
        $query = Sourcemessagescan::find();

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
            'id'                => $this->id,
            'source_message_id' => $this->source_message_id,
            'valid'             => $this->valid,
            'loccount'          => $this->loccount,
            'new'               => $this->new,
            'blacklisted'       => $this->blacklisted
        ]);

        $query->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}