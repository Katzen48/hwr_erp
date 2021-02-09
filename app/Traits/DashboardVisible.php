<?php
/**
 * User: Katzen48
 * Date: 02.02.2021
 * Time: 16:31
 */

namespace App\Traits;


trait DashboardVisible
{
    static function getDashboardTitle()
    {
        $classPathParts = explode('\\', get_class());
        $className = $classPathParts[count($classPathParts) - 1];

        $parts = explode('Controller', $className);

        return $parts[0];
    }

    static function getDashboardParent()
    {
        return null;
    }
}
