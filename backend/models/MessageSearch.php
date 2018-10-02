<?php

namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * backend\models\MessageSearch represents the model behind the search form about `backend\models\Message`.
 * @property int $masterts [timestamp]
 * @property int $localts [timestamp]
 */
class MessageSearch extends Message
{
    public $sourceMessage;
    public $category;
    public $duplicates;
    public $unused;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['language', 'translation', 'sourceMessage', 'category'], 'safe'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     * @throws \yii\db\Exception
     */
    public function search($params)
    {
        $query = Message::find()
            ->joinWith('sourceMessage');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder' => ['id' => SORT_DESC]
            ]
        ]);

        $dataProvider->sort->attributes['sourceMessage'] = [
            'asc'  => ['source_message.message' => SORT_ASC],
            'desc' => ['source_message.message' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['category'] = [
            'asc'  => ['source_message.category' => SORT_ASC],
            'desc' => ['source_message.category' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'message.id' => $this->id,
        ]);

        if ($this->language != -1) {
            $query->andFilterWhere(['like', 'language', $this->language]);
        }

        if ($this->duplicates == 1) {
            $dupids = ArrayHelper::getColumn(Yii::$app->db->createCommand("
                SELECT id FROM source_message WHERE CONCAT(category, '£', message) IN (
                    SELECT CONCAT(SM1.category, '£', SM1.message)
                    FROM source_message SM1
                    INNER JOIN(
                    SELECT category, message
                    FROM source_message
                    GROUP BY category, message
                    HAVING COUNT(*) >1
                    ) SM2 ON SM1.message= SM2.message AND SM1.category= SM2.category)
                ")->queryAll(), 'id');
            $query->andFilterWhere(['source_message.id' => $dupids]);
        }

        if ($this->unused == 1) {
            $usedids = ArrayHelper::getColumn(Yii::$app->db->createCommand("
                SELECT source_message_id FROM source_message_scan WHERE source_message_id IS NOT NULL
                ")->queryAll(), 'source_message_id');
            $query->andFilterWhere(['not in', 'source_message.id', $usedids]);
        }

        $query->andFilterWhere(['like', 'source_message.message', $this->sourceMessage])
            ->andFilterWhere(['like', 'source_message.category', $this->category])
            ->andFilterWhere(['like', 'translation', $this->translation]);

        return $dataProvider;
    }
}
