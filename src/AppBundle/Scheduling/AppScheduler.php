<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 20/04/2018
 * Time: 20:35
 */

namespace AppBundle\Scheduling;


use MIT\Bundle\SchedulerBundle\Scheduler\Scheduler;

class AppScheduler
{
    /** @var Scheduler */
    private $mitScheduler;

    /**
     * AppScheduler constructor.
     *
     * @param Scheduler $mitScheduler
     */
    public function __construct(Scheduler $mitScheduler)
    {
        $this->mitScheduler = $mitScheduler;
    }
}