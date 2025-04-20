<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Conservation Tips') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
            <h1 class="text-3xl font-extrabold mb-6 text-green-700 border-b-4 border-green-300 pb-2">Conservation Tips</h1>
            <p class="mb-6 text-lg leading-relaxed text-gray-700">Here are some practical tips to help conserve the environment and reduce waste in your daily life:</p>
            <ul class="list-disc list-inside space-y-3 text-gray-800 text-lg">
                <li><span class="font-semibold">Reduce, reuse, and recycle</span> whenever possible to minimize waste.</li>
                <li><span class="font-semibold">Compost organic waste</span> to reduce landfill use and enrich soil.</li>
                <li><span class="font-semibold">Use energy-efficient appliances and lighting</span> to save energy and reduce carbon footprint.</li>
                <li><span class="font-semibold">Conserve water</span> by fixing leaks and using water-saving fixtures.</li>
                <li><span class="font-semibold">Participate in local clean-up events</span> and community recycling programs.</li>
                <li><span class="font-semibold">Educate others</span> about the importance of environmental conservation.</li>
            </ul>
        </div>
    </div>
</x-app-layout>
