<?php

namespace App\Traits;

/**
 * Trait HttpDelayTrait
 * @package App\Traits
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
trait HttpDelayTrait
{
    /**
     * @param int $seconds
     * @return void
     */
    public function addDelay(int $seconds): void
    {
        ini_set('default_socket_timeout', 60);
        ini_set('max_execution_time', 60);

        sleep($seconds);
    }
}
