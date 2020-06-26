<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\GamesBoard;
use yii\db\Expression;

/**
 * frontend\models\RotaSearch represents the model behind the search form about `backend\models\GamesBoard`.
 */
class RotaSearch extends GamesBoard
{

    public $timefilter;
    public $seasonfilter;
//    public $termin_date;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'c_id', 'termin_id', 'timefilter', 'seasonfilter','slot_id', 'member_id', 'court_id', 'status_id', 'fines','coaching'], 'integer'],
            [['tokens', 'late','coaching'], 'safe'],
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
        $query = GamesBoard::find()
                ->innerJoinWith('termin', true)
                ->where(['games_board.c_id' => Yii::$app->session->get('c_id')]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'sort' => ['attributes' => ['termin_date']],
               
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->timefilter == 1) {
            $query->andWhere(['>', 'play_dates.termin_date', new Expression('NOW()')])
                    ->orderBy(['play_dates.termin_date' => SORT_ASC,
                        'games_board.court_id'  => SORT_ASC,
                        'games_board.slot_id'   => SORT_ASC]);
        } elseif ($this->timefilter == 2) {
            $query->andWhere(['<', 'play_dates.termin_date', new Expression('NOW()')])
                    ->orderBy(['play_dates.termin_date' => SORT_DESC,
                        'games_board.court_id'  => SORT_ASC,
                        'games_board.slot_id'   => SORT_ASC]);
        } else {
            $query->orderBy(['play_dates.termin_date' => SORT_DESC,
                'games_board.court_id'  => SORT_ASC,
                'games_board.slot_id'   => SORT_ASC]);
        }
        
        if ($this->seasonfilter != 0) {
            $query->andWhere([
                'play_dates.season_id'  => $this->seasonfilter,
            ]);
        }

        if ($this->tokens != -1) {
            $query->andFilterWhere([
                'tokens' => $this->tokens,
            ]);
        }
        if ($this->late != -1) {
            $query->andFilterWhere([
                'late' => $this->late,
            ]);
        }
        if ($this->coaching != -1) {
            $query->andFilterWhere([
                'coaching' => $this->coaching,
            ]);
        }

        $query->andFilterWhere([
            'id'                   => $this->id,
            'c_id'                 => $this->c_id,
            'play_dates.termin_id' => $this->termin_id,
            'member_id'            => $this->member_id,
            'court_id'             => $this->court_id,
            'slot_id'              => $this->slot_id,
            'status_id'            => $this->status_id,
            'fines'                => $this->fines,
        ]);

        return $dataProvider;
    }

}
