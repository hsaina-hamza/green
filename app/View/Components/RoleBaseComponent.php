<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\View\Components\Traits\HasRoleBasedStyles;

abstract class RoleBaseComponent extends Component
{
    use HasRoleBasedStyles;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    abstract public function render();

    /**
     * Get the role-based classes for the component.
     *
     * @param string $type
     * @return string
     */
    protected function getClasses($type)
    {
        return $this->getRoleStyle($type);
    }

    /**
     * Get the role-based background class.
     *
     * @return string
     */
    protected function getBackgroundClass()
    {
        return $this->getClasses('bg');
    }

    /**
     * Get the role-based border class.
     *
     * @return string
     */
    protected function getBorderClass()
    {
        return $this->getClasses('border');
    }

    /**
     * Get the role-based text class.
     *
     * @return string
     */
    protected function getTextClass()
    {
        return $this->getClasses('text');
    }

    /**
     * Get the role-based button class.
     *
     * @return string
     */
    protected function getButtonClass()
    {
        return $this->getClasses('button');
    }

    /**
     * Get the role-based focus class.
     *
     * @return string
     */
    protected function getFocusClass()
    {
        return $this->getClasses('focus');
    }

    /**
     * Get the role-based input border class.
     *
     * @return string
     */
    protected function getInputBorderClass()
    {
        return $this->getClasses('input-border');
    }

    /**
     * Get the role-based file input class.
     *
     * @return string
     */
    protected function getFileClass()
    {
        return $this->getClasses('file');
    }

    /**
     * Check if the current user has a specific role.
     *
     * @param string $role
     * @return bool
     */
    protected function hasRole($role)
    {
        return $this->isRole($role);
    }

    /**
     * Get the current user's role.
     *
     * @return string
     */
    protected function getCurrentRole()
    {
        return auth()->user()->role;
    }
}
