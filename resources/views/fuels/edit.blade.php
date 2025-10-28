<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Editar Combustível | {{ $fuel->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('fuels.update', $fuel) }}">
                        @csrf
                        @method('PUT')

                        <div class="mt-4">
                            <x-input-label for="name" :value="__('Nome')" class="dark:text-gray-300" />
                            <select id="name" name="name" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm dark:text-gray-300" required>
                                <option value=""></option>
                                <option value="G.Simples" {{ (old('name') ?? $fuel->name) == 'G.Simples' ? 'selected' : '' }}>G.Simples</option>
                                <option value="G.Aditivado" {{ (old('name') ?? $fuel->name) == 'G.Aditivado' ? 'selected' : '' }}>G.Aditivado</option>
                                <option value="Agrícola Verde" {{ (old('name') ?? $fuel->name) == 'Agrícola Verde' ? 'selected' : '' }}>Agrícola Verde</option>
                                <option value="Gasolina 95" {{ (old('name') ?? $fuel->name) == 'Gasolina 95' ? 'selected' : '' }}>Gasolina 95</option>
                                <option value="Gasolina 95 ADT" {{ (old('name') ?? $fuel->name) == 'Gasolina 95 ADT' ? 'selected' : '' }}>Gasolina 95 ADT</option>
                                <option value="Gasolina 98" {{ (old('name') ?? $fuel->name) == 'Gasolina 98' ? 'selected' : '' }}>Gasolina 98</option>
                                <option value="AdBlue" {{ (old('name') ?? $fuel->name) == 'AdBlue' ? 'selected' : '' }}>AdBlue</option>
                                <option value="GPL" {{ (old('name') ?? $fuel->name) == 'GPL' ? 'selected' : '' }}>GPL</option>
                            </select>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="counter" :value="__('Quantidade de contador')" class="dark:text-gray-300" />
                            <x-text-input id="counter" class="block mt-1 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" type="number" name="counter" min="1" :value="old('counter', $fuel->counter)" required />
                            <x-input-error :messages="$errors->get('counter')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="capacity" :value="__('Capacidade (LT)')" class="dark:text-gray-300" />
                            <x-text-input id="capacity" class="block mt-1 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" type="number" name="capacity" min="1" :value="old('capacity', $fuel->capacity)" required />
                            <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <label for="is_active" class="inline-flex items-center">
                                <input id="is_active" type="checkbox" class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-700 dark:ring-offset-gray-800" name="is_active" {{ $fuel->is_active ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">{{ __('Ativo') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <input type="hidden" name="station_id" value="{{ $fuel->station_id }}" />

                            <a href="{{ url()->previous() }}" class="mr-3 underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                Cancelar
                            </a>
                            <x-primary-button class="ml-4">
                                Salvar
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>