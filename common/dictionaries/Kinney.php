<?php

namespace common\dictionaries;

use Yii;

/**
 * Class Kinney
 * @package common\dictionaries
 */
abstract class Kinney
{
    use TraitDictionaries;

    const KINNEY_EMPTY = 0;
    /** Effect constants */
    const EFFE_SMALL = 10;
    const EFFE_SIGNIFICANT = 30;
    const EFFE_SERIOUS = 70;
    const EFFE_VSERIOUS = 150;
    const EFFE_CATASTROPHE = 400;
    /** Frequency constants */
    const FREQ_CONTINUED = 100;
    const FREQ_REGULAR = 60;
    const FREQ_OCCASIONAL = 30;
    const FREQ_SOMETIMES = 20;
    const FREQ_RARE = 10;
    const FREQ_VERYRARE = 5;
    /** Probability constants */
    const PROB_PRACTICALLYIMPOS = 2;
    const PROB_HARDLYCONC = 1;
    const PROB_CONCEIVABLE = 5;
    const PROB_UNLIKELY = 10;
    const PROB_UNCOMMON = 30;
    const PROB_POSSIBLE = 60;
    const PROB_PREDICTABLE = 100;
    /** Score constants */
    const SCORE_NEGLIGIBLE = 0;
    const SCORE_EXTREME = 400;
    const SCORE_HIGH = 200;
    const SCORE_MEDIUM = 70;
    const SCORE_LOW = 20;
    /** Colors constants */
    const COLOR_WHITE = '#fff';
    const COLOR_BLACK = '#111';
    const COLOR_RED = '#fe2712';
    const COLOR_GREEN = '#72CC33';
    const COLOR_YELLOW = '#e2e22b';
    const COLOR_ORANGE = '#fb9902';
    /** Level constants */
    const NUMBER_ZERO = 'N0';
    const NUMBER_ONE = 'N1';
    const NUMBER_TWO = 'N2';
    const NUMBER_THREE = 'N3';
    const NUMBER_FOUR = 'N4';
    const NUMBER_FIVE = 'N5';

    /**
     * @return array
     */
    public static function allEffe(): array
    {
        return [
            self::KINNEY_EMPTY      => Yii::t('modelattr', '(not set)'),
            self::EFFE_SMALL        => Yii::t('modelattr', '1 - Small injury without loss of working time'),
            self::EFFE_SIGNIFICANT  => Yii::t('modelattr', '3 - Significant, lost work time'),
            self::EFFE_SERIOUS      => Yii::t('modelattr', '7 - Serious irreversible injuries'),
            self::EFFE_VSERIOUS     => Yii::t('modelattr', '15 - Very serious 1 dead'),
            self::EFFE_CATASTROPHE  => Yii::t('modelattr', '40 - Several deaths (catastrophe)')
        ];
    }

    /**
     * @param $type
     *
     * @return mixed|string
     */
    public static function getEffe($type)
    {
        $all = self::allEffe();

        if (isset($all[$type])) {
            return $all[$type];
        }

        return '-';
    }

    /**
     * @return array
     */
    public static function allFreq(): array
    {
        return [
            self::KINNEY_EMPTY    => Yii::t('modelattr', '(not set)'),
            self::FREQ_VERYRARE   => Yii::t('modelattr', '0.5 - Very rare (less than once a year)'),
            self::FREQ_RARE       => Yii::t('modelattr', '1 - Rare (annual)'),
            self::FREQ_SOMETIMES  => Yii::t('modelattr', '2 - Sometimes (monthly)'),
            self::FREQ_OCCASIONAL => Yii::t('modelattr', '3 - Occasional (weekly)'),
            self::FREQ_REGULAR    => Yii::t('modelattr', '6 - Regular (daily)'),
            self::FREQ_CONTINUED  => Yii::t('modelattr', '10 - Continued')
        ];
    }

    /**
     * @param $type
     *
     * @return mixed|string
     */
    public static function getFreq($type)
    {
        $all = self::allFreq();
        if (isset($all[$type])) {
            return $all[$type];
        }
        return '-';
    }

    /**
     * @return array
     */
    public static function allProb(): array
    {
        return [
            self::KINNEY_EMPTY          => Yii::t('modelattr', '(not set)'),
            self::PROB_HARDLYCONC       => Yii::t('modelattr', '0.1 - Hardly conceivable'),
            self::PROB_PRACTICALLYIMPOS => Yii::t('modelattr', '0.2 - Practically impossible'),
            self::PROB_CONCEIVABLE      => Yii::t('modelattr', '0.5 - Conceivable but unlikely'),
            self::PROB_UNLIKELY         => Yii::t('modelattr', '1 - Unlikely but possible in borderline cases'),
            self::PROB_UNCOMMON         => Yii::t('modelattr', '3 - Uncommon'),
            self::PROB_POSSIBLE         => Yii::t('modelattr', '6 - Quite possible'),
            self::PROB_PREDICTABLE      => Yii::t('modelattr', '10 - Predictable')
        ];
    }

    /**
     * @param $type
     *
     * @return mixed|string
     */
    public static function getProb($type)
    {
        $all = self::allProb();
        if (isset($all[$type])) {
            return $all[$type];
        }
        return '-';
    }

    /**
     * @return array
     */
    public static function allScores(): array
    {
        return [
            self::SCORE_EXTREME    => Yii::t('modelattr', 'Extreme risk - stop operations immediately - R>400'),
            self::SCORE_HIGH       => Yii::t('modelattr', 'High risk - immediate action required - 200<R<=400'),
            self::SCORE_MEDIUM     => Yii::t('modelattr', 'Medium risk - action required - 70<R<=200'),
            self::SCORE_LOW        => Yii::t('modelattr', 'Low risk - attention required - 20<R<=70'),
            self::SCORE_NEGLIGIBLE => Yii::t('modelattr', 'Negligible risk - no action required - R<=20')
        ];
    }

    /**
     * @return array
     */
    public static function allColors(): array
    {
        return [
            self::SCORE_EXTREME    => self::COLOR_BLACK,
            self::SCORE_HIGH       => self::COLOR_RED,
            self::SCORE_MEDIUM     => self::COLOR_ORANGE,
            self::SCORE_LOW        => self::COLOR_YELLOW,
            self::SCORE_NEGLIGIBLE => self::COLOR_GREEN
        ];
    }

    /**
     * @param $type
     *
     * @return mixed|string
     */
    public static function getScore($type)
    {
        $all = self::allScores();
        if (isset($all[$type])) {
            return $all[$type];
        }
        return '-';
    }

    /**
     * @param int|null $risk_score
     *
     * @return string
     */
    public static function getKinneyRiskColor(int $risk_score = null): string
    {
        switch (true) {
            case ($risk_score === null):
                return self::COLOR_WHITE;
                break;
            case ($risk_score > 400):
                return self::COLOR_BLACK;
                break;
            case ($risk_score > 200):
                return self::COLOR_RED;
                break;
            case ($risk_score > 70):
                return self::COLOR_ORANGE;
                break;
            case ($risk_score > 20):
                return self::COLOR_YELLOW;
                break;
            default:
                return self::COLOR_GREEN;
                break;
        }
    }

    /**
     * @param int|null $risk_score
     *
     * @return string
     */
    public static function getKinneyRiskTextLevel(int $risk_score = null): string
    {
        switch (true) {
            case ($risk_score === null):
                return self::NUMBER_ZERO;
                break;
            case ($risk_score > 400):
                return self::NUMBER_FIVE;
                break;
            case ($risk_score > 200):
                return self::NUMBER_FOUR;
                break;
            case ($risk_score > 70):
                return self::NUMBER_THREE;
                break;
            case ($risk_score > 20):
                return self::NUMBER_TWO;
                break;
            default:
                return self::NUMBER_ONE;
                break;
        }
    }

    /**
     * @param int|null $risk_score
     *
     * @return string
     */
    public static function getKinneyRiskText($risk_score = null)
    {
        switch (true) {
            case ($risk_score === null):
                return '--';
                break;
            case ($risk_score > 400):
                return Yii::t('modelattr', 'Extreme risk - stop operations immediately - R>400');
                break;
            case ($risk_score > 200):
                return Yii::t('modelattr', 'High risk - immediate action required - 200<R<=400');
                break;
            case ($risk_score > 70):
                return Yii::t('modelattr', 'Medium risk - action required - 70<R<=200');
                break;
            case ($risk_score > 20):
                return Yii::t('modelattr', 'Low risk - attention required - 20<R<=70');
                break;
            default:
                return Yii::t('modelattr', 'Negligible risk - no action required - R<=20');
                break;
        }
    }
}
