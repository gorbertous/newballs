<?php

namespace common\context;

/**
 * Interface Context
 * @package common\context
 */
interface Context
{
    const LBL_NEW    = 'New';
    const LBL_MODIFY = 'Modify';
    const LBL_VIEW   = 'View';
    const LBL_DELETE = 'Delete';
    const LBL_PRINT  = 'Print';

    /**
     * Return an array with all the information about the
     * page ( title, icons, labels... )
     * -> page header texts
     * -> horizontal menu button layout and text
     * -> gridview action button layout and text
     *
     * @return array
     */
    public static function getContextArray();

    /**
     * Return an array with all the information about the
     * user page
     *
     * @return array
     */
    public static function getUsersContextArray();

    /**
     * Return an array with all the information about the
     * text page
     *
     * @return array
     */
    public static function getTextsContextArray();
}