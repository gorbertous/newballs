<?php

/**
 * @copyright Copyright © 2016-2018 eSST Sàrl - HSE compliance tools.
 * @link https://www.esst.lu/
 * @license https://www.esst.lu/backend/licence/
 */

namespace backend\widgets;

use yii\grid\Column;
use yii\helpers\Html;

/**
 * Class GridView
 *
 * @author gorbertous
 * @since 1.0.0
 * @package backend\widgets
 */
class GridView extends \kartik\grid\GridView
{
    private $currentgroup;
    private $groupLevelClass;
    private $defaultGroupLevelClass = ['kv-grouped-row' , ''];
    private $colspan = null;

    /**
     * @inheritdoc
     */
    public function renderPageSummary()
    {
        $prender = parent::renderPageSummary();
        $cols = $this->splitbytag($prender, 'td');

        foreach ($this->columns as $index => $column) {
            /* @var $column Column */
            /** @noinspection PhpUndefinedFieldInspection */
            if (property_exists($column, 'groupedRow') && $column->groupedRow) {
                // remove this column
                $cols[$index] = '';
            }
        }

        return join('', $cols);

    }

    /**
     * @inheritdoc
     */
    public function renderTableHeader()
    {
        $prender = parent::renderTableHeader();
        $cols = $this->splitbytag($prender, 'th');

        foreach ($this->columns as $index => $column) {
            /* @var $column Column */
            /** @noinspection PhpUndefinedFieldInspection */
            if (property_exists($column, 'groupedRow') && $column->groupedRow) {
                // remove this column
                $cols[$index] = '';
            }
        }

        return join('', $cols);
    }

    /**
     * @inheritdoc
     */
    public function renderFilters()
    {
        $prender = parent::renderFilters();
        $cols = $this->splitbytag($prender, 'td');
        foreach ($this->columns as $index => $column) {
            /* @var $column Column */
            /** @noinspection PhpUndefinedFieldInspection */
            if (property_exists($column, 'groupedRow') && $column->groupedRow) {
                // remove this column
                $cols[$index-1] = '';
            }
        }

        return join('', $cols);

    }

    /**
     * @inheritdoc
     */
    public function renderTableRow($model, $key, $index)
    {
        // first count the columns where groupedRow = false
        $this->countspan();

        $prender = parent::renderTableRow($model, $key, $index);
        $cols = $this->splitbytag($prender, 'td');

        $grouprow = '';
        foreach ($this->columns as $index => $column) {
            /* @var $column Column */
            /** @noinspection PhpUndefinedFieldInspection */
            if (property_exists($column, 'groupedRow') && $column->groupedRow) {
                $re = '/<td.*?>(.*?)<\/td>/';
                preg_match($re, $cols[$index], $matches);
                if ($this->currentgroup[$index] !== $matches[1]) {
                    $cell = Html::tag('td', (!empty($column->label) ? $column->label . ': ' : '') . $matches[1],
                        ['class'   => $this->groupLevelClass[$index],
                         'colspan' => $this->colspan]);
                    // we have a new group, insert a row with the group title
                    $grouprow .= Html::tag('tr', $cell);
                    $this->currentgroup[$index] = $matches[1];
                }
                // remove this column
                $cols[$index] = '';
            }
        }

        return $grouprow . join('', $cols);

    }

    /**
     * split a html text, based on the tag parameter
     * scans for '<th ' and '<th>' opening tags
     *
     * @param string $html
     * @param string $tag
     * @return array
     */
    private function splitbytag(string $html, string $tag): array
    {
        $opentag = '<' . $tag;
        $closetag = '</' . $tag . '>';
        $splitbytag = [];
        $col = 0;
        $nocol = 0;
        while (($pos = $this->strpos_first($html, $opentag . ' ', $opentag . '>')) !== false) {
            if ($pos > 0) {
                $splitbytag['nocol' . $nocol++] = substr($html, 0, $pos);
                $html = substr($html, $pos);
            }
            $endpos = strpos($html, $closetag) + strlen($closetag);
            $splitbytag[$col++] = substr($html, 0, $endpos);
            $html = substr($html, $endpos);
        }
        if ($html != '') {
            /** @noinspection PhpUnusedLocalVariableInspection */
            $splitbytag['nocol' . $nocol++] = $html;
        }
        return $splitbytag;
    }

    /**
     * finds the first occurence of needle1 or needle2 and return:
     * - false if both are not found
     * - position of needle1 if needle2 is not found
     * - position of needle2 if needle1 is not found
     * - first position of needle1 or needle2 if both are found
     *
     * @param string $haystack
     * @param string $needle1
     * @param string $needle2
     * @return bool|int|mixed
     */
    private function strpos_first(string $haystack, string $needle1, string $needle2)
    {
        $strpos1 = strpos($haystack, $needle1);
        $strpos2 = strpos($haystack, $needle2);

        if ($strpos1 === false && $strpos2 === false) {
            return false;
        } elseif ($strpos1 === false) {
            return $strpos2;
        } elseif ($strpos2 === false) {
            return $strpos1;
        } else {
            return min($strpos1, $strpos2);
        }
    }

    /**
     * counts all the column which have groupedRow = false
     * this value is used to span the group header rows on the whole table
     */
    private function countspan()
    {
        $currentGroupLevel = 0;
        if ($this->colspan === null) {
            foreach ($this->columns as $index => $column) {
                /* @var $column Column */
                /** @noinspection PhpUndefinedFieldInspection */
                if (!(property_exists($column, 'groupedRow') && $column->groupedRow)) {
                    $this->colspan++;
                } else {
                    // initialize the initial group level class
                    $this->groupLevelClass[$index] = $this->defaultGroupLevelClass[$currentGroupLevel++];
                    // initialize the initial group values
                    $this->currentgroup[$index] = '$$$';
                }
            }
        }
    }
}
