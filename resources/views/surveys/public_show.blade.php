{{-- On étend le layout principal de l'application --}}
@extends('layouts.public')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
            <h1 class="text-2xl font-bold tracking-tight">{{ $survey->title }}</h1>
            <p class="mt-2 text-lg text-gray-700">{{ $survey->description }}</p>

            <hr class="my-6">

            {{-- Ici, vous pouvez boucler sur les questions du sondage --}}
            @if(isset($questions) && $questions->count() > 0)
                <form action="#" method="POST">
                    @csrf
                    @foreach ($questions as $question)
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">{{ $question->text }}</label>
                            {{-- Affichez ici les options de réponse pour chaque question --}}
                        </div>
                    @endforeach
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Soumettre
                    </button>
                </form>
            @else
                <p class="text-gray-500">Ce sondage ne contient pas encore de questions.</p>
            @endif
        </div>
    </div>
</div>
@endsection