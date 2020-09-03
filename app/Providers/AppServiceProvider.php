<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Set singletons
     *
     * @var array
     */
    public $singletons = [
    ];


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRepositoryBinding();
    }

    /**
     * Binding repository interface and class.
     */
    private function registerRepositoryBinding(): void
    {
        $simgletons = [
            \App\Repositories\Interfaces\UserRepositoryInterface::class => \App\Repositories\Mysql\UserRepository::class,
            \App\Repositories\Interfaces\AuthorRepositoryInterface::class => \App\Repositories\Mysql\AuthorRepository::class,
            \App\Repositories\Interfaces\BookRepositoryInterface::class => \App\Repositories\Mysql\BookRepository::class,
        ];

        $this->singletons = \array_merge($this->singletons, $simgletons);
    }
}
