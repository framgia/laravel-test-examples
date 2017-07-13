<?php

namespace App\Repo;

use App\Street;

class StreetModelRepository extends StreetRepository implements StreetRepositoryInterface
{
    /**
     * @var \App\Street
     */
    protected $model;

    /**
     * StreetModelRepository constructor.
     */
    public function __construct()
    {
        $this->model = new Street();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function query()
    {
        return $this->model->newQuery();
    }
}