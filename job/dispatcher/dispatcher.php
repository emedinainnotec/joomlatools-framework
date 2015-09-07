<?php
/**
 * Nooku Framework - http://nooku.org/framework
 *
 * @copyright   Copyright (C) 2015 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://github.com/nooku/nooku-scheduler for the canonical source repository
 */

/**
 * Job dispatcher
 *
 * Runs the jobs by ordering and priority
 *
 * @author Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Koowa\Component\Scheduler
 */
class ComSchedulerJobDispatcher extends ComSchedulerJobDispatcherAbstract
{
    /**
     * @param KObjectConfig $config
     */
    public function __construct(KObjectConfig $config)
    {
        parent::__construct($config);

        @set_time_limit(60);
        @ini_set('memory_limit', '256M');
        @ignore_user_abort(true);
    }

    /**
     * Dispatches the next job in line
     *
     * @return bool
     */
    public function dispatch()
    {
        if ($job = $this->pickNextJob())
        {
            $result = false;

            /** @var ComSchedulerJobInterface $runner */
            $runner = $this->getObject($job->id, array(
                'state'       => $job->getState(),
                'stop_on'     => time()+15,
                'logger'      => array($this, 'log')
            ));

            // Set to running
            $job->status = 1;
            $job->save();

            try
            {
                $this->log('dispatch job', $runner);

                try {
                    $result = $runner->run();
                }
                catch (Exception $e)
                {
                    $this->log('exception thrown: '.$e->getMessage(), $runner);

                    $result = ComSchedulerJobInterface::JOB_ERROR;
                }

                $this->log('result: '.$result, $runner);

                /*
                complete:
                    high priority: put it on the top of low priority queue
                    low priority:  put it on the bottom of low priority queue
                suspend:
                    high priority: put it on the top of high priority queue
                    low priority:  put it on the bottom of high priority queue
                */

                $job->ordering = $runner->isPrioritized() ? -PHP_INT_MAX : PHP_INT_MAX;

                if ($result === ComSchedulerJobInterface::JOB_SUSPEND) {
                    $job->queue = 1;
                }
                else {
                    $job->completed_on = gmdate('Y-m-d H:i:s');
                    $job->queue = 0;
                }
            }
            catch (Exception $e) {
            }

            if ($result === ComSchedulerJobInterface::JOB_COMPLETE && !$this->_getNextRun($job)) {
                $job->delete();
            }
            else {
                // Stop the job
                $job->status = 0;
                $job->save();
            }

            return true;
        }

        return false;
    }

    /**
     * Picks the next job to run based on priority
     *
     * @return null|KDatabaseRowInterface
     */
    public function pickNextJob()
    {
        $this->_quitStaleJobs();

        if ($this->getModel()->status(1)->count() === 0)
        {
            $high_priority = $this->getModel()->status(0)->sort('ordering')->queue(1)->fetch();

            if (count($high_priority) === 0)
            {
                $low_priority = $this->getModel()->status(0)->sort('ordering')->queue(0)->fetch();

                foreach ($low_priority as $job)
                {
                    if ($this->_isDue($job))
                    {
                        $job->queue = 1;

                        if ($job->save()) {
                            return $job;
                        }
                    }
                }
            }
            else
            {
                foreach ($high_priority as $job)
                {
                    if ($this->_isDue($job)) {
                        return $job;
                    }
                }
            }
        }



        return null;
    }

    protected function _isDue($job)
    {
        $result = true;

        if ($job->completed_on !== '0000-00-00 00:00:00')
        {
            try {
                $cron   = Cron\CronExpression::factory($job->frequency);
                $result = $cron->getNextRunDate($job->completed_on) < new DateTime('now');
            }
            catch (RuntimeException $e) {
                $result = true; // last run and it'll be deleted
            }
            catch (Exception $e) {
                $result = false;
            }
        }

        return $result;
    }

    protected function _getNextRun($job)
    {
        $result = false;

        try {
            $cron   = Cron\CronExpression::factory($job->frequency);
            $result = $cron->getNextRunDate();
        }
        catch (RuntimeException $e) {
            // never gonna run again :(
        }

        return $result;
    }

    protected function _quitStaleJobs()
    {
        $stale = $this->getModel()->stale(1)->fetch();

        if (count($stale))
        {
            foreach ($stale as $entity) {
                $entity->status = 0;
            }

            $stale->save();
        }
    }
}