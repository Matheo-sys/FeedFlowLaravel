<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Survey') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="font-medium text-red-600">{{ __('Whoops! Something went wrong.') }}</div>
                            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div x-data="surveyForm()">
                        <form @submit.prevent="submitSurvey">
                            @csrf

                            <!-- Survey Information -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-4">Survey Information</h3>

                                <!-- Title -->
                                <div class="mb-4">
                                    <x-input-label for="title" :value="__('Title')" />
                                    <x-text-input id="title" class="block mt-1 w-full" type="text"
                                        x-model="survey.title" required autofocus />
                                </div>

                                <!-- Description -->
                                <div class="mb-4">
                                    <x-input-label for="description" :value="__('Description')" />
                                    <textarea id="description" x-model="survey.description"
                                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        rows="4" required></textarea>
                                </div>

                                <!-- Dates -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <x-input-label for="start_date" :value="__('Start Date')" />
                                        <x-text-input id="start_date" class="block mt-1 w-full" type="datetime-local"
                                            x-model="survey.start_date" required />
                                    </div>
                                    <div>
                                        <x-input-label for="end_date" :value="__('End Date')" />
                                        <x-text-input id="end_date" class="block mt-1 w-full" type="datetime-local"
                                            x-model="survey.end_date" required />
                                    </div>
                                </div>

                                <!-- Anonymous -->
                                <div class="block mt-4">
                                    <label for="is_anonymous" class="inline-flex items-center">
                                        <input id="is_anonymous" type="checkbox"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                            x-model="survey.is_anonymous">
                                        <span class="ml-2 text-sm text-gray-600">{{ __('Anonymous Survey') }}</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Questions Section -->
                            <div class="mb-6 border-t pt-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-semibold">Questions</h3>
                                    <button type="button" @click="addQuestion"
                                        style="background-color: #16a34a; color: white;"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        + Add Question
                                    </button>
                                </div>

                                <!-- List of Questions -->
                                <div class="space-y-4">
                                    <template x-for="(question, qIndex) in questions" :key="qIndex">
                                        <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                                            <div class="flex justify-between items-start mb-3">
                                                <h4 class="font-semibold text-gray-700"
                                                    x-text="'Question ' + (qIndex + 1)"></h4>
                                                <button type="button" @click="removeQuestion(qIndex)"
                                                    class="text-red-600 hover:text-red-800 text-sm font-semibold">
                                                    Remove
                                                </button>
                                            </div>

                                            <!-- Question Title -->
                                            <div class="mb-3">
                                                <x-input-label :value="__('Question Title')" />
                                                <x-text-input class="block mt-1 w-full" type="text"
                                                    x-model="question.title" required />
                                            </div>

                                            <!-- Question Type -->
                                            <div class="mb-3">
                                                <x-input-label :value="__('Question Type')" />
                                                <select x-model="question.question_type"
                                                    @change="updateQuestionOptions(qIndex)"
                                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                                    <option value="text">Text</option>
                                                    <option value="single_choice">Single Choice</option>
                                                    <option value="multiple_choice">Multiple Choice</option>
                                                    <option value="scale_1_10">Scale 1-10</option>
                                                </select>
                                            </div>

                                            <!-- Options (for single/multiple choice) -->
                                            <div x-show="question.question_type === 'single_choice' || question.question_type === 'multiple_choice'"
                                                x-transition class="mb-3">
                                                <x-input-label :value="__('Options')" />
                                                <div class="mt-2 space-y-2">
                                                    <template x-for="(option, oIndex) in question.options"
                                                        :key="oIndex">
                                                        <div class="flex gap-2">
                                                            <input type="text" x-model="question.options[oIndex]"
                                                                class="flex-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                                                placeholder="Option text" required>
                                                            <button type="button" @click="removeOption(qIndex, oIndex)"
                                                                class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                                                                ×
                                                            </button>
                                                        </div>
                                                    </template>
                                                    <button type="button" @click="addOption(qIndex)"
                                                        class="mt-2 px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm">
                                                        + Add Option
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Empty State -->
                                    <div x-show="questions.length === 0" class="text-center py-8 text-gray-500">
                                        No questions added yet. Click "Add Question" to get started.
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-center justify-end mt-6">
                                <a href="{{ route('surveys.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                                    Cancel
                                </a>
                                <x-primary-button type="submit">
                                    {{ __('Create Survey') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function surveyForm() {
            return {
                survey: {
                    title: '',
                    description: '',
                    start_date: '',
                    end_date: '',
                    is_anonymous: false
                },
                questions: [],

                addQuestion() {
                    this.questions.push({
                        title: '',
                        question_type: 'text',
                        options: []
                    });
                },

                removeQuestion(index) {
                    this.questions.splice(index, 1);
                },

                updateQuestionOptions(qIndex) {
                    const question = this.questions[qIndex];
                    if (question.question_type === 'single_choice' || question.question_type === 'multiple_choice') {
                        if (question.options.length === 0) {
                            question.options = [''];
                        }
                    } else {
                        question.options = [];
                    }
                },

                addOption(qIndex) {
                    this.questions[qIndex].options.push('');
                },

                removeOption(qIndex, oIndex) {
                    this.questions[qIndex].options.splice(oIndex, 1);
                },

                submitSurvey() {
                    // Clean up questions by removing empty options
                    const cleanedQuestions = this.questions.map(q => ({
                        title: q.title,
                        question_type: q.question_type,
                        options: q.options ? q.options.filter(opt => opt.trim() !== '') : []
                    }));

                    const formData = {
                        ...this.survey,
                        questions: cleanedQuestions,
                        _token: document.querySelector('meta[name="csrf-token"]')?.content ||
                            document.querySelector('input[name="_token"]')?.value
                    };

                    console.log('Submitting survey with data:', formData);
                    console.log('Number of questions:', cleanedQuestions.length);

                    fetch('{{ route("surveys.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': formData._token
                        },
                        body: JSON.stringify(formData)
                    })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Server response:', data);
                            if (data.success || data.message) {
                                window.location.href = '{{ route("surveys.index") }}';
                            } else {
                                alert('Error creating survey');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while creating the survey');
                        });
                }
            };
        }
    </script>
</x-app-layout>