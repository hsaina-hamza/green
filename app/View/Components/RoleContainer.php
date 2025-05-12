<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class RoleContainer extends Component
{
    public $type;
    public $padded;
    public $rounded;
    public $shadowed;

    /**
     * Create a new component instance.
     *
     * @param string $type Type of container (bg, border, or both)
     * @param bool $padded Whether to add padding
     * @param bool $rounded Whether to add rounded corners
     * @param bool $shadowed Whether to add shadow
     */
    public function __construct($type = 'both', $padded = true, $rounded = true, $shadowed = true)
    {
        $this->type = $type;
        $this->padded = $padded;
        $this->rounded = $rounded;
        $this->shadowed = $shadowed;
    }

    /**
     * Get the role-based classes for the container.
     *
     * @return string
     */
    protected function getContainerClasses()
    {
        $classes = [];
        
        // Base classes
        if ($this->padded) {
            $classes[] = 'p-6';
        }
        if ($this->rounded) {
            $classes[] = 'rounded-lg';
        }
        if ($this->shadowed) {
            $classes[] = 'shadow-sm';
        }

        $role = Auth::user()->role;
        
        // Role-specific classes
        $roleClasses = [
            'admin' => [
                'bg' => 'bg-purple-50',
                'border' => 'border border-purple-200',
                'both' => 'bg-purple-50 border border-purple-200'
            ],
            'worker' => [
                'bg' => 'bg-blue-50',
                'border' => 'border border-blue-200',
                'both' => 'bg-blue-50 border border-blue-200'
            ],
            'user' => [
                'bg' => 'bg-green-50',
                'border' => 'border border-green-200',
                'both' => 'bg-green-50 border border-green-200'
            ]
        ];

        $roleClass = $roleClasses[$role][$this->type] ?? $roleClasses['user'][$this->type];
        $classes[] = $roleClass;

        return implode(' ', $classes);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.role-container', [
            'containerClasses' => $this->getContainerClasses()
        ]);
    }
}
