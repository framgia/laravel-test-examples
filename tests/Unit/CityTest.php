<?php

namespace Tests\Unit;

use App\City;
use App\Street;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\ModelTestCase;

class CityTest extends ModelTestCase
{
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new City(), [
            'name',
        ]);
    }

    public function test_streets_relation()
    {
        $m = new City();
        $r = $m->streets();
        $this->assertHasManyRelation($r, $m, new Street());
    }
}
