<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class RoleButton extends Component
{
    public $type;
    public $href;
    public $disabled;

    /**
     * Create a new component instance.
     *
     * @param string $type
     * @param string|null $href
     * @param bool $disabled
     */
    public function __construct($type = 'button', $href = null, $disabled = false)
    {
        $this->type = $type;
        $this->href = $href;
        $this->disabled = $disabled;
    }

    /**
     * Get the role-based classes for the button.
     *
     * @return string
     */
    protected function getButtonClasses()
    {
        $baseClasses = 'inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150';
        
        if ($this->disabled) {
            return $baseClasses . ' opacity-50 cursor-not-allowed';
        }

        $role = Auth::user()->role;
        
        $roleClasses = [
            'admin' => 'bg-purple-500 hover:bg-purple-600 focus:ring-purple-500',
            'worker' => 'bg-blue-500 hover:bg-blue-600 focus:ring-blue-500',
            'user' => 'bg-green-500 hover:bg-green-600 focus:ring-green-500'
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
        return view('components.role-button', [
            'classes' => $this->getButtonClasses()
        ]);
    }
}
