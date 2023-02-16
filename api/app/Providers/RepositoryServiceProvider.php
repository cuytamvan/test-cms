<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    protected $repositories = [
        'User',
        'Role',
        'Permission',
        'Article',
        'ArticleCategory',
        'ArticleCategory',
    ];

    public function register(): void
    {
        foreach ($this->repositories as $r)
            $this->app->bind("App\\Repositories\\{$r}Repository", "App\\Repositories\\{$r}RepositoryEloquent");
    }

    public function boot(): void
    {
        //
    }
}
