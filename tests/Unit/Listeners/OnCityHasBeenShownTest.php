<?php

namespace Tests\Unit\Listeners;

use App\City;
use App\Listeners\OnCityHasBeenShown;
use Mockery as m;
use App\Events\CityShown;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Tests\SimpleTestCase;

class OnCityHasBeenShownTest extends SimpleTestCase
{
    public function test_it_stores_time_to_session()
    {
        $e = new CityShown(new City(['id' => 555]));

        $session = m::mock(SessionInterface::class);
        $l = new OnCityHasBeenShown($session);

        $session->shouldReceive('set')->with('city.shown.555', m::any());
        $this->assertNull($l->handle($e));
    }
}
