<?php

namespace Tests\Unit;

use App\City;
use App\Street;
use Tests\ModelTestCase;

class StreetTest extends ModelTestCase
{
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new Street(), [
            'city_id', 'name',
        ]);
    }

    public function test_streets_relation()
    {
        $m = new Street();
        $r = $m->city();
        $this->assertBelongsToRelation($r, $m, new City(), 'city_id');
    }
}
