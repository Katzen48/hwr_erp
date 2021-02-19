<?php
/**
 * User: Katzen48
 * Date: 02.02.2021
 * Time: 16:31
 */

namespace App\Traits;


trait DashboardVisible
{
    static function getDashboardTitle(): string
    {
        $classPathParts = explode('\\', get_class());
        $className = $classPathParts[count($classPathParts) - 1];

        $parts = explode('Controller', $className);

        return $parts[0];
    }

    /**
     * @return \App\Http\Controllers\Controller|null
     */
    static function getDashboardParent()
    {
        return null;
    }

    static function isEditable(): bool
    {
        return true;
    }

    static function getEditFields(): array
    {
        return static::getDashboardFields();
    }

    abstract static function getDashboardId(): string;
    abstract static function getDashboardFields(): array;
}
