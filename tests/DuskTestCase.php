<?php

namespace Tests;

use App\User;
use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        switch (config('dusk.driver')) {
            case 'container': // For running inside docker container
                return RemoteWebDriver::create(
                    'http://selenium:4444/wd/hub', DesiredCapabilities::chrome()
                );
            default: // Use chrome default driver for local
                return RemoteWebDriver::create(
                    'http://localhost:9515', DesiredCapabilities::chrome()
                );
        }
    }

    protected function user()
    {
        return (new User());
    }
}
