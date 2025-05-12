<x-role-container>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Role-Based Components Example</h2>
    </x-slot>

    <x-role-form method="POST" action="{{ route('example.store') }}" has-files>
        <div class="space-y-6">
            <!-- Text Input -->
            <x-role-input
                type="text"
                name="title"
                label="Title"
                required
                placeholder="Enter title"
            />

            <!-- Select Input -->
            <x-role-select
                name="category"
                label="Category"
                required
                placeholder="Select a category"
            >
                <option value="1">Category 1</option>
                <option value="2">Category 2</option>
                <option value="3">Category 3</option>
            </x-role-select>

            <!-- File Input -->
            <x-role-input
                type="file"
                name="image"
                label="Image"
                accept="image/*"
            />

            <!-- Text Area -->
            <div>
                <label class="block font-medium text-sm text-gray-700">Description</label>
                <textarea
                    name="description"
                    rows="3"
                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                ></textarea>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3">
                <x-role-button type="button">
                    Cancel
                </x-role-button>

                <x-role-button type="submit">
                    Save
                </x-role-button>
            </div>
        </div>
    </x-role-form>

    <!-- Role-Specific Content -->
    @admin
        <div class="mt-6">
            <x-role-container type="border">
                <h3 class="text-lg font-medium">Admin Only Section</h3>
                <p class="mt-2">This content is only visible to administrators.</p>
            </x-role-container>
        </div>
    @endadmin

    @worker
        <div class="mt-6">
            <x-role-container type="border">
                <h3 class="text-lg font-medium">Worker Only Section</h3>
                <p class="mt-2">This content is only visible to workers.</p>
            </x-role-container>
        </div>
    @endworker

    @user
        <div class="mt-6">
            <x-role-container type="border">
                <h3 class="text-lg font-medium">User Only Section</h3>
                <p class="mt-2">This content is only visible to regular users.</p>
            </x-role-container>
        </div>
    @enduser
</x-role-container>
