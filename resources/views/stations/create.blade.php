<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Criar Posto
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('stations.store') }}">
                        @csrf

                        <div class="mt-4">
                            <x-input-label for="name" :value="__('Nome')" class="dark:text-gray-300" />
                            <x-text-input id="name" class="block mt-1 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="group" :value="__('Grupo')" class="dark:text-gray-300" />
                            <x-text-input id="group" class="block mt-1 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" type="number" name="group" min="1" :value="old('group')" required />
                            <x-input-error :messages="$errors->get('group')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="address" :value="__('Morada')" class="dark:text-gray-300" />
                            <x-text-input id="address" class="block mt-1 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" type="text" name="address" :value="old('address')" required />
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="city" :value="__('Localidade')" class="dark:text-gray-300" />
                            <x-text-input id="city" class="block mt-1 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" type="text" name="city" :value="old('city')" required />
                            <x-input-error :messages="$errors->get('city')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="user_ids" :value="__('Colaboradores')" class="dark:text-gray-300" />
                            
                            <div class="mt-2">
                                <input 
                                    type="text" 
                                    id="search-users" 
                                    placeholder="Pesquisar colaboradores..." 
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 text-sm"
                                    onkeyup="filterUsers(this.value)"
                                >
                            </div>
                            
                            <div class="mt-2 max-h-48 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700">
                                <div class="p-3 space-y-2" id="users-container">
                                    @foreach($users as $user)
                                    <label class="flex items-center user-item hover:bg-gray-100 dark:hover:bg-gray-600 p-1 rounded cursor-pointer" data-name="{{ strtolower($user->name) }}">
                                        <input 
                                            type="checkbox" 
                                            name="user_ids[]" 
                                            value="{{ $user->id }}" 
                                            class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-700 dark:ring-offset-gray-800"
                                            {{ in_array($user->id, old('user_ids', $selectedUsers ?? [])) ? 'checked' : '' }}
                                            onchange="updateSelectedCount()"
                                        >
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $user->name }}</span>
                                    </label>
                                    @endforeach
                                </div>
                                
                                <div id="no-results" class="p-3 text-center text-sm text-gray-500 dark:text-gray-400 hidden">
                                    Nenhum colaborador encontrado
                                </div>
                            </div>
                            
                            <div class="mt-1 flex justify-between">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Selecione um ou mais utilizadores colaboradores</p>
                                <span id="selected-count" class="text-sm font-medium text-indigo-600 dark:text-indigo-400">0 selecionados</span>
                            </div>
                            
                            <x-input-error :messages="$errors->get('user_ids')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ url()->previous() }}" class="mr-3 underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                Cancelar
                            </a>
                            <x-primary-button class="ml-4">
                                Criar
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script>
    function filterUsers(searchTerm) {
        const items = document.querySelectorAll('.user-item');
        const noResults = document.getElementById('no-results');
        let visibleItems = 0;
        
        searchTerm = searchTerm.toLowerCase();
        
        items.forEach(item => {
            const userName = item.getAttribute('data-name');
            if (userName.includes(searchTerm)) {
                item.style.display = 'flex';
                visibleItems++;
            } else {
                item.style.display = 'none';
            }
        });
        
        if (visibleItems === 0 && searchTerm !== '') {
            noResults.classList.remove('hidden');
        } else {
            noResults.classList.add('hidden');
        }
    }

    function updateSelectedCount() {
        const checkboxes = document.querySelectorAll('input[name="user_ids[]"]:checked');
        const counter = document.getElementById('selected-count');
        const count = checkboxes.length;
        
        if (count === 0) {
            counter.textContent = '0 selecionados';
        } else if (count === 1) {
            counter.textContent = '1 selecionado';
        } else {
            counter.textContent = `${count} selecionados`;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateSelectedCount();
    });
</script>
</x-app-layout>