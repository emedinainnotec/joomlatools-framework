<?php
/**
 * Koowa Framework - http://developer.joomlatools.com/koowa
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/joomlatools/koowa for the canonical source repository
 */

/**
 * Translator Alias Catalogue
 * 
 * Joomla 1.6+ uses some common keys like JALL, JYES. This class is used to map plain words to them.
 *
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Koowa\Component\Koowa
 */
class ComKoowaTranslatorCatalogueAliases extends KTranslatorCatalogue
{
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   KObjectConfig $config An optional KObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KObjectConfig $config)
    {
        $defaults = array(
            'all' => 'JALL',
            'title' => 'JGLOBAL_TITLE',
            'alias' => 'JFIELD_ALIAS_LABEL',
            'status' => 'JSTATUS',
            'category' => 'JCATEGORY',
            'categories' => 'JCATEGORIES',
            'access' => 'JGRID_HEADING_ACCESS',
            'date' => 'JDATE',
            'ordering' => 'JGRID_HEADING_ORDERING',
            'search' => 'JSEARCH_FILTER_SUBMIT',
            'clear' => 'JSEARCH_FILTER_CLEAR',
            'details' => 'JDETAILS',
            'description' => 'JGLOBAL_DESCRIPTION',
            'apply' => 'JAPPLY',
            'cancel' => 'JCANCEL',
            'created date' => 'JGLOBAL_FIELD_CREATED_LABEL',
            'created by' => 'JGLOBAL_FIELD_CREATED_BY_LABEL',
            'modified date' => 'JGLOBAL_FIELD_MODIFIED_LABEL',
            'last modified' => 'JGLOBAL_FIELD_MODIFIED_LABEL',
            'modified by' => 'JGLOBAL_FIELD_MODIFIED_BY_LABEL',
            'published' => 'JPUBLISHED',
            'unpublished' => 'JUNPUBLISHED',
            'metadata options' => 'JGLOBAL_FIELDSET_METADATA_OPTIONS',
            'options' => 'JOPTIONS',
            'unpublish item' => 'JLIB_HTML_UNPUBLISH_ITEM',
            'publish item' => 'JLIB_HTML_PUBLISH_ITEM',
            'move down' => 'JLIB_HTML_MOVE_DOWN',
            'move up' => 'JLIB_HTML_MOVE_UP',
            'select' => 'JSELECT',
            'yes' => 'JYES',
            'no' => 'JNO',
            'enabled' => 'JENABLED',
            'disabled' => 'JDISABLED',
            'click to sort by this column' => 'JGLOBAL_CLICK_TO_SORT_THIS_COLUMN',
            'about the calendar' => 'JLIB_HTML_BEHAVIOR_ABOUT_THE_CALENDAR',
            'go today' => 'JLIB_HTML_BEHAVIOR_GO_TODAY',
            'select date' => 'JLIB_HTML_BEHAVIOR_SELECT_DATE',
            'drag to move' => 'JLIB_HTML_BEHAVIOR_DRAG_TO_MOVE',
            'display %s first' => 'JLIB_HTML_BEHAVIOR_DISPLAY_S_FIRST',
            'close' => 'JLIB_HTML_BEHAVIOR_CLOSE',
            'today' => 'JLIB_HTML_BEHAVIOR_TODAY',
            'wk' => 'JLIB_HTML_BEHAVIOR_WK',
            'time:' => 'JLIB_HTML_BEHAVIOR_TIME',
            'prev. year (hold for menu)' => 'JLIB_HTML_BEHAVIOR_PREV_YEAR_HOLD_FOR_MENU',
            'prev. month (hold for menu)' => 'JLIB_HTML_BEHAVIOR_PREV_MONTH_HOLD_FOR_MENU',
            'next month (hold for menu)' => 'JLIB_HTML_BEHAVIOR_NEXT_MONTH_HOLD_FOR_MENU',
            'next year (hold for menu)' => 'JLIB_HTML_BEHAVIOR_NEXT_YEAR_HOLD_FOR_MENU',
            '(shift-)click or drag to change value' => 'JLIB_HTML_BEHAVIOR_SHIFT_CLICK_OR_DRAG_TO_CHANGE_VALUE',
            '%a, %b %e' => 'JLIB_HTML_BEHAVIOR_TT_DATE_FORMAT',
            'start' => 'JLIB_HTML_START',
            'prev' => 'JPREV',
            'next' => 'JNEXT',
            'end' => 'JLIB_HTML_END'
        );

        if ($this->getIdentifier()->domain === 'admin')
        {
            $config->append(array(
                'data'  => $defaults
            ));
        }
    
        parent::_initialize($config);
    }
}
