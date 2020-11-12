<?php

namespace Xuandan\Facades;

use Illuminate\Support\Facades\Facade;
use Xuandan\Application;

/**
 * @mixin Application
 */
class Xuandan extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Application::class;
    }

    /**
     * @return Application
     */
    public static function getFacadeRoot()
    {
        return parent::getFacadeRoot();
    }
}
