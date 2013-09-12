<?php
/**
 * Koowa Framework - http://developer.joomlatools.com/koowa
 *
 * @copyright      Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://github.com/joomlatools/koowa-activities for the canonical source repository
 */

/**
 * Activity Controller Permissions.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Koowa\Component\Activities
 */
class ComActivitiesControllerPermissionActivity extends ComKoowaControllerPermissionAbstract
{
    public function canAdd()
    {
        $result = false;
        if (!$this->getMixer()->isDispacthed())
        {
            $result = true;
        }
        return $result;
    }

    public function canEdit()
    {
        return false;
    }
}