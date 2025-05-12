# Union Dashboard Design Guide

## Brand Colors

Our color system is based on the union brand identity:

- Primary (Union Blue): `union-500` (#0ba5ec)
- Neutral Grays: `neutral-*` (50-900)
- Success Green: `success-500` (#22c55e)
- Alert Red: `alert-500` (#ef4444)

## Typography

### Fonts
- Primary: "Cairo" - Used for all UI text
- Alternative: "Tajawal" - Available for Arabic text
- Minimum font size: 14px (for accessibility)

### Type Scale
```
xs: 14px/20px
sm: 16px/24px
base: 18px/28px
lg: 20px/32px
xl: 24px/36px
2xl: 30px/40px
3xl: 36px/48px
```

## Components

### Buttons

```html
<!-- Primary Button -->
<button class="btn-primary">
    Primary Action
</button>

<!-- Secondary Button -->
<button class="btn-secondary">
    Secondary Action
</button>

<!-- Success Button -->
<button class="btn-success">
    Confirm Action
</button>

<!-- Danger Button -->
<button class="btn-danger">
    Delete Action
</button>
```

### Forms

```html
<!-- Text Input -->
<input type="text" class="form-input" />

<!-- Select -->
<select class="form-select">
    <option>Option 1</option>
</select>

<!-- Checkbox -->
<input type="checkbox" class="form-checkbox" />

<!-- Radio -->
<input type="radio" class="form-radio" />
```

### Cards

```html
<div class="card">
    <div class="card-header">
        <h3>Card Title</h3>
    </div>
    <div class="card-body">
        Content goes here
    </div>
    <div class="card-footer">
        Footer content
    </div>
</div>
```

### Tables

```html
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Header</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Cell</td>
            </tr>
        </tbody>
    </table>
</div>
```

### Navigation

```html
<a href="#" class="nav-link">
    Regular Link
</a>

<a href="#" class="nav-link active">
    Active Link
</a>
```

### Badges

```html
<span class="badge-blue">Status</span>
<span class="badge-green">Success</span>
<span class="badge-red">Alert</span>
```

### Alerts

```html
<div class="alert-info">
    Information message
</div>

<div class="alert-success">
    Success message
</div>

<div class="alert-error">
    Error message
</div>
```

## Dark Mode

Dark mode is supported via the `dark:` prefix for all components. Enable dark mode by adding the `dark` class to the `html` tag:

```html
<html class="dark">
```

## Accessibility

- All interactive elements are keyboard accessible
- Color contrast ratios meet WCAG 2.1 Level AA standards
- Focus states are clearly visible
- Minimum text size of 14px
- ARIA attributes are used where necessary

## Responsive Design

All components are responsive by default and follow a mobile-first approach:

- Mobile: < 640px
- Tablet: 640px - 1024px
- Desktop: > 1024px

## Icons

We use [Lucide](https://lucide.dev/) icons. Example usage:

```html
<svg class="h-5 w-5">
    <use href="#icon-name" />
</svg>
```

## Best Practices

1. Use semantic HTML elements
2. Maintain consistent spacing using the provided utility classes
3. Follow BEM naming convention for custom CSS
4. Use provided color variables instead of custom values
5. Ensure all interactive elements have hover and focus states
6. Test components in both light and dark modes
7. Verify mobile responsiveness
8. Check accessibility with screen readers

## Integration with Laravel

1. Components are available as Blade components
2. Use the provided traits for role-based styling
3. Dark mode preference is stored in local storage
4. All styles are compiled using Laravel Mix/Vite

## JavaScript Dependencies

- Alpine.js for interactive components
- No additional JavaScript frameworks required
- Dark mode toggle functionality included

## File Structure

```
resources/
├── css/
│   └── app.css
├── js/
│   └── app.js
└── views/
    ├── components/
    │   └── [component-files]
    └── layouts/
        └── [layout-files]
```

## Updates and Maintenance

1. All color changes should be made in `tailwind.config.js`
2. Component updates should be documented here
3. Test all changes in both light and dark modes
4. Verify accessibility after any changes
5. Update this guide when adding new components
