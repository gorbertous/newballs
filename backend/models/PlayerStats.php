<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "player_stats".
 *
 * @property int $id
 * @property int $member_id
 * @property int $season_id
 * @property int $token_stats
 * @property int $player_stats_scheduled
 * @property int $player_stats_played
 * @property int $player_stats_cancelled
 * @property int $coaching_stats
 * @property int $status_stats
 *
 * @property Members $member
 */
class PlayerStats extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'player_stats';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'season_id'], 'required'],
            [['member_id', 'season_id', 'token_stats', 'player_stats_scheduled', 'player_stats_played', 'player_stats_cancelled', 'coaching_stats', 'status_stats'], 'integer'],
            [['member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['member_id' => 'member_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                     => Yii::t('modelattr', 'ID'),
            'member_id'              => Yii::t('modelattr', 'Member'),
            'season_id'              => Yii::t('modelattr', 'Season'),
            'token_stats'            => Yii::t('modelattr', 'Tokens') . ' / ' . Yii::t('modelattr', 'Balls Count'),
            'player_stats_scheduled' => Yii::t('modelattr', 'Scheduled'),
            'player_stats_played'    => Yii::t('modelattr', 'Played'),
            'player_stats_cancelled' => Yii::t('modelattr', 'Cancelled'),
            'coaching_stats'         => Yii::t('modelattr', 'Coached'),
            'status_stats'           => Yii::t('modelattr', 'No Show') . ' / ' . Yii::t('modelattr', 'Non Scheduled Play'),
        ];
    }

    //    create or update player stats
    public static function generatePlayerStats()
    {
        for ($x = 1; $x <= 20; $x++) {
            $playerstats = GamesBoard::find()
                    ->select(['COUNT(games_board.id) AS id', 'games_board.member_id', 'games_board.status_id'])
                    ->joinWith('termin')
                    ->where(['play_dates.season_id' => $x])
                    ->andWhere(['games_board.c_id' => Yii::$app->session->get('c_id')])
                    ->andWhere(['>', 'games_board.member_id', 1])
                    ->groupBy(['games_board.member_id', 'games_board.status_id'])

                    ->all();
            dd($playerstats);

            foreach ($playerstats as $srcrow) {

                $newstat = new backend\models\PlayerStats();

                $newstat->member_id = $srcrow->member_id;
                $newstat->season_id = $srcrow->season_id;


                $newstat->save();
            }
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Members::className(), ['member_id' => 'member_id']);
    }

    /**
     * {@inheritdoc}
     * @return PlayerStatsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PlayerStatsQuery(get_called_class());
    }

}
