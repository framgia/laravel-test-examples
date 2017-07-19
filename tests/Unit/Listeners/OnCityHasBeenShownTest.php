<?php

namespace Tests\Unit\Listeners;

use App\City;
use DateTime;
use Mockery as m;
use App\Events\CityShown;
use Tests\SimpleTestCase;
use App\Listeners\OnCityHasBeenShown;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OnCityHasBeenShownTest extends SimpleTestCase
{
    public function test_it_stores_time_to_session()
    {
        City::unguard();
        $e = new CityShown(new City(['id' => 555]));

        $session = m::mock(SessionInterface::class);
        $l = new OnCityHasBeenShown($session);

        $session->shouldReceive('set')->once()->with('city.shown.555', m::type(DateTime::class));
        $this->assertNull($l->handle($e));
    }
}
