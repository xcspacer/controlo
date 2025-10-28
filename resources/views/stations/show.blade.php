<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Posto | {{ $station->name }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">

                    <div class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Nome</p>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $station->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Grupo</p>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $station->group }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Morada</p>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $station->address }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Localidade</p>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $station->city }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Ativo</p>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $station->is_active ? 'Sim' : 'Não' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Data de Registo</p>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $station->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Última Atualização</p>
                                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $station->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Combustíveis</h3>
                            <a href="{{ route('fuels.create', ['station_id' => $station->id]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Adicionar Combustível
                            </a>
                        </div>

                        @if($fuels->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nome</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantidade de contador</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Capacidade (LT)</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ativo</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Última Atualização</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($fuels as $fuel)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $fuel->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $fuel->counter }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $fuel->capacity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $fuel->is_active ? 'Sim' : 'Não' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $fuel->updated_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('fuels.edit', $fuel) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                                <button
                                                    type="button"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                    x-data="{}"
                                                    x-on:click="$dispatch('open-modal', 'confirm-fuel-deletion-{{ $fuel->id }}')">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                                <x-modal name="confirm-fuel-deletion-{{ $fuel->id }}" :show="false" focusable>
                                                    <form method="POST" action="{{ route('fuels.destroy', $fuel) }}" class="p-6">
                                                        @csrf
                                                        @method('DELETE')
                                                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 text-left">
                                                            Tens a certeza que pretendes eliminar este combustível?
                                                        </h2>
                                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 break-normal whitespace-normal overflow-wrap-normal text-left">
                                                            Esta ação é irreversível.
                                                        </p>
                                                        <div class="mt-6 flex justify-end">
                                                            <input type="hidden" name="station_id" value="{{ $fuel->station_id }}" />

                                                            <x-secondary-button x-on:click="$dispatch('close')">
                                                                Cancelar
                                                            </x-secondary-button>
                                                            <x-danger-button class="ml-3">
                                                                Eliminar
                                                            </x-danger-button>
                                                        </div>
                                                    </form>
                                                </x-modal>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-6 text-center">
                            <p class="text-gray-500 dark:text-gray-400">Não existem combustíveis registados para este posto.</p>
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-end mt-8">
                        <a href="{{ route('stations.index') }}" class="mr-3 underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            Voltar
                        </a>
                        <a href="{{ route('stations.edit', $station) }}" class="ml-4 underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            Editar
                        </a>
                        <x-danger-button
                            class="ml-4"
                            x-data="{}"
                            x-on:click="$dispatch('open-modal', 'confirm-station-deletion')">
                            Eliminar Posto
                        </x-danger-button>
                        <x-modal name="confirm-station-deletion" :show="false" focusable>
                            <form method="POST" action="{{ route('stations.destroy', $station) }}" class="p-6">
                                @csrf
                                @method('DELETE')
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Tens a certeza que pretendes eliminar este posto?
                                </h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Esta ação é irreversível. Todos os dados associados a este posto serão permanentemente removidos.
                                </p>
                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close')">
                                        Cancelar
                                    </x-secondary-button>
                                    <x-danger-button class="ml-3">
                                        Eliminar Posto
                                    </x-danger-button>
                                </div>
                            </form>
                        </x-modal>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>