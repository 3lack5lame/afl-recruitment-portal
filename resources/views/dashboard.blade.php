<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Armed Forces of Liberia - Recruitment Portal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">Welcome to the AFL Recruitment Portal</h3>
                    
                    @if(auth()->user()->applications && auth()->user()->applications->count() > 0)
                        <div class="bg-blue-50 border border-blue-200 p-4 rounded mb-4">
                            <h4 class="font-bold">Your Application Status:</h4>
                            <p>{{ auth()->user()->applications->last()->status }}</p>
                        </div>
                        <a href="{{ route('apply') }}" 
                           class="inline-block bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                            View/Update Application
                        </a>
                    @else
                        <p class="mb-4">Start your journey to serve the nation. Apply now to join the Armed Forces of Liberia.</p>
                        <a href="{{ route('apply') }}" 
                           class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg text-lg font-bold hover:bg-blue-700">
                            Start Application →
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>