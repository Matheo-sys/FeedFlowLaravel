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

                    <div class="mb-8">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Description</h3>
                        <p class="text-gray-900 text-lg leading-relaxed">{{ $survey->description }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase">Start Date</h4>
                            <p class="text-gray-800 font-medium mt-1">{{ $survey->start_date }}</p>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase">End Date</h4>
                            <p class="text-gray-800 font-medium mt-1">{{ $survey->end_date }}</p>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase">Anonymat</h4>
                            <div class="mt-1">
                                @if($survey->is_anonymous)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Anonyme
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Nominatif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($survey->questions && $survey->questions->count() > 0)
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="text-xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                                <span>Questions</span>
                                <span class="bg-gray-100 text-gray-600 text-sm font-bold px-3 py-1 rounded-full border border-gray-200">
                                    {{ $survey->questions->count() }}
                                </span>
                            </h3>

                            <div class="space-y-6">
                                @foreach($survey->questions as $index => $question)
                                    <div class="bg-white rounded-2xl p-6 mt-3 border border-gray-200 shadow-sm transition-all duration-300 hover:shadow-md hover:border-indigo-300 group">
                                        
                                        {{-- 
                                            STRUCTURE GRID (La correction est ici) :
                                            On définit 2 colonnes : 
                                            1. 'auto' (pour le chiffre, s'adapte à sa taille)
                                            2. '1fr' (pour le reste du contenu)
                                            Cela empêche le contenu de venir écraser le chiffre.
                                        --}}
                                        <div class="grid grid-cols-[auto_1fr] gap-6">
                                            
                                            {{-- Colonne 1 : Le Numéro --}}
                                            <div>
                                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-50 text-indigo-600 font-bold text-lg border border-indigo-100 shadow-sm group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 transition-colors duration-300">
                                                    {{ $index + 1 }}
                                                </span>
                                            </div>

                                            {{-- Colonne 2 : Le Contenu --}}
                                            <div class="min-w-0"> {{-- min-w-0 est crucial pour empêcher les débordements de texte --}}
                                                
                                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-2">
                                                    <h4 class="text-lg font-bold text-gray-900 leading-snug pt-1">{{ $question->title }}</h4>
                                                    
                                                    {{-- Badge type de question --}}
                                                    <span class="inline-flex self-start sm:self-center shrink-0 items-center px-2.5 py-0.5 rounded-md text-m border font-medium 
                                                        @if($question->question_type === 'text') bg-blue-50 text-blue-700 ring-blue-700/10
                                                        @elseif($question->question_type === 'single_choice') bg-emerald-50 text-emerald-700 ring-emerald-600/20
                                                        @elseif($question->question_type === 'multiple_choice') bg-violet-50 text-violet-700 ring-violet-700/10
                                                        @else bg-amber-50 text-amber-700 ring-amber-600/20
                                                        @endif">
                                                        @if($question->question_type === 'text') Texte libre
                                                        @elseif($question->question_type === 'single_choice') Choix unique
                                                        @elseif($question->question_type === 'multiple_choice') Choix multiple
                                                        @elseif($question->question_type === 'scale_1_10') Échelle 1-10
                                                        @endif
                                                    </span>
                                                </div>

                                                @if(in_array($question->question_type, ['single_choice', 'multiple_choice']) && $question->options)
                                                    <div class="mt-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Options disponibles</p>
                                                        
                                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                            @foreach($question->options as $option)
                                                                <div class="flex items-center p-2 rounded hover:bg-white transition-colors">
                                                                    @if($question->question_type === 'single_choice')
                                                                        <div class="w-4 h-4 rounded-full border-2 border-black-1000 mr-3 flex-shrink-0"></div>
                                                                    @else
                                                                        <div class="w-4 h-4 rounded border-2 border-black-1000 mr-3 flex-shrink-0"></div>
                                                                    @endif
                                                                    <span class="text-sm text-gray-700">{{ $option }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif

                                                {{-- Affichage Echelle --}}
                                                @if($question->question_type === 'scale_1_10')
                                                    <div class="mt-4 overflow-x-auto pb-2">
                                                        <div class="flex items-center gap-3 min-w-max">
                                                            @for($i = 1; $i <= 10; $i++)
                                                                <div class="flex items-center justify-center w-10 h-10 rounded border border-gray-200 bg-gray-50 text-xs font-bold text-gray-500">
                                                                    {{ $i }}
                                                                </div>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 shadow-sm">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-yellow-800">
                                            Ce sondage ne contient aucune question pour le moment.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <a href="{{ route('surveys.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to List
                        </a>

                        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                            @can('update', $survey)
                                <a href="{{ route('surveys.edit', $survey) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                            @endcan

                            @can('delete', $survey)
                                <form action="{{ route('surveys.destroy', $survey) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce sondage ? Cette action est irréversible.');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>