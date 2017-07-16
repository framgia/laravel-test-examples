<?php

namespace Tests\Unit\Events;

use App\City;
use App\Events\CityShown;
use Tests\SimpleTestCase;

class CityShownTest extends SimpleTestCase
{
    public function test_event_constructor()
    {
        $city = new City([
            'id' => 111,
        ]);

        $e = new CityShown($city);

        $this->assertSame($city, $e->city);
    }
}
