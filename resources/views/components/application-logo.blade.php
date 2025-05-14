<svg 
    viewBox="0 0 24 24" 
    fill="none" 
    xmlns="http://www.w3.org/2000/svg" 
    aria-hidden="true"
    focusable="false"
    {{ $attributes }}
>
    <title>List with Check Icon</title>
    <desc>A document list icon with a green checkmark indicator</desc>
    
    <!-- Document outline -->
    <path 
        d="M4 6C4 4.89543 4.89543 4 6 4H18C19.1046 4 20 4.89543 20 6V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V6Z" 
        stroke="currentColor" 
        stroke-width="2"
        stroke-linejoin="round"
    />
    
    <!-- List lines -->
    <path 
        d="M9 8L15 8M9 12L15 12M9 16L13 16" 
        stroke="currentColor" 
        stroke-width="2" 
        stroke-linecap="round"
    />
    
    <!-- Green checkmark circle -->
    <circle 
        cx="17" 
        cy="17" 
        r="5" 
        fill="#10B981"
    />
    
    <!-- Checkmark -->
    <path 
        d="M14.5 17H19.5M17 14.5V19.5" 
        stroke="white" 
        stroke-width="2" 
        stroke-linecap="round"
    />
</svg>