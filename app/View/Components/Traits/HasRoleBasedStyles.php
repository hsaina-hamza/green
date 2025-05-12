<?php

namespace App\View\Components\Traits;

use Illuminate\Support\Facades\Auth;

trait HasRoleBasedStyles
{
    protected function getRoleColors()
    {
        return [
            'admin' => [
                'bg' => 'bg-purple-50',
                'border' => 'border-purple-200',
                'text' => 'text-purple-700',
                'button' => 'bg-purple-500 hover:bg-purple-600',
                'focus' => 'focus:border-purple-500 focus:ring-purple-500',
                'input-border' => 'border-purple-300',
                'file' => 'file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100'
            ],
            'worker' => [
                'bg' => 'bg-blue-50',
                'border' => 'border-blue-200',
                'text' => 'text-blue-700',
                'button' => 'bg-blue-500 hover:bg-blue-600',
                'focus' => 'focus:border-blue-500 focus:ring-blue-500',
                'input-border' => 'border-blue-300',
                'file' => 'file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100'
            ],
            'user' => [
                'bg' => 'bg-green-50',
                'border' => 'border-green-200',
                'text' => 'text-green-700',
                'button' => 'bg-green-500 hover:bg-green-600',
                'focus' => 'focus:border-green-500 focus:ring-green-500',
                'input-border' => 'border-green-300',
                'file' => 'file:bg-green-50 file:text-green-700 hover:file:bg-green-100'
            ]
        ];
    }

    protected function getRoleStyle($type)
    {
        $role = Auth::user()->role;
        $colors = $this->getRoleColors();
        return $colors[$role][$type] ?? '';
    }

    protected function getRoleClasses($classes)
    {
        $role = Auth::user()->role;
        return $classes[$role] ?? $classes['default'] ?? '';
    }

    protected function isRole($role)
    {
        return Auth::check() && Auth::user()->role === $role;
    }
}
