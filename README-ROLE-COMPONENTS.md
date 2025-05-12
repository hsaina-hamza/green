# Role-Based Components

A collection of Laravel Blade components that adapt their styling based on the user's role (admin, worker, or user).

## Available Components

### Role Container
A container component that provides role-specific styling:

```php
<x-role-container>
    <h1>Content goes here</h1>
</x-role-container>

<!-- With options -->
<x-role-container type="border" padded="true" rounded="true" shadowed="true">
    <h1>Content goes here</h1>
</x-role-container>
```

### Role Button
A button component with role-specific styling:

```php
<x-role-button>
    Click Me
</x-role-button>

<!-- As a link -->
<x-role-button href="/some-url">
    Click Me
</x-role-button>

<!-- With type -->
<x-role-button type="submit">
    Submit
</x-role-button>
```

### Role Input
An input component with role-specific styling:

```php
<x-role-input
    type="text"
    name="title"
    label="Title"
    required
    placeholder="Enter title"
/>

<!-- File input -->
<x-role-input
    type="file"
    name="image"
    label="Image"
    accept="image/*"
/>
```

### Role Select
A select component with role-specific styling:

```php
<x-role-select
    name="category"
    label="Category"
    required
    placeholder="Select a category"
>
    <option value="1">Option 1</option>
    <option value="2">Option 2</option>
</x-role-select>
```

### Role Form
A form component that handles method spoofing and CSRF protection:

```php
<x-role-form method="POST" action="/store" has-files>
    <!-- Form content -->
    <x-role-input name="title" label="Title" />
    
    <!-- Form actions -->
    <div class="flex justify-end space-x-3">
        <x-role-button type="button">Cancel</x-role-button>
        <x-role-button type="submit">Save</x-role-button>
    </div>
</x-role-form>
```

## Role-Based Directives

The components come with Blade directives for role-specific content:

```php
@admin
    <div>Admin only content</div>
@endadmin

@worker
    <div>Worker only content</div>
@endworker

@user
    <div>User only content</div>
@enduser
```

## Color Schemes

Each role has its own color scheme:

- Admin: Purple theme (purple-500, purple-600, etc.)
- Worker: Blue theme (blue-500, blue-600, etc.)
- User: Green theme (green-500, green-600, etc.)

## Installation

1. Copy the component files to your Laravel project:
   - `app/View/Components/Traits/HasRoleBasedStyles.php`
   - `app/View/Components/RoleButton.php`
   - `app/View/Components/RoleInput.php`
   - `app/View/Components/RoleSelect.php`
   - `app/View/Components/RoleForm.php`
   - `app/View/Components/RoleContainer.php`

2. Copy the component views to your Laravel project:
   - `resources/views/components/role-button.blade.php`
   - `resources/views/components/role-input.blade.php`
   - `resources/views/components/role-select.blade.php`
   - `resources/views/components/role-form.blade.php`
   - `resources/views/components/role-container.blade.php`

3. Register the components in your `AppServiceProvider.php`:

```php
use Illuminate\Support\Facades\Blade;

public function boot()
{
    Blade::component('role-button', \App\View\Components\RoleButton::class);
    Blade::component('role-input', \App\View\Components\RoleInput::class);
    Blade::component('role-select', \App\View\Components\RoleSelect::class);
    Blade::component('role-form', \App\View\Components\RoleForm::class);
    Blade::component('role-container', \App\View\Components\RoleContainer::class);

    Blade::if('admin', function () {
        return auth()->check() && auth()->user()->role === 'admin';
    });

    Blade::if('worker', function () {
        return auth()->check() && auth()->user()->role === 'worker';
    });

    Blade::if('user', function () {
        return auth()->check() && auth()->user()->role === 'user';
    });
}
```

## Example Usage

See `resources/views/examples/role-components.blade.php` for a complete example of how to use these components in your views.

The example includes:
- Form with various input types
- Role-specific content sections
- Proper form handling
- File upload support
- Validation messages

## Notes

- Components automatically detect the user's role and apply appropriate styling
- All components support standard HTML attributes through attribute forwarding
- Form components handle CSRF protection and method spoofing automatically
- Input components include built-in error message display
- All components are fully responsive and follow Tailwind CSS conventions
