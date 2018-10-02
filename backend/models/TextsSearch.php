<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * TextsSearch represents the model behind the search form about `backend\models\Texts`.
 */
class TextsSearch extends Texts {

    /**
     * @inheritdoc
     */
    public function rules() {
        return self::ContLangRules([
            
            [['code', 'text'], 'safe'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     * @throws NotFoundHttpException
     */
    public function search($params) {
        
        $query = Texts::find();

        $this->load($params);
        // add conditions that should always apply here

        
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }


        $fns = Texts::ContLangFieldName('text');
        $query->andFilterWhere(['like', 'code', $this->code])
                ->andFilterWhere(['like', Texts::ContLangConcat('text'), $this->$fns]);

        return $dataProvider;
    }

}
