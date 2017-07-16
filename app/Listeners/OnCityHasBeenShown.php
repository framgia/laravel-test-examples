<?php

namespace App\Listeners;

use App\Events\CityShown;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OnCityHasBeenShown
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CityShown  $event
     * @return void
     */
    public function handle(CityShown $event)
    {
        //
    }
}
