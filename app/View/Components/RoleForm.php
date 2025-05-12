<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class RoleForm extends Component
{
    public $method;
    public $action;
    public $hasFiles;

    /**
     * Create a new component instance.
     *
     * @param string $method
     * @param string|null $action
     * @param bool $hasFiles
     */
    public function __construct($method = 'POST', $action = null, $hasFiles = false)
    {
        $this->method = strtoupper($method);
        $this->action = $action;
        $this->hasFiles = $hasFiles;
    }

    /**
     * Get the role-based classes for the form container.
     *
     * @return string
     */
    protected function getFormClasses()
    {
        $baseClasses = 'p-6 rounded-lg shadow-sm space-y-4';
        
        $role = Auth::user()->role;
        
        $roleClasses = [
            'admin' => 'bg-purple-50 border border-purple-200',
            'worker' => 'bg-blue-50 border border-blue-200',
            'user' => 'bg-green-50 border border-green-200'
        ];

        return $baseClasses . ' ' . ($roleClasses[$role] ?? $roleClasses['user']);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.role-form', [
            'formClasses' => $this->getFormClasses()
        ]);
    }
}
