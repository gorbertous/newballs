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
 * @property int $scheduled_stats
 * @property int $played_stats
 * @property int $cancelled_stats
 * @property int $coaching_stats
 * @property int $noshow_stats
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
            [['member_id', 'season_id', 'token_stats', 'scheduled_stats', 'played_stats', 'cancelled_stats', 'coaching_stats', 'nonscheduled_stats', 'noshow_stats', 'foundsub_stats'], 'integer'],
            [['member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['member_id' => 'member_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                 => Yii::t('modelattr', 'ID'),
            'member_id'          => Yii::t('modelattr', 'Member'),
            'season_id'          => Yii::t('modelattr', 'Season'),
            'token_stats'        => Yii::t('modelattr', 'Tokens') . ' / ' . Yii::t('modelattr', 'Balls Count'),
            'scheduled_stats'    => Yii::t('modelattr', 'Scheduled'),
            'played_stats'       => Yii::t('modelattr', 'Played'),
            'cancelled_stats'    => Yii::t('modelattr', 'Cancelled'),
            'coaching_stats'     => Yii::t('modelattr', 'Coached'),
            'nonscheduled_stats' => Yii::t('modelattr', 'Non Scheduled Play'),
            'noshow_stats'       => Yii::t('modelattr', 'No Show'),
        ];
    }

    //    create all time player stats
    public static function generateCoachingPlayerStats()
    {
        //get all the coaching games
        $subQuery = GamesBoard::find()
                ->joinWith('termin')
                ->joinWith('member')
                ->where(['games_board.c_id' => Yii::$app->session->get('c_id')])
                ->andWhere(['members.mem_type_id' => 5])
                ->all();

        //extract the list of courts and date ids
        $courts_list = \yii\helpers\ArrayHelper::getColumn($subQuery, 'court_id');
        $termins_list = \yii\helpers\ArrayHelper::getColumn($subQuery, 'termin_id');

        $coachingstats = GamesBoard::find()
                ->where(['in', 'termin_id', $termins_list])
                ->andWhere(['games_board.c_id' => Yii::$app->session->get('c_id')])
                ->andWhere(['>', 'member_id', 1])
//                ->andWhere(['status_id' => 1])
                ->andWhere(['in', 'status_id', [1, 7]])
                ->andWhere(['in', 'court_id', $courts_list])
                ->createCommand()
                ->queryAll();

//        return $coaching_stats->count();
//           dd($coachingstats);


        if (!empty($coachingstats)) {
            foreach ($coachingstats as $srcrow) {

                $playerstat = GamesBoard::find()
                        ->where(['id' => $srcrow['id']])
                        ->one();
                if (!empty($playerstat)) {

                    $playerstat->coaching = 1;
                    $playerstat->save();
                }
            }
        }
    }

    //    create all time player stats
    public static function generateAllTimePlayerStats()
    {
        $playerstats = GamesBoard::find()
                ->select(['COUNT(id) AS count', 'member_id', 'status_id'])
                ->andWhere(['c_id' => Yii::$app->session->get('c_id')])
                ->andWhere(['>', 'member_id', 1])
                ->andWhere(['status_id' => 1])
                ->groupBy(['member_id', 'status_id'])
                ->createCommand()
                ->queryAll();

        if (!empty($playerstats)) {
            foreach ($playerstats as $srcrow) {

                $playerstat = PlayerStats::find()
                        ->where(['season_id' => 0])
                        ->andWhere(['member_id' => $srcrow['member_id']])
                        ->one();
                if (empty($playerstat)) {
                    $newstat = new PlayerStats();

                    $newstat->member_id = $srcrow['member_id'];
                    $newstat->season_id = 0;

                    $newstat->played_stats = $srcrow['count'];

                    $newstat->save();
                }
            }
        }
    }

    //    create player stats per season
    public static function generatePlayerStats()
    {
        for ($x = 1; $x <= 15; $x++) {
            $playerstats = GamesBoard::find()
                    ->select(['COUNT(games_board.id) AS count', 'games_board.member_id', 'games_board.status_id', 'play_dates.season_id'])
                    ->joinWith('termin')
                    ->where(['play_dates.season_id' => $x])
                    ->andWhere(['games_board.c_id' => Yii::$app->session->get('c_id')])
                    ->andWhere(['>', 'games_board.member_id', 1])
                    ->andWhere(['in', 'games_board.status_id', [1]])
                    ->groupBy(['play_dates.season_id', 'games_board.status_id', 'games_board.member_id'])
                    ->createCommand()
                    ->queryAll();

            if (!empty($playerstats)) {
                foreach ($playerstats as $srcrow) {

                    $playerstat = PlayerStats::find()
                            ->where(['season_id' => $x])
                            ->andWhere(['member_id' => $srcrow['member_id']])
                            ->one();
                    if (empty($playerstat)) {
                        $newstat = new PlayerStats();
                        $newstat->member_id = $srcrow['member_id'];
                        $newstat->season_id = $x;
                        $newstat->played_stats = $srcrow['count'];
                        $newstat->save();
                    }
                }
            }
        }
    }

    public static function updateAllTimePlayerStats()
    {

        $status_list = [3, 4, 5, 6, 7];
        foreach ($status_list as $item) {
            $statusstats = GamesBoard::find()
                    ->select(['COUNT(id) AS count', 'member_id', 'status_id'])
                    ->andWhere(['c_id' => Yii::$app->session->get('c_id')])
                    ->andWhere(['>', 'member_id', 1])
                    ->andWhere(['status_id' => $item])
                    ->groupBy(['status_id', 'member_id'])
                    ->createCommand()
                    ->queryAll();
//            dd($otherplayerstats);

            if (!empty($statusstats)) {
                foreach ($statusstats as $srcrow) {

                    $playerstat = PlayerStats::find()
                            ->where(['season_id' => 0])
                            ->andWhere(['member_id' => $srcrow['member_id']])
                            ->one();
                    if (!empty($playerstat)) {
                        switch ($item) {
                            case 3:
                                $playerstat->noshow_stats = $srcrow['count'];
                                break;
                            case 4:
                                $playerstat->scheduled_stats = $srcrow['count'];
                                break;
                            case 5:
                                $playerstat->cancelled_stats = $srcrow['count'];
                                break;
                            case 6:
                                $playerstat->coaching_stats = $srcrow['count'];
                                break;
                            case 7:
                                $playerstat->nonscheduled_stats = $srcrow['count'];
                                break;
                            default:
                                break;
                        }
                        $playerstat->save();
                    }
                }
            }
        }

        $tokenstats = GamesBoard::find()
                ->select(['COUNT(id) AS count', 'member_id'])
                ->andWhere(['c_id' => Yii::$app->session->get('c_id')])
                ->andWhere(['>', 'member_id', 1])
                ->andWhere(['status_id' => 1])
                ->andWhere(['tokens' => 1])
                ->groupBy(['member_id'])
                ->createCommand()
                ->queryAll();


        if (!empty($tokenstats)) {
            foreach ($tokenstats as $srcrow) {

                $playerstat = PlayerStats::find()
                        ->where(['season_id' => 0])
                        ->andWhere(['member_id' => $srcrow['member_id']])
                        ->one();
                if (!empty($playerstat)) {

                    $playerstat->token_stats = $srcrow['count'];
                    $playerstat->save();
                }
            }
        }

        $coachingstats = GamesBoard::find()
                ->select(['COUNT(id) AS count', 'member_id'])
                ->andWhere(['c_id' => Yii::$app->session->get('c_id')])
                ->andWhere(['>', 'member_id', 1])
                ->andWhere(['in', 'status_id', [1, 7]])
                ->andWhere(['coaching' => 1])
                ->groupBy(['member_id'])
                ->createCommand()
                ->queryAll();


        if (!empty($coachingstats)) {
            foreach ($coachingstats as $srcrow) {

                $playerstat = PlayerStats::find()
                        ->where(['season_id' => 0])
                        ->andWhere(['member_id' => $srcrow['member_id']])
                        ->one();
                if (!empty($playerstat)) {

                    $playerstat->coaching_stats = $srcrow['count'];
                    $playerstat->save();
                }
            }
        }

//        //get all the coaching games
//        $subQuery = GamesBoard::find()
//                ->joinWith('termin')
//                ->joinWith('member')
//                ->where(['games_board.c_id' => Yii::$app->session->get('c_id')])
//                ->andWhere(['members.mem_type_id' => 5])
//                ->all();
//
//        //extract the list of courts and date ids
//        $courts_list = \yii\helpers\ArrayHelper::getColumn($subQuery, 'court_id');
//        $termins_list = \yii\helpers\ArrayHelper::getColumn($subQuery, 'termin_id');
//
//        $coachingstats = GamesBoard::find()
//                ->select(['COUNT(id) AS count', 'member_id', 'status_id'])
//                ->where(['in', 'termin_id', $termins_list])
//                ->andWhere(['games_board.c_id' => Yii::$app->session->get('c_id')])
//                ->andWhere(['>', 'member_id', 1])
//                ->andWhere(['status_id' => 1])
//                ->andWhere(['in', 'court_id', $courts_list])
//                ->groupBy(['status_id', 'member_id'])
//                ->createCommand()
//                ->queryAll();
//
////        return $coaching_stats->count();
////            dd($coachingstats);
//
//
//        if (!empty($coachingstats)) {
//            foreach ($coachingstats as $srcrow) {
//
//                $playerstat = PlayerStats::find()
//                        ->where(['season_id' => 0])
//                        ->andWhere(['member_id' => $srcrow['member_id']])
//                        ->one();
//                if (!empty($playerstat)) {
//
//                    $playerstat->coaching_stats = $srcrow['count'];
//                    $playerstat->save();
//                }
//            }
//        }
    }

    //    update player stats
    public static function updatePlayerStats()
    {
        for ($x = 1; $x <= 15; $x++) {
            $status_list = [3, 4, 5, 6, 7];
            foreach ($status_list as $item) {
                $statusstats = GamesBoard::find()
                        ->select(['COUNT(games_board.id) AS count', 'games_board.member_id', 'games_board.status_id', 'play_dates.season_id'])
                        ->joinWith('termin')
                        ->where(['play_dates.season_id' => $x])
                        ->andWhere(['games_board.c_id' => Yii::$app->session->get('c_id')])
                        ->andWhere(['>', 'games_board.member_id', 1])
                        ->andWhere(['games_board.status_id' => $item])
                        ->groupBy(['play_dates.season_id', 'games_board.status_id', 'games_board.member_id'])
                        ->createCommand()
                        ->queryAll();
//            dd($otherplayerstats);

                if (!empty($statusstats)) {
                    foreach ($statusstats as $srcrow) {

                        $playerstat = PlayerStats::find()
                                ->where(['season_id' => $x])
                                ->andWhere(['member_id' => $srcrow['member_id']])
                                ->one();
                        if (!empty($playerstat)) {
                            switch ($item) {
                                case 3:
                                    $playerstat->noshow_stats = $srcrow['count'];
                                    break;
                                case 4:
                                    $playerstat->scheduled_stats = $srcrow['count'];
                                    break;
                                case 5:
                                    $playerstat->cancelled_stats = $srcrow['count'];
                                    break;
                                case 6:
                                    $playerstat->coaching_stats = $srcrow['count'];
                                    break;
                                case 7:
                                    $playerstat->nonscheduled_stats = $srcrow['count'];
                                    break;
                                default:
                                    break;
                            }
                            $playerstat->save();
                        }
                    }
                }
            }

            $tokenstats = GamesBoard::find()
                    ->select(['COUNT(games_board.id) AS count', 'games_board.member_id', 'play_dates.season_id'])
                    ->joinWith('termin')
                    ->where(['play_dates.season_id' => $x])
                    ->andWhere(['games_board.c_id' => Yii::$app->session->get('c_id')])
                    ->andWhere(['>', 'games_board.member_id', 1])
                    ->andWhere(['games_board.status_id' => 1])
                    ->andWhere(['in', 'games_board.status_id', [1, 7]])
                    ->andWhere(['games_board.tokens' => 1])
                    ->groupBy(['play_dates.season_id', 'games_board.member_id'])
                    ->createCommand()
                    ->queryAll();


            if (!empty($tokenstats)) {
                foreach ($tokenstats as $srcrow) {

                    $playerstat = PlayerStats::find()
                            ->where(['season_id' => $x])
                            ->andWhere(['member_id' => $srcrow['member_id']])
                            ->one();
                    if (!empty($playerstat)) {

                        $playerstat->token_stats = $srcrow['count'];
                        $playerstat->save();
                    }
                }
            }


            $coachingstats = GamesBoard::find()
                    ->select(['COUNT(games_board.id) AS count', 'games_board.member_id', 'play_dates.season_id'])
                    ->joinWith('termin')
                    ->where(['play_dates.season_id' => $x])
                    ->andWhere(['games_board.c_id' => Yii::$app->session->get('c_id')])
                    ->andWhere(['>', 'games_board.member_id', 1])
                    ->andWhere(['games_board.status_id' => 1])
                    ->andWhere(['in', 'games_board.status_id', [1, 7]])
                    ->andWhere(['games_board.coaching' => 1])
                    ->groupBy(['play_dates.season_id', 'games_board.member_id'])
                    ->createCommand()
                    ->queryAll();


            if (!empty($coachingstats)) {
                foreach ($coachingstats as $srcrow) {

                    $playerstat = PlayerStats::find()
                            ->where(['season_id' => $x])
                            ->andWhere(['member_id' => $srcrow['member_id']])
                            ->one();
                    if (!empty($playerstat)) {

                        $playerstat->coaching_stats = $srcrow['count'];
                        $playerstat->save();
                    }
                }
            }



//            //get all the coaching games
//            $subQuery = GamesBoard::find()
//                    ->joinWith('termin')
//                    ->joinWith('member')
//                    ->where(['season_id' => $x])//$this->club->season_id
//                    ->andWhere(['games_board.c_id' => Yii::$app->session->get('c_id')])
//                    ->andWhere(['members.mem_type_id' => 5])
//                    ->all();
//
//            //extract the list of courts and date ids
//            $courts_list = \yii\helpers\ArrayHelper::getColumn($subQuery, 'court_id');
//            $termins_list = \yii\helpers\ArrayHelper::getColumn($subQuery, 'termin_id');
//
//            $coachingstats = GamesBoard::find()
//                    ->select(['COUNT(games_board.id) AS count', 'games_board.member_id', 'games_board.status_id', 'play_dates.season_id'])
//                    ->joinWith('termin')
//                    ->where(['play_dates.season_id' => $x])
//                    ->andWhere(['games_board.c_id' => Yii::$app->session->get('c_id')])
//                    ->andWhere(['in', 'games_board.termin_id', $termins_list])
//                    ->andWhere(['>', 'games_board.member_id', 1])
//                    ->andWhere(['games_board.status_id' => 1])
//                    ->andWhere(['in', 'games_board.court_id', $courts_list])
//                    ->groupBy(['play_dates.season_id', 'games_board.status_id', 'games_board.member_id'])
//                    ->createCommand()
//                    ->queryAll();
//
////        return $coaching_stats->count();
////            dd($coachingstats);
//
//
//            if (!empty($coachingstats)) {
//                foreach ($coachingstats as $srcrow) {
//
//                    $playerstat = PlayerStats::find()
//                            ->where(['season_id' => $x])
//                            ->andWhere(['member_id' => $srcrow['member_id']])
//                            ->one();
//                    if (!empty($playerstat)) {
//
//                        $playerstat->coaching_stats = $srcrow['count'];
//                        $playerstat->save();
//                    }
//                }
//            }
//        }
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
