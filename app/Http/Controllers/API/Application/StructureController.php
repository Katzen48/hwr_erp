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
        })->mapWithKeys(function ($route) {
            $apiUrl = '/' . $route->uri;
            $controller = explode('@', $route->action['controller'])[0];
            $parent = null;

            if($parentController = $controller::getDashboardParent()) {
                $parent = $parentController::getDashboardId();
            }

            $entity = [
                'primary_key' => $controller::getPrimaryKey(),
                'api_url' => $apiUrl,
                'title' => $controller::getDashboardTitle(),
                'parent' => $parent,
                'type' => 'List',
                'fields' => $controller::getDashboardFields(),
            ];

            if($controller::isEditable()) {
                $entity['edit'] = [
                    'primary_key' => $controller::getPrimaryKey(),
                    'title' => $controller::getDashboardTitle(),
                    'api_url' => $apiUrl,
                    'type' => 'Card',
                    'fields' => $controller::getEditFields(),
                ];
            }

            return [
                $controller::getDashboardId() => $entity
            ];
        })->all();
    }
}
