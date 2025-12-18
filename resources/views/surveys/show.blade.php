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

                    {{-- Gestion des messages Flash (Succès/Erreur) --}}
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative">
                            <strong>Oups !</strong> Il y a des erreurs dans votre soumission.
                        </div>
                    @endif

                    {{-- LOGIQUE PHP : Vérifier si l'utilisateur a déjà répondu --}}
                    @php
                        // $userAnswers est passé par le contrôleur (keyBy('survey_question_id'))
                        $hasAnswered = isset($userAnswers) && $userAnswers->count() > 0;
                    @endphp

                    @if($hasAnswered)
                        <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded relative">
                            Merci vous avez déjà répondu à ce sondage !
                        </div>
                    @endif

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

                            {{-- DÉBUT DU FORMULAIRE DE RÉPONSE --}}
                            <form action="{{ route('surveys.storeAnswer', $survey) }}" method="POST">
                                @csrf
                                
                                <div class="space-y-6">
                                    @foreach($survey->questions as $index => $question)

                                        {{-- LOGIQUE PHP : Récupérer la réponse pour CETTE question --}}
                                        @php
                                            $existingVal = null;
                                            $decodedVal = [];

                                            if ($hasAnswered && isset($userAnswers[$question->id])) {
                                                $existingVal = $userAnswers[$question->id]->answer;
                                                
                                                // Si c'est un choix multiple, on décode le JSON
                                                if ($question->question_type === 'multiple_choice') {
                                                    $decodedVal = json_decode($existingVal, true) ?? [];
                                                    if (!is_array($decodedVal)) $decodedVal = [$existingVal];
                                                }
                                            }
                                        @endphp

                                        <div class="bg-white rounded-2xl p-6 mt-3 border border-gray-200 shadow-sm transition-all duration-300 hover:shadow-md hover:border-indigo-300 group">
                                            
                                            <div class="grid grid-cols-[auto_1fr] gap-6">
                                                
                                                {{-- Colonne 1 : Le Numéro --}}
                                                <div>
                                                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-50 text-indigo-600 font-bold text-lg border border-indigo-100 shadow-sm group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 transition-colors duration-300">
                                                        {{ $index + 1 }}
                                                    </span>
                                                </div>

                                                {{-- Colonne 2 : Le Contenu (INPUTS) --}}
                                                <div class="min-w-0">
                                                    
                                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-2">
                                                        <h4 class="text-lg font-bold text-gray-900 leading-snug pt-1">
                                                            {{ $question->title }}
                                                            @if($question->required) <span class="text-red-500">*</span> @endif
                                                        </h4>
                                                        
                                                        <span class="inline-flex self-start sm:self-center shrink-0 items-center px-2.5 py-0.5 rounded-md text-m font-medium 
                                                            @if($question->question_type === 'text') 
                                                            @elseif($question->question_type === 'single_choice') 
                                                            @elseif($question->question_type === 'multiple_choice') 
                                                            @else 
                                                            @endif">Type de question :
                                                            @if($question->question_type === 'text') Texte libre
                                                            @elseif($question->question_type === 'single_choice') Choix unique
                                                            @elseif($question->question_type === 'multiple_choice') Choix multiple
                                                            @elseif($question->question_type === 'scale_1_10') Échelle 1-10
                                                            @endif
                                                        </span>
                                                    </div>

                                                    {{-- INPUT TYPE: TEXTE --}}
                                                    @if($question->question_type === 'text')
                                                        <div class="mt-3">
                                                            <textarea 
                                                                name="answers[{{ $question->id }}]" 
                                                                rows="3" 
                                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('answers.'.$question->id) border-red-500 @enderror disabled:bg-gray-100 disabled:text-gray-500"
                                                                placeholder="Votre réponse..."
                                                                {{ $hasAnswered ? 'disabled' : '' }}
                                                            >{{ $hasAnswered ? $existingVal : old('answers.'.$question->id) }}</textarea>
                                                        </div>
                                                    @endif

                                                    {{-- INPUT TYPE: CHOIX (UNIQUE & MULTIPLE) --}}
                                                    @if(in_array($question->question_type, ['single_choice', 'multiple_choice']) && $question->options)
                                                        <div class="mt-4 p-4 bg-gray-50 rounded-xl border border-gray-100 @error('answers.'.$question->id) border-red-300 bg-red-50 @enderror">
                                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Options disponibles</p>
                                                            
                                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                                @foreach($question->options as $option)
                                                                    @php
                                                                        // Logique pour cocher la case
                                                                        $checked = false;
                                                                        if ($hasAnswered) {
                                                                            if ($question->question_type === 'single_choice') {
                                                                                $checked = ($existingVal == $option);
                                                                            } else {
                                                                                $checked = in_array($option, $decodedVal);
                                                                            }
                                                                        } else {
                                                                            // Logique old() standard
                                                                            if ($question->question_type === 'single_choice') {
                                                                                $checked = (old('answers.'.$question->id) == $option);
                                                                            } else {
                                                                                $checked = (is_array(old('answers.'.$question->id)) && in_array($option, old('answers.'.$question->id)));
                                                                            }
                                                                        }
                                                                    @endphp

                                                                    <label class="flex items-center p-2 rounded hover:bg-white transition-colors cursor-pointer group/option {{ $hasAnswered ? 'opacity-75 cursor-default' : '' }}">
                                                                        @if($question->question_type === 'single_choice')
                                                                            <input 
                                                                                type="radio" 
                                                                                name="answers[{{ $question->id }}]" 
                                                                                value="{{ $option }}" 
                                                                                class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500 mr-3"
                                                                                {{ $checked ? 'checked' : '' }}
                                                                                {{ $hasAnswered ? 'disabled' : '' }}
                                                                            >
                                                                        @else
                                                                            <input 
                                                                                type="checkbox" 
                                                                                name="answers[{{ $question->id }}][]" 
                                                                                value="{{ $option }}" 
                                                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mr-3"
                                                                                {{ $checked ? 'checked' : '' }}
                                                                                {{ $hasAnswered ? 'disabled' : '' }}
                                                                            >
                                                                        @endif
                                                                        <span class="text-sm text-gray-700 group-hover/option:text-gray-900 {{ $hasAnswered && $checked ? 'font-bold text-indigo-600' : '' }}">{{ $option }}</span>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
{{-- INPUT TYPE: ECHELLE 1-10 (GRID FULL WIDTH) --}}
                                                    @if($question->question_type === 'scale_1_10')
                                                        <div class="mt-4 w-full">
                                                            {{-- UTILISATION DE GRID AU LIEU DE FLEX --}}
                                                            {{-- grid-cols-5 : 5 par ligne sur mobile --}}
                                                            {{-- sm:grid-cols-10 : 10 sur une seule ligne sur PC --}}
                                                            <div class="grid grid-cols-5 sm:grid-cols-10 gap-2 w-full">
                                                                @for($i = 1; $i <= 10; $i++)
                                                                    @php
                                                                        $checked = $hasAnswered ? ($existingVal == $i) : (old('answers.'.$question->id) == $i);
                                                                        
                                                                        // Logique de couleur inchangée
                                                                        $isRed = $i <= 5;
                                                                        $activeClass = $isRed ? 'bg-red-600 border-red-600 text-red-500' : 'bg-green-600 border-green-600 text-green-600';
                                                                        $hoverClass = $isRed ? 'hover:border-red-600 text-red-600' : 'hover:border-green-6000 text-green-600';
                                                                        $baseClass = 'bg-white-600 text-gray-600 border-gray-200';
                                                                    @endphp
                                                                    
                                                                    {{-- Le label prend toute la largeur de sa case de grille --}}
                                                                    <label class="{{ $hasAnswered ? 'cursor-default' : 'cursor-pointer' }} w-full block">
                                                                        <input 
                                                                            type="radio" 
                                                                            name="answers[{{ $question->id }}]" 
                                                                            value="{{ $i }}" 
                                                                            class="sr-only peer"
                                                                            {{ $question->required && !$hasAnswered ? 'required' : '' }}
                                                                            {{ $checked ? 'checked' : '' }}
                                                                            {{ $hasAnswered ? 'disabled' : '' }}
                                                                        >
                                                                        {{-- w-full ici force le carré à remplir la grille --}}
                                                                        <div class="w-full h-10 sm:h-12 flex items-center justify-center rounded-lg border text-sm font-bold transition-all duration-200 
                                                                            {{ $checked ? $activeClass . ' shadow-md scale-105' : $baseClass }}
                                                                            {{ !$hasAnswered && !$checked ? $hoverClass . ' hover:bg-gray-50' : '' }}
                                                                            {{ $hasAnswered && !$checked ? 'opacity-50' : '' }}
                                                                        ">
                                                                            {{ $i }}
                                                                        </div>
                                                                    </label>
                                                                @endfor
                                                            </div>
                                                            
                                                            {{-- Légende alignée --}}
                                                            <div class="flex justify-between px-1 mt-2 text-xs font-medium text-gray-400 uppercase tracking-wider">
                                                                <span class="text-red-600">Faible (1-5)</span>
                                                                <span class="text-green-600">Élevé (6-10)</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    {{-- Message d'erreur spécifique à la question --}}
                                                    @error('answers.'.$question->id)
                                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- BOUTON ENVOYER LES RÉPONSES --}}
                                {{-- On cache le bouton si l'utilisateur a déjà répondu --}}
                                @if(!$hasAnswered)
                                    <div class="mt-8 flex justify-end">
                                        <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">                                               
                                            Envoyer mes réponses
                                        </button>
                                    </div>
                                @endif
                                
                            </form>
                            {{-- FIN DU FORMULAIRE DE RÉPONSE --}}

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

                    {{-- ZONE DES BOUTONS D'ADMINISTRATION (Edit/Delete/Back) - CONSERVÉE --}}
                    <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <a href="{{ route('surveys.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to List
                        </a>

                        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                            @can('update', $survey)
                                <a href="{{ route('surveys.edit', $survey) }}" 
                                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">                                               
                                
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
                    {{-- FIN ZONE DES BOUTONS --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>