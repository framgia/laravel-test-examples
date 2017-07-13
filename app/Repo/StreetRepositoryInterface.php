<?php
/**
 * Created by PhpStorm.
 * User: roman.kinyakin
 * Date: 14.07.2017
 * Time: 1:04 AM
 */

namespace App\Repo;

use Illuminate\Support\Collection;

interface StreetRepositoryInterface
{
    const DEFAULT_LIMIT = 10;

    /**
     * @param array $columns
     * @param int $offset
     * @param int $limit
     *
     * @return \Illuminate\Support\Collection
     */
    public function fetchList(array $columns = ['*'], $offset = 0, $limit = self::DEFAULT_LIMIT);

    /**
     * @param null $page
     * @param array $columns
     * @param int $perPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateList($page = null, array $columns = ['*'], $perPage = self::DEFAULT_LIMIT);

    /**
     * @param $id
     * @param array $columns
     *
     * @return object|null
     */
    public function fetchOneById($id, array $columns = ['*']);

    /**
     * @param $cityId
     * @param array $columns
     * @param int $offset
     * @param int $limit
     *
     * @return \Illuminate\Support\Collection
     */
    public function fetchListByCity($cityId, array $columns = ['*'], $offset = 0, $limit = self::DEFAULT_LIMIT);

    /**
     * @param array $conditions
     * @param array $columns
     * @param int $offset
     * @param int $limit
     *
     * @return \Illuminate\Support\Collection
     */
    public function fetchListByFields(array $conditions, array $columns = ['*'], $offset = 0, $limit = self::DEFAULT_LIMIT);

    /**
     * @param array $data
     *
     * @return int
     */
    public function store(array $data);

    /**
     * @param \Illuminate\Support\Collection $list
     *
     * @return bool
     */
    public function storeMany(Collection $list);

    /**
     * @param $id
     * @param array $data
     *
     * @return int
     */
    public function updateById($id, array $data);

    /**
     * @param array $conditions
     * @param array $data
     *
     * @return int
     */
    public function updateMany(array $conditions, array $data);
}