<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Policies\ChatPolicy;
use App\Policies\MessagePolicy;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Mapovanie modelov na politiky.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Chat::class => ChatPolicy::class,
        Message::class => MessagePolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Zaregistrovanie akýchkoľvek autentifikačných / autorizačných služieb.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Prípadné vlastné pravidlá cez Gate::define() sem.
    }
}
