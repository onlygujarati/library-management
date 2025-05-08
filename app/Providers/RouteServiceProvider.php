<?php

public const HOME = '/api';

public function boot()
{
    $this->routes(function () {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));
    });
}
