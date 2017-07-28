<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Events\CityShown;
use Illuminate\Contracts\Session\Session;

class OnCityHasBeenShown
{
    /**
     * @var \Illuminate\Contracts\Session\Session
     */
    protected $session;

    /**
     * Create the event listener.
     *
     * @param \Illuminate\Contracts\Session\Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Handle the event.
     *
     * @param  CityShown  $event
     * @return void
     */
    public function handle(CityShown $event)
    {
        $this->session->put('city.shown.'.$event->city->getKey(), Carbon::now());
    }
}
