<?php

namespace App\Providers;

use App\Contracts\IUserRepository;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider {

    public function register() {
        $this->app->bind(
            IUserRepository::class,
            UserRepository::class
        );
    }

}
