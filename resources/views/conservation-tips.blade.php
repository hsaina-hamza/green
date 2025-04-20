<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Conservation Tips') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-3xl font-bold text-green-700 mb-8">🌿 Conservation Tips for a Greener Planet</h1>

                <div class="space-y-8">
                    <section>
                        <h3 class="text-xl font-semibold text-green-600 mb-4">💧 Save Water</h3>
                        <ul class="list-disc list-inside space-y-2 text-gray-700">
                            <li>Turn off taps while brushing your teeth</li>
                            <li>Fix leaks immediately — one drip per second can waste over 3,000 gallons per year!</li>
                            <li>Use a broom instead of a hose to clean driveways and sidewalks</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="text-xl font-semibold text-green-600 mb-4">🔌 Reduce Energy Use</h3>
                        <ul class="list-disc list-inside space-y-2 text-gray-700">
                            <li>Switch to LED light bulbs</li>
                            <li>Unplug chargers and appliances when not in use</li>
                            <li>Use natural light during the day whenever possible</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="text-xl font-semibold text-green-600 mb-4">♻️ Reduce, Reuse, Recycle</h3>
                        <ul class="list-disc list-inside space-y-2 text-gray-700">
                            <li>Avoid single-use plastics; go for reusable bags, bottles, and containers</li>
                            <li>Donate or repurpose old clothes and electronics</li>
                            <li>Sort recyclables properly and follow your local recycling rules</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="text-xl font-semibold text-green-600 mb-4">🌳 Protect Nature</h3>
                        <ul class="list-disc list-inside space-y-2 text-gray-700">
                            <li>Plant trees or native plants in your yard</li>
                            <li>Support local conservation efforts and wildlife organizations</li>
                            <li>Stay on trails when hiking to protect fragile ecosystems</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="text-xl font-semibold text-green-600 mb-4">🚲 Green Transportation</h3>
                        <ul class="list-disc list-inside space-y-2 text-gray-700">
                            <li>Walk, bike, or carpool when you can</li>
                            <li>Use public transportation to reduce carbon emissions</li>
                            <li>Maintain your vehicle for better fuel efficiency</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="text-xl font-semibold text-green-600 mb-4">🛒 Eco-Friendly Shopping</h3>
                        <ul class="list-disc list-inside space-y-2 text-gray-700">
                            <li>Buy in bulk to reduce packaging waste</li>
                            <li>Support eco-conscious brands and local farmers</li>
                            <li>Choose products with minimal or biodegradable packaging</li>
                        </ul>
                    </section>
                </div>

                <div class="mt-8 p-4 bg-green-50 rounded-lg">
                    <p class="text-green-800 text-center italic">
                        "The greatest threat to our planet is the belief that someone else will save it." - Robert Swan
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
