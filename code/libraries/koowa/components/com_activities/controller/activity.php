<?php
/**
 * Nooku Framework - http://nooku.org/framework
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-activities for the canonical source repository
 */

/**
 * Activity Controller
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Koowa\Component\Activities
 */
class ComActivitiesControllerActivity extends ComKoowaControllerModel
{
    /**
     * Constructor.
     *
     * @param   KObjectConfig $config Configuration options
     */
    public function __construct(KObjectConfig $config)
    {
        parent::__construct($config);

        $this->getObject('translator')->load('com:activities');
    }

    /**
     * Purge action. Deletes all activities between start and and date.
     *
     * @param	KControllerContextInterface	$context A command context object
     * @throws  KControllerExceptionActionFailed   If the activities cannot be purged
     * @return  KModelEntityInterface
     */
    protected function _actionPurge(KControllerContextInterface $context)
    {
        $model = $this->getModel();
        $state = $model->getState();
        $query = $this->getObject('lib:database.query.delete');

        $query->table(array($model->getTable()->getName()));

        if ($state->end_date && $state->end_date != '0000-00-00')
        {
            $end_date = $this->getObject('lib:date', array('date' => $state->end_date));
            $end      = $end_date->format('Y-m-d');

            $query->where('DATE(created_on) <= :end')->bind(array('end' => $end));
        }

        if (!$this->getModel()->getTable()->getAdapter()->execute($query)) {
            throw new KControllerExceptionActionFailed('Delete Action Failed');
        } else {
            $context->status = KHttpResponse::NO_CONTENT;
        }
    }

    /**
     * Set the ip address if we are adding a new activity
     *
     * @param	KControllerContextInterface	$context A command context object
     * @return  KModelEntityInterface
     */
    protected function _beforeAdd(KControllerContextInterface $context)
    {
        $context->request->data->ip = $this->getObject('request')->getAddress();
    }
}
