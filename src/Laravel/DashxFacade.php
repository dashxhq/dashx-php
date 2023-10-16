<?php

namespace Dashx\Php\Laravel;

use Illuminate\Support\Facades\Facade;

class DashxFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'DashX';
    }
}
