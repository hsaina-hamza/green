<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Report a Waste Site') }}
        </h2>
        <p class="text-sm text-gray-600">Help us identify and clean up waste sites in our community</p>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <nav class="flex space-x-8 border-b border-gray-200 mb-6" aria-label="Tabs">
                <a href="#" class="border-b-2 border-green-600 text-green-600 pb-2 font-semibold" aria-current="page">Basic Information</a>
                <a href="#" class="text-gray-500 hover:text-gray-700 pb-2 font-semibold">Location</a>
                <a href="#" class="text-gray-500 hover:text-gray-700 pb-2 font-semibold">Photos & Submit</a>
            </nav>

            <form action="{{ route('waste-reports.store') }}" method="POST" enctype="multipart/form-data" id="multiStepForm">
                @csrf

                <!-- Step 1: Basic Information -->
                <section id="step1">
                    <h3 class="text-lg font-semibold mb-4">Describe the Waste Site</h3>

                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="title" placeholder="E.g., Illegal dumping near river" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4" placeholder="Describe the waste site in detail. What type of waste is it? How much is there? Is it affecting the environment?" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required></textarea>
                    </div>

                    <div class="mb-6">
                        <span class="block text-sm font-medium text-gray-700 mb-2">Waste Type</span>
                        <div class="grid grid-cols-3 gap-4">
                            <label class="cursor-pointer flex items-center p-3 border rounded hover:border-green-600">
                                <input type="radio" name="type" value="general" class="mr-3" required>
                                <div>
                                    <div class="font-semibold">Household Waste</div>
                                    <div class="text-xs text-gray-500">General household trash, furniture, appliances</div>
                                </div>
                            </label>
                            <label class="cursor-pointer flex items-center p-3 border rounded hover:border-green-600">
                                <input type="radio" name="type" value="construction" class="mr-3" required>
                                <div>
                                    <div class="font-semibold">Construction Debris</div>
                                    <div class="text-xs text-gray-500">Building materials, concrete, wood, metal scraps</div>
                                </div>
                            </label>
                            <label class="cursor-pointer flex items-center p-3 border rounded hover:border-green-600">
                                <input type="radio" name="type" value="electronic" class="mr-3" required>
                                <div>
                                    <div class="font-semibold">Electronic Waste</div>
                                    <div class="text-xs text-gray-500">Computers, phones, TVs, batteries, cables</div>
                                </div>
                            </label>
                            <label class="cursor-pointer flex items-center p-3 border rounded hover:border-green-600">
                                <input type="radio" name="type" value="hazardous" class="mr-3" required>
                                <div>
                                    <div class="font-semibold">Hazardous Materials</div>
                                    <div class="text-xs text-gray-500">Chemicals, paints, oils, medical waste</div>
                                </div>
                            </label>
                            <label class="cursor-pointer flex items-center p-3 border rounded hover:border-green-600">
                                <input type="radio" name="type" value="plastic" class="mr-3" required>
                                <div>
                                    <div class="font-semibold">Plastic Pollution</div>
                                    <div class="text-xs text-gray-500">Plastic bags, bottles, packaging materials</div>
                                </div>
                            </label>
                            <label class="cursor-pointer flex items-center p-3 border rounded hover:border-green-600">
                                <input type="radio" name="type" value="organic" class="mr-3" required>
                                <div>
                                    <div class="font-semibold">Organic Waste</div>
                                    <div class="text-xs text-gray-500">Food waste, yard trimmings, agricultural waste</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" id="toStep2" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Next: Location</button>
                    </div>
                </section>

                <!-- Step 2: Location -->
                <section id="step2" class="hidden">
                    <h3 class="text-lg font-semibold mb-4">Pinpoint the Location</h3>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Location on Map</label>
                        <div id="map" class="w-full h-64 bg-blue-100 rounded border border-gray-300 flex items-center justify-center text-gray-500">
                            Click on the map to select a location<br>
                            <small>You can drag the marker to adjust the position</small>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="location_details" class="block text-sm font-medium text-gray-700">Location Name</label>
                        <input type="text" name="location_details" id="location_details" placeholder="E.g., Behind Main Street Park, Near the river" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div class="flex justify-between">
                        <button type="button" id="backToStep1" class="border border-gray-300 px-4 py-2 rounded hover:bg-gray-100">Back</button>
                        <button type="button" id="toStep3" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Next: Photos & Submit</button>
                    </div>
                </section>

                <!-- Step 3: Photos & Submit -->
                <section id="step3" class="hidden">
                    <h3 class="text-lg font-semibold mb-4">Upload Photos</h3>

                    <div class="mb-4">
                        <label for="image" class="block text-sm font-medium text-gray-700">Upload Photos</label>
                        <input type="file" name="image" id="image" accept="image/png, image/jpeg, image/gif" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" multiple>
                        <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 10MB</p>
                    </div>

                    <div class="mb-4 p-4 bg-yellow-100 border border-yellow-300 rounded text-yellow-700">
                        By submitting this report, you confirm that all information provided is accurate to the best of your knowledge.
                    </div>

                    <div class="flex justify-between">
                        <button type="button" id="backToStep2" class="border border-gray-300 px-4 py-2 rounded hover:bg-gray-100">Back</button>
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Submit Report</button>
                    </div>
                </section>
            </form>
        </div>
    </div>

    <script>
        // Simple multi-step form navigation
        document.getElementById('toStep2').addEventListener('click', function() {
            document.getElementById('step1').classList.add('hidden');
            document.getElementById('step2').classList.remove('hidden');
        });
        document.getElementById('backToStep1').addEventListener('click', function() {
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step1').classList.remove('hidden');
        });
        document.getElementById('toStep3').addEventListener('click', function() {
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step3').classList.remove('hidden');
        });
        document.getElementById('backToStep2').addEventListener('click', function() {
            document.getElementById('step3').classList.add('hidden');
            document.getElementById('step2').classList.remove('hidden');
        });
    </script>
</x-app-layout>
