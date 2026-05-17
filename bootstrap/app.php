<?php

use App\Http\Middleware\CheckEmployeeStatus;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\ForcePasswordChange;
use App\Http\Middleware\LogUserActivity;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
         $middleware->alias([
        'role' => CheckRole::class,
        'force.password.change' => ForcePasswordChange::class,
        'check.employee.status' => CheckEmployeeStatus::class,
        'guest' => RedirectIfAuthenticated::class,
        'log.activity' => LogUserActivity::class,
        'spatie_role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

          $middleware->web(append: [
            LogUserActivity::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
        $exceptions->render(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            return response()->view('errors.403', [], 403);
        });
    })->create();
