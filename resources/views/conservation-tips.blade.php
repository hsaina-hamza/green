<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Conservation Tips') }}
        </h2>
    </x-slot>

   <div class="mt-10 bg-blue-50 p-6 rounded-lg border border-blue-200">
                <h2 class="text-xl font-bold mb-4 text-blue-700">Scheduling Tips</h2>
                <p class="mb-4 text-lg leading-relaxed text-gray-700">Here are some best practices for managing truck schedules efficiently:</p>
                <ul class="list-disc list-inside space-y-2 text-gray-800">
                    <li><span class="font-semibold">Plan ahead</span> to optimize routes and reduce fuel consumption.</li>
                    <li><span class="font-semibold">Schedule during off-peak hours</span> to avoid traffic congestion when possible.</li>
                    <li><span class="font-semibold">Maintain consistent weekly schedules</span> to improve operational efficiency.</li>
                    <li><span class="font-semibold">Allow buffer time</span> between deliveries to account for unexpected delays.</li>
                    <li><span class="font-semibold">Consider location proximity</span> when scheduling multiple deliveries on the same day.</li>
                    <li><span class="font-semibold">Regularly review and optimize</span> schedules based on historical performance data.</li>
                </ul>
            </div>
</x-app-layout>
