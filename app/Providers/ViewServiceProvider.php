<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\OffDayRequest;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('layouts.sidebar', function ($view) {
            $pendingRequestsCount = OffDayRequest::where('status', 'pending')->count();

            $view->with('pendingRequestsCount', $pendingRequestsCount);
        });
    }
}
