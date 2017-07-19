<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Events\CityShown;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OnCityHasBeenShown
{
    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    /**
     * Create the event listener.
     *
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     */
    public function __construct(SessionInterface $session)
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
        $this->session->set('city.shown.'.$event->city->getKey(), Carbon::now());
    }
}
