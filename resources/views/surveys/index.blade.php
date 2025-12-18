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
                                <div class="block p-6 bg-white mt-3 border border-gray-200 rounded-lg shadow hover:bg-gray-100 relative">
                                    <a href="{{ route('surveys.show', $survey) }}" class="block before:absolute before:inset-0 before:z-0">
                                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">{{ $survey->title }}</h5>
                                        <p class="font-normal text-gray-700 mb-4">{{ Str::limit($survey->description, 100) }}</p>
                                    </a>
                                    
                        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">

                                        @if($survey->token)
                                            <button 
                                                type="button" 
                                                x-data
                                                @click.stop.prevent="window.copyLink('{{ route('surveys.public_show', ['token' => $survey->token]) }}', $el)"
                                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest relative z-10 hover:bg-gray-500 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                            >
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                                </svg>
                                                Partager
                                            </button>
                                            @endif

                                        @can('update', $survey)
                                            <a href="{{ route('surveys.edit', $survey) }}"
                                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Edit
                                            </a>
                                        @endcan

                                        @can('delete', $survey)
                                            <form action="{{ route('surveys.destroy', $survey) }}" method="POST" onsubmit="return confirm('Are you sure?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest relative z-10 hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
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


<script>
    // On définit la fonction directement ici pour être sûr qu'elle existe
    window.copyLink = function(url, button) {
        
        // Fonction interne pour le feedback visuel (Texte "Copié !")
        const triggerFeedback = () => {
            let originalContent = button.innerHTML;
            // On change le texte et la couleur
            button.innerText = 'Copié !';
            button.classList.remove('bg-gray-800'); // On enlève le gris
            button.classList.add('bg-green-600');   // On met du vert
            
            // On remet tout comme avant après 2 secondes
            setTimeout(() => {
                button.innerHTML = originalContent;
                button.classList.remove('bg-green-600');
                button.classList.add('bg-gray-800');
            }, 2000);
        };

        // Méthode moderne (Clipboard API)
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url)
                .then(triggerFeedback)
                .catch(err => {
                    console.warn('Echec Clipboard API, tentative fallback...', err);
                    // Si ça rate, on tente la vieille méthode
                    fallbackCopy(url, button, triggerFeedback);
                });
        } else {
            // Méthode ancienne (Fallback)
            fallbackCopy(url, button, triggerFeedback);
        }
    };

    // Fonction de secours pour les anciens navigateurs ou contextes non-sécurisés
    function fallbackCopy(url, button, onSuccess) {
        const textArea = document.createElement("textarea");
        textArea.value = url;
        textArea.style.position = "fixed"; // Hors écran
        textArea.style.left = "-9999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            const successful = document.execCommand('copy');
            if (successful && onSuccess) onSuccess();
        } catch (err) {
            console.error('Erreur copie :', err);
            prompt('Copiez ce lien manuellement :', url);
        }
        
        document.body.removeChild(textArea);
    }
</script>