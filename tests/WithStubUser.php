<?php

namespace Tests;

use App\User;

trait WithStubUser
{
    /**
     * @var \App\User
     */
    protected $user;

    public function createStubUser(array $data = [])
    {
        $data = array_merge([
            'name' => 'Test User',
            'email' => 'test-user-'.uniqid().'@example.com',
            'password' => bcrypt('123456'),
        ], $data);

        return $this->user = User::create($data);
    }

    public function deleteStubUser()
    {
        $this->user->forceDelete();
    }
}
