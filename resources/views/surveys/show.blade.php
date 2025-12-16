<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $survey->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Description</h3>
                        <p class="text-gray-700">{{ $survey->description }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <h4 class="font-semibold">Start Date</h4>
                            <p class="text-gray-700">{{ $survey->start_date }}</p>
                        </div>
                        <div>
                            <h4 class="font-semibold">End Date</h4>
                            <p class="text-gray-700">{{ $survey->end_date }}</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="font-semibold">Anonymous Survey</h4>
                        <p class="text-gray-700">{{ $survey->is_anonymous ? 'Yes' : 'No' }}</p>
                    </div>

                    <div class="flex space-x-2 mt-6">
                        <a href="{{ route('surveys.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:outline-none transition ease-in-out duration-150">
                            Back to List
                        </a>

                        @can('update', $survey)
                            <a href="{{ route('surveys.edit', $survey) }}"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none transition ease-in-out duration-150">
                                Edit
                            </a>
                        @endcan

                        @can('delete', $survey)
                            <form action="{{ route('surveys.destroy', $survey) }}" method="POST"
                                onsubmit="return confirm('Are you sure?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none transition ease-in-out duration-150">
                                    Delete
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>