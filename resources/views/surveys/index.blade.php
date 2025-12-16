<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Surveys') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">All Surveys</h3>
                        <a href="{{ route('surveys.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Create New Survey
                        </a>
                    </div>

                    @if($surveys->isEmpty())
                        <p class="text-gray-500">No surveys found.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($surveys as $survey)
                                <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 relative">
                                    <a href="{{ route('surveys.show', $survey) }}" class="block">
                                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">{{ $survey->title }}</h5>
                                        <p class="font-normal text-gray-700 mb-4">{{ Str::limit($survey->description, 100) }}</p>
                                    </a>
                                    
                                    <div class="flex space-x-2 mt-4">
                                        @can('update', $survey)
                                            <a href="{{ route('surveys.edit', $survey) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                                        @endcan

                                        @can('delete', $survey)
                                            <form action="{{ route('surveys.destroy', $survey) }}" method="POST" onsubmit="return confirm('Are you sure?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium ml-2">Delete</button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
