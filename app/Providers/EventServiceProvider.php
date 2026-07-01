<?php

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

use App\Listeners\LogSuccessfulLogin;
use App\Listeners\LogSuccessfulLogout;

class EventServiceProvider extends Illuminate\Foundation\Support\Providers\EventServiceProvider
{
    protected $listen = [

    Login::class => [
        LogSuccessfulLogin::class,
    ],

    Logout::class => [
        LogSuccessfulLogout::class,
    ],

];
}
