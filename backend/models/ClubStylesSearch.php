<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ClubStyles;

/**
 * ClubStylesSearch represents the model behind the search form about `\backend\models\ClubStyles`.
 */
class ClubStylesSearch extends ClubStyles
{
    public function rules()
    {
        return [
            [['c_css_id'], 'integer'],
            [['c_css', 'c_menu_image', 'c_top_image', 'c_top', 'c_left', 'c_menu', 'c_right', 'c_footer', 'c_main_colour_EN', 'c_main_colour_FR', 'c_colour_sample', 'is_active'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ClubStyles::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'c_css_id' => $this->c_css_id,
        ]);

        $query->andFilterWhere(['like', 'c_css', $this->c_css])
            ->andFilterWhere(['like', 'c_menu_image', $this->c_menu_image])
            ->andFilterWhere(['like', 'c_top_image', $this->c_top_image])
            ->andFilterWhere(['like', 'c_top', $this->c_top])
            ->andFilterWhere(['like', 'c_left', $this->c_left])
            ->andFilterWhere(['like', 'c_menu', $this->c_menu])
            ->andFilterWhere(['like', 'c_right', $this->c_right])
            ->andFilterWhere(['like', 'c_footer', $this->c_footer])
            ->andFilterWhere(['like', 'c_main_colour_EN', $this->c_main_colour_EN])
            ->andFilterWhere(['like', 'c_main_colour_FR', $this->c_main_colour_FR])
            ->andFilterWhere(['like', 'c_colour_sample', $this->c_colour_sample])
            ->andFilterWhere(['like', 'is_active', $this->is_active]);

        return $dataProvider;
    }
}
