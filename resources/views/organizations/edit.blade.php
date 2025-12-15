<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Organization') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('organizations.update', $organization) }}">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Organization Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $organization->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('organizations.index') }}">
                                {{ __('Cancel') }}
                            </a>

                            <x-primary-button class="ms-4">
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Members</h3>
                    
                    <ul class="mb-6 divide-y divide-gray-200">
                        @foreach($organization->users as $user)
                        <li class="py-3 flex justify-between items-center">
                            <div class="flex items-center">
                                <span class="font-medium text-gray-900">{{ $user->name }}</span>
                                <span class="ml-2 text-sm text-gray-500">({{ $user->email }})</span>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ ucfirst($user->pivot->role) }}
                            </span>
                        </li>
                        @endforeach
                    </ul>

                    <h4 class="text-md font-medium mb-2">Invite Member</h4>
                    <form method="POST" action="{{ route('organizations.invite', $organization) }}" class="flex gap-4 items-end">
                        @csrf
                        <div class="flex-grow">
                            <x-input-label for="email" :value="__('User Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <x-primary-button>
                            {{ __('Invite') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
