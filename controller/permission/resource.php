<?php
/**
 * Nooku Framework - http://nooku.org/framework
 *
 * @copyright   Copyright (C) 2011 - 2015 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://github.com/joomlatools/joomlatools-framework-activities for the canonical source repository
 */

/**
 * Resource Controller Permission.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Koowa\Component\Activities
 */
class ComActivitiesControllerPermissionResource extends KControllerPermissionAbstract
{
    public function canAdd()
    {
        return !$this->isDispatched(); // Do not allow resource to be added if the controller is dispatched.
    }

    public function canEdit()
    {
        return $this->canAdd();
    }

    public function canDelete()
    {
        return $this->canAdd();
    }
}