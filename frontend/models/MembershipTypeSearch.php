<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MembershipType;

/**
 * MembershipTypeSearch represents the model behind the search form about `\backend\models\MembershipType`.
 */
class MembershipTypeSearch extends MembershipType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mem_type_id', 'c_id', 'fee'], 'integer'],
            [['name_EN', 'name_FR', 'name_DE'], 'safe'],
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
        $query = MembershipType::find()->where(['c_id' => Yii::$app->session->get('c_id')]);

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
            'mem_type_id' => $this->mem_type_id,
            'fee' => $this->fee,
        ]);

        $query->andFilterWhere(['like', 'name_EN', $this->name_EN])
            ->andFilterWhere(['like', 'name_FR', $this->name_FR])
            ->andFilterWhere(['like', 'name_DE', $this->name_DE]);

        return $dataProvider;
    }
}
