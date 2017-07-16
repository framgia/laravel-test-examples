<?php

namespace App\Events;

use App\City;
use Illuminate\Queue\SerializesModels;

class CityShown
{
    use SerializesModels;

    /**
     * @var \App\City
     */
    public $city;

    /**
     * Create a new event instance.
     *
     * @param \App\City $city
     */
    public function __construct(City $city)
    {
        $this->city = $city;
    }
}
