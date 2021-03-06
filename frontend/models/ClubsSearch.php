<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Clubs;

/**
 * ClubsSearch represents the model behind the search form about `\backend\models\Clubs`.
 */
class ClubsSearch extends Clubs
{

    public function rules()
    {
        return [
            [['c_id', 'css_id', 'sport_id', 'season_id', 'session_id', 'type_id', 'coach_stats', 'token_stats', 'play_stats', 'scores', 'match_instigation', 'court_booking', 'money_stats',  'chair_id', 'location_id', 'is_active', 'payment', 'rota_removal', 'rota_block', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['name', 'logo', 'logo_orig', 'home_page', 'rules_page', 'members_page', 'rota_page', 'tournament_page', 'subscription_page', 'summary_page', 'photo_one', 'photo_two', 'photo_three', 'photo_four'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Clubs::find()->where(['c_id' => Yii::$app->session->get('c_id')]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'sport_id'          => $this->sport_id,
            'type_id'           => $this->type_id,
            'coach_stats'       => $this->coach_stats,
            'token_stats'       => $this->token_stats,
            'play_stats'        => $this->play_stats,
            'scores'            => $this->scores,
            'match_instigation' => $this->match_instigation,
            'court_booking'     => $this->court_booking,
            'money_stats'       => $this->money_stats,
            'chair_id'          => $this->chair_id,
            'location_id'       => $this->location_id,
            'is_active'         => $this->is_active,
            'payment'           => $this->payment,
            'rota_removal'      => $this->rota_removal,
            'rota_block'        => $this->rota_block,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

}
