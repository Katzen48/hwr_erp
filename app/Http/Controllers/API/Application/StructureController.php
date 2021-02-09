<?php

namespace App\Http\Controllers\API\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class StructureController extends Controller
{
    public function routes()
    {
        return collect(Route::getRoutes()->getRoutes())->filter(function ($route) {
            return $route->action && array_key_exists('controller', $route->action) && $route->action['controller'];
        })->unique(function ($route) {
            return explode('@', $route->action['controller'])[0];
        })->filter(function ($route) {
            $controller = explode('@', $route->action['controller'])[0];

            return method_exists($controller, 'getDashboardTitle') && method_exists($controller, 'getDashboardParent');
        })->map(function ($route) {
            $controller = explode('@', $route->action['controller'])[0];

            return [
                'uri' => '/' . $route->uri,
                'title' => $controller::getDashboardTitle(),
                'parent' => $controller::getDashboardParent(),
            ];
        })->values();
    }
}
