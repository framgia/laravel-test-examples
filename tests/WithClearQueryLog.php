<?php

namespace Tests;

trait WithClearQueryLog
{
    /**
     * @var \Illuminate\Database\Connection
     */
    protected $db;

    protected function setUpQueryLog()
    {
        $this->db = $this->app->make('db');
        $this->db->flushQueryLog();
        $this->db->enableQueryLog();
    }

    protected function tearDownQueryLog()
    {
        $this->db->flushQueryLog();
        $this->db->disableQueryLog();
    }
}
