<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class RoleInput extends Component
{
    public $type;
    public $label;
    public $disabled;
    public $required;

    /**
     * Create a new component instance.
     *
     * @param string $type
     * @param string|null $label
     * @param bool $disabled
     * @param bool $required
     */
    public function __construct($type = 'text', $label = null, $disabled = false, $required = false)
    {
        $this->type = $type;
        $this->label = $label;
        $this->disabled = $disabled;
        $this->required = $required;
    }

    /**
     * Get the role-based classes for the input.
     *
     * @return string
     */
    protected function getInputClasses()
    {
        $baseClasses = 'mt-1 block w-full rounded-md shadow-sm';
        
        if ($this->disabled) {
            return $baseClasses . ' opacity-50 cursor-not-allowed';
        }

        $role = Auth::user()->role;
        
        $roleClasses = [
            'admin' => 'border-purple-300 focus:border-purple-500 focus:ring-purple-500',
            'worker' => 'border-blue-300 focus:border-blue-500 focus:ring-blue-500',
            'user' => 'border-green-300 focus:border-green-500 focus:ring-green-500'
        ];

        return $baseClasses . ' ' . ($roleClasses[$role] ?? $roleClasses['user']);
    }

    /**
     * Get the role-based classes for the label.
     *
     * @return string
     */
    protected function getLabelClasses()
    {
        $baseClasses = 'block font-medium text-sm';
        
        $role = Auth::user()->role;
        
        $roleClasses = [
            'admin' => 'text-purple-700',
            'worker' => 'text-blue-700',
            'user' => 'text-green-700'
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
        return view('components.role-input', [
            'inputClasses' => $this->getInputClasses(),
            'labelClasses' => $this->getLabelClasses()
        ]);
    }
}
