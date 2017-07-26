<?php

namespace App\Repo;

use Illuminate\Database\Connection;
use Illuminate\Support\Collection;

class StreetRepository implements StreetRepositoryInterface
{
    /**
     * @var \Illuminate\Database\Connection
     */
    protected $db;

    /**
     * StreetRepository constructor.
     *
     * @param \Illuminate\Database\Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    protected function query()
    {
        return $this->db->table('streets');
    }

    /**
     * @param array $columns
     * @param int $offset
     * @param int $limit
     *
     * @return \Illuminate\Support\Collection
     */
    public function fetchList(array $columns = ['*'], $offset = 0, $limit = StreetRepositoryInterface::DEFAULT_LIMIT)
    {
        return $this->query()
            ->skip($offset)
            ->limit($limit)
            ->get($columns);
    }

    /**
     * @param null $page
     * @param array $columns
     * @param int $perPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateList($page = null, array $columns = ['*'], $perPage = StreetRepositoryInterface::DEFAULT_LIMIT)
    {
        return $this->query()
            ->paginate($perPage, $columns, 'page', $page);
    }

    /**
     * @param $id
     * @param array $columns
     *
     * @return object|null
     */
    public function fetchOneById($id, array $columns = ['*'])
    {
        return $this->query()
            ->find($id, $columns);
    }

    /**
     * @param $cityId
     * @param array $columns
     * @param int $offset
     * @param int $limit
     *
     * @return \Illuminate\Support\Collection
     */
    public function fetchListByCity($cityId, array $columns = ['*'], $offset = 0, $limit = StreetRepositoryInterface::DEFAULT_LIMIT)
    {
        return $this->query()
            ->where('city_id', '=', $cityId)
            ->skip($offset)
            ->limit($limit)
            ->get($columns);
    }

    /**
     * @param array $conditions
     * @param array $columns
     * @param int $offset
     * @param int $limit
     *
     * @return \Illuminate\Support\Collection
     */
    public function fetchListByFields(array $conditions, array $columns = ['*'], $offset = 0, $limit = StreetRepositoryInterface::DEFAULT_LIMIT)
    {
        $query = $this->query();

        foreach ($conditions as $field => $value) {
            $query->where($field, '=', $value);
        }

        return $query
            ->skip($offset)
            ->limit($limit)
            ->get($columns);
    }

    /**
     * @param array $data
     *
     * @return int
     */
    public function store(array $data)
    {
        $id = $this->query()
            ->insertGetId($data);

        return $id;
    }

    /**
     * @param \Illuminate\Support\Collection $list
     *
     * @return bool
     */
    public function storeMany(Collection $list)
    {
        return $this->query()
            ->insert($list->all());
    }

    /**
     * @param $id
     * @param array $data
     *
     * @return int
     */
    public function updateById($id, array $data)
    {
        return $this->query()
            ->where('id', '=', $id)
            ->update($data);
    }

    /**
     * @param array $conditions
     * @param array $data
     *
     * @return int
     */
    public function updateMany(array $conditions, array $data)
    {
        $query = $this->query();

        foreach ($conditions as $field => $value) {
            $query->where($field, '=', $value);
        }

        return $query->update($data);
    }
}
