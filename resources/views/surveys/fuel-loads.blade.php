<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Histórico de Cargas | {{ $survey->station->name }} | {{ $survey->getMonthYearLabel() }}
            </h2>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                
                    <div class="mb-6">
                        <a href="{{ route('surveys.show', $survey) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                            </svg>
                            Voltar ao Registo
                        </a>
                    </div>

                    @if($fuelLoads->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Data</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dia</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Combustível</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sondagem Anterior</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantidade</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Utilizador</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Observações</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($fuelLoads as $load)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $load->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $load->day }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $load->fuel->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $load->current_sounding }} LT</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-semibold">
                                        <span class="text-green-600 dark:text-green-400">+{{ $load->load_amount }} LT</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $load->user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $load->notes }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button 
                                                x-data="{}"
                                                x-on:click="editFuelLoad({{ $load->id }})"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                            <button 
                                                x-data="{}"
                                                x-on:click="confirmDeleteFuelLoad({{ $load->id }}, '{{ $load->fuel->name }}', {{ $load->day }}, {{ $load->load_amount }})"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-6 text-center">
                        <p class="text-gray-500 dark:text-gray-400">Não existem cargas registadas para este mês.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-modal name="edit-fuel-load" :show="false" focusable>
        <form id="edit-fuel-load-form" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Editar Carga de Combustível
            </h2>

            <div class="mt-4">
                <x-input-label for="edit_fuel_name" :value="__('Combustível')" class="dark:text-gray-300" />
                <div id="edit_fuel_name" class="block mt-1 w-full p-2 bg-gray-100 rounded border text-gray-700"></div>
            </div>

            <div class="mt-4">
                <x-input-label for="edit_day" :value="__('Dia')" class="dark:text-gray-300" />
                <div id="edit_day" class="block mt-1 w-full p-2 bg-gray-100 rounded border text-gray-700"></div>
            </div>

            <div class="mt-4">
                <x-input-label for="edit_load_amount" :value="__('Quantidade da Carga (LT)')" class="dark:text-gray-300" />
                <x-text-input id="edit_load_amount" class="block mt-1 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" type="number" name="load_amount" step="1" min="1" required />
                <x-input-error :messages="$errors->get('load_amount')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="edit_notes" :value="__('Observações (opcional)')" class="dark:text-gray-300" />
                <textarea
                    id="edit_notes"
                    name="notes"
                    class="block mt-1 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 border-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    rows="3"></textarea>
                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancelar
                </x-secondary-button>
                <x-primary-button class="ml-3">
                    Atualizar Carga
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <x-modal name="confirm-delete-fuel-load" :show="false" focusable>
        <form id="delete-fuel-load-form" method="POST" class="p-6">
            @csrf
            @method('DELETE')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Confirmar Eliminação
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Tem a certeza que pretende eliminar esta carga de combustível?
            </p>

            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    <div><strong>Combustível:</strong> <span id="delete_fuel_name"></span></div>
                    <div><strong>Dia:</strong> <span id="delete_day"></span></div>
                    <div><strong>Quantidade:</strong> <span id="delete_amount"></span> LT</div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancelar
                </x-secondary-button>
                <x-danger-button class="ml-3">
                    Eliminar Carga
                </x-danger-button>
            </div>
        </form>
    </x-modal>

    <script>
        async function editFuelLoad(fuelLoadId) {
            try {
                const response = await fetch(`{{ route('surveys.fuel-loads.edit', [$survey, ':id']) }}`.replace(':id', fuelLoadId));
                
                if (!response.ok) {
                    throw new Error('Erro ao carregar dados da carga');
                }
                
                const fuelLoad = await response.json();
                
                document.getElementById('edit_fuel_name').textContent = fuelLoad.fuel_name;
                document.getElementById('edit_day').textContent = fuelLoad.day;
                document.getElementById('edit_load_amount').value = fuelLoad.load_amount;
                document.getElementById('edit_notes').value = fuelLoad.notes || '';
                
                const form = document.getElementById('edit-fuel-load-form');
                form.action = `{{ route('surveys.fuel-loads.update', [$survey, ':id']) }}`.replace(':id', fuelLoadId);
                
                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-fuel-load' }));
                
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro ao carregar dados da carga');
            }
        }

        function confirmDeleteFuelLoad(fuelLoadId, fuelName, day, amount) {
            document.getElementById('delete_fuel_name').textContent = fuelName;
            document.getElementById('delete_day').textContent = day;
            document.getElementById('delete_amount').textContent = amount;
            
            const form = document.getElementById('delete-fuel-load-form');
            form.action = `{{ route('surveys.fuel-loads.destroy', [$survey, ':id']) }}`.replace(':id', fuelLoadId);
            
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-delete-fuel-load' }));
        }
    </script>
</x-app-layout>