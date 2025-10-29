<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Pedir Combustível
        </h2>
    </x-slot>

    <div class="py-12" x-data="fuelRequestForm()">
        <div class="sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                        Erros de validação:
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mb-6 p-4 bg-amber-50 dark:bg-amber-900/20 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Data Prevista de Entrega
                                </label>
                                <input type="date" x-model="deliveryDate" @change="updateProjections()"
                                    :min="minDate"
                                    name="delivery_date_view"
                                    class="block w-full rounded-md border-gray-300 dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300">
                                <x-input-error :messages="$errors->get('delivery_date')" class="mt-2" />
                            </div>
                            <div x-show="deliveryDate" class="flex items-center">
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-medium">Dias até a entrega:</span> 
                                    <span x-text="daysUntilDelivery"></span> dias
                                    <br>
                                    <span class="text-xs">As quantidades disponíveis foram ajustadas com base no consumo médio previsto</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-lg font-medium text-blue-900 dark:text-blue-100">
                                Total do Pedido
                            </h3>
                            <span class="text-lg font-bold" :class="totalQuantity > 32000 ? 'text-red-600' : 'text-blue-900 dark:text-blue-100'">
                                <span x-text="totalQuantity.toLocaleString('pt-PT')"></span> / 32.000 LT
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4 dark:bg-gray-700">
                            <div class="h-4 rounded-full transition-all duration-300"
                                :class="totalQuantity > 32000 ? 'bg-red-600' : 'bg-blue-600'"
                                :style="`width: ${Math.min((totalQuantity / 32000) * 100, 100)}%`">
                            </div>
                        </div>
                        <div class="mt-2 text-sm" :class="totalQuantity > 32000 ? 'text-red-600' : 'text-gray-600 dark:text-gray-400'">
                            <span x-show="totalQuantity <= 32000">
                                Disponível: <span x-text="(32000 - totalQuantity).toLocaleString('pt-PT')"></span> LT
                            </span>
                            <span x-show="totalQuantity > 32000">
                                Excedido: <span x-text="(totalQuantity - 32000).toLocaleString('pt-PT')"></span> LT
                            </span>
                        </div>
                    </div>

                    <div class="mb-6" x-show="requests.length > 0">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                            Pedidos Adicionados
                        </h3>
                        <div class="space-y-2">
                            <template x-for="(request, index) in requests" :key="index">
                                <div class="flex items-center justify-between p-3 rounded-lg"
                                    :class="checkRequestCapacity(request) ? 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800' : 'bg-gray-50 dark:bg-gray-700'">
                                    <div class="flex-1">
                                        <span class="font-medium" x-text="request.station_name"></span> - 
                                        <span x-text="request.fuel_name"></span>
                                        <span class="text-sm text-gray-600 dark:text-gray-400 ml-2">
                                            (Grupo <span x-text="request.group"></span>)
                                        </span>
                                        <div x-show="checkRequestCapacity(request)" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                            ⚠️ Este pedido pode exceder a capacidade disponível após recalcular com a data de entrega atual
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="font-medium" x-text="request.quantity.toLocaleString('pt-PT') + ' LT'"></span>
                                        <button type="button" @click="removeRequest(index)"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                            Adicionar Pedido
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Grupo
                                </label>
                                <select x-model="selectedGroup" @change="onGroupChange()"
                                    class="block w-full rounded-md border-gray-300 dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300">
                                    <option value="">Selecione um grupo</option>
                                    @foreach($groups as $groupData)
                                        <option value="{{ $groupData['group'] }}">Grupo {{ $groupData['group'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Posto
                                </label>
                                <select x-model="selectedStation" @change="onStationChange()"
                                    x-bind:disabled="!selectedGroup"
                                    class="block w-full rounded-md border-gray-300 dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300 disabled:opacity-50">
                                    <option value="">Selecione um posto</option>
                                    <template x-for="station in availableStations" :key="station.id">
                                        <option :value="station.id" x-text="station.name"></option>
                                    </template>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Combustível
                                </label>
                                <select x-model="selectedFuel"
                                    x-bind:disabled="!selectedStation"
                                    class="block w-full rounded-md border-gray-300 dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300 disabled:opacity-50">
                                    <option value="">Selecione um combustível</option>
                                    <template x-for="fuel in availableFuels" :key="fuel.id">
                                        <option :value="fuel.id">
                                            <span x-text="fuel.name"></span> - 
                                            <span x-text="getProjectedSpace(fuel) + ' LT disponível'"></span>
                                        </option>
                                    </template>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Quantidade (LT)
                                </label>
                                <div class="flex space-x-2">
                                    <input type="number" x-model.number="quantity"
                                        x-bind:disabled="!selectedFuel"
                                        :max="maxQuantity"
                                        min="1"
                                        class="block w-full rounded-md border-gray-300 dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300 disabled:opacity-50"
                                        :placeholder="selectedFuel ? 'Máx: ' + maxQuantity : ''">
                                    <button type="button" @click="addRequest()"
                                        x-bind:disabled="!canAddRequest"
                                        class="px-4 py-2 bg-green-600 text-white rounded-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-green-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div x-show="selectedFuel" class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            <span x-text="getFuelInfo()"></span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('fuel-requests.store') }}" @submit="prepareSubmit($event)">
                        @csrf
                        
                        <div id="hidden-requests"></div>
                        <input type="hidden" name="total_quantity" x-bind:value="totalQuantity">
                        <input type="hidden" name="delivery_date" x-bind:value="deliveryDate">

                        <div class="mb-6">
                            <x-input-label for="notes" :value="__('Observações (opcional)')" class="dark:text-gray-300" />
                            <textarea
                                id="notes"
                                name="notes"
                                rows="4"
                                class="block mt-1 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 border-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                placeholder="Informações adicionais sobre o pedido...">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('fuel-requests.index') }}" class="mr-3 underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                Cancelar
                            </a>
                            <x-primary-button type="submit" x-bind:disabled="requests.length === 0 || totalQuantity > 32000 || !deliveryDate">
                                Enviar Pedido
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fuelRequestForm() {
            return {
                groups: @json($groups),
                requests: [],
                selectedGroup: '',
                selectedStation: '',
                selectedFuel: '',
                quantity: 0,
                availableStations: [],
                availableFuels: [],
                deliveryDate: '',
                minDate: new Date().toISOString().split('T')[0],

                get daysUntilDelivery() {
                    if (!this.deliveryDate) return 0;
                    
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    const delivery = new Date(this.deliveryDate);
                    delivery.setHours(0, 0, 0, 0);
                    
                    const diffTime = delivery - today;
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    
                    return Math.max(0, diffDays);
                },

                get totalQuantity() {
                    return this.requests.reduce((sum, request) => sum + request.quantity, 0);
                },

                get maxQuantity() {
                    if (!this.selectedFuel) return 0;
                    
                    const fuel = this.availableFuels.find(f => f.id == this.selectedFuel);
                    if (!fuel) return 0;
                    
                    // Calcular o espaço projetado para este posto/combustível específico
                    const projectedSpace = this.getProjectedSpace(fuel);
                    
                    // Limite global de 32.000 LT
                    const remainingGlobalCapacity = 32000 - this.totalQuantity;
                    
                    // Calcular quantidade total já solicitada para este posto/combustível
                    // (pode haver múltiplos pedidos para o mesmo posto/combustível)
                    const existingRequests = this.requests.filter(r => 
                        r.station_id == this.selectedStation && r.fuel_id == this.selectedFuel
                    );
                    
                    const totalExistingQuantity = existingRequests.reduce((sum, r) => sum + r.quantity, 0);
                    
                    // Calcular capacidade restante para este posto/combustível
                    const remainingStationCapacity = Math.max(0, projectedSpace - totalExistingQuantity);
                    
                    // Retornar o menor entre os limites: global e individual do posto
                    return Math.min(remainingStationCapacity, remainingGlobalCapacity);
                },

                get canAddRequest() {
                    return this.selectedGroup && this.selectedStation && this.selectedFuel && 
                           this.quantity > 0 && this.quantity <= this.maxQuantity && this.deliveryDate;
                },

                getProjectedSpace(fuel) {
                    if (!fuel) return 0;
                    
                    let projectedSpace = fuel.available_space;
                    
                    if (this.daysUntilDelivery > 0 && fuel.average_consumption > 0) {
                        projectedSpace += (fuel.average_consumption * this.daysUntilDelivery);
                    }
                    
                    return Math.min(projectedSpace, fuel.capacity);
                },

                updateProjections() {
                    if (this.selectedFuel) {
                        const currentFuel = this.selectedFuel;
                        this.selectedFuel = '';
                        this.$nextTick(() => {
                            this.selectedFuel = currentFuel;
                        });
                    }
                },

                onGroupChange() {
                    this.selectedStation = '';
                    this.selectedFuel = '';
                    this.quantity = 0;
                    this.availableFuels = [];
                    
                    if (this.selectedGroup) {
                        const group = this.groups.find(g => g.group == this.selectedGroup);
                        this.availableStations = group ? group.stations : [];
                    } else {
                        this.availableStations = [];
                    }
                },

                onStationChange() {
                    this.selectedFuel = '';
                    this.quantity = 0;
                    
                    if (this.selectedStation) {
                        const station = this.availableStations.find(s => s.id == this.selectedStation);
                        this.availableFuels = station ? station.fuels : [];
                    } else {
                        this.availableFuels = [];
                    }
                },

                getFuelInfo() {
                    if (!this.selectedFuel) return '';
                    
                    const fuel = this.availableFuels.find(f => f.id == this.selectedFuel);
                    if (!fuel) return '';
                    
                    let info = `Stock atual: ${fuel.current_stock.toLocaleString('pt-PT')} LT | `;
                    info += `Capacidade: ${fuel.capacity.toLocaleString('pt-PT')} LT`;
                    
                    if (fuel.average_consumption > 0) {
                        info += ` | Consumo médio (7d): ${fuel.average_consumption} LT/dia`;
                        
                        if (this.daysUntilDelivery > 0) {
                            const consumoTotal = fuel.average_consumption * this.daysUntilDelivery;
                            info += ` | Consumo previsto até entrega: ${consumoTotal.toLocaleString('pt-PT')} LT`;
                        }
                    }
                    
                    return info;
                },

                addRequest() {
                    if (!this.canAddRequest) return;
                    
                    const station = this.availableStations.find(s => s.id == this.selectedStation);
                    const fuel = this.availableFuels.find(f => f.id == this.selectedFuel);
                    
                    this.requests.push({
                        group: this.selectedGroup,
                        station_id: this.selectedStation,
                        station_name: station.name,
                        fuel_id: this.selectedFuel,
                        fuel_name: fuel.name,
                        quantity: this.quantity
                    });
                    
                    this.selectedFuel = '';
                    this.quantity = 0;
                },

                removeRequest(index) {
                    this.requests.splice(index, 1);
                },

                checkRequestCapacity(request) {
                    // Verificar se este pedido específico ainda está dentro da capacidade
                    // considerando todos os outros pedidos para o mesmo posto/combustível
                    if (!this.deliveryDate) return false;
                    
                    // Encontrar o grupo e station correspondente
                    const group = this.groups.find(g => g.group == request.group);
                    if (!group) return false;
                    
                    const station = group.stations.find(s => s.id == request.station_id);
                    if (!station) return false;
                    
                    const fuel = station.fuels.find(f => f.id == request.fuel_id);
                    if (!fuel) return false;
                    
                    // Calcular espaço projetado
                    const projectedSpace = this.getProjectedSpace(fuel);
                    
                    // Calcular quantidade total solicitada para este posto/combustível
                    const allRequestsForSame = this.requests.filter(r => 
                        r.station_id == request.station_id && r.fuel_id == request.fuel_id
                    );
                    
                    const totalQuantity = allRequestsForSame.reduce((sum, r) => sum + r.quantity, 0);
                    
                    // Se a quantidade total excede o espaço projetado, retornar true
                    return totalQuantity > projectedSpace;
                },

                prepareSubmit(event) {
                    // Validações iniciais
                    if (this.requests.length === 0) {
                        event.preventDefault();
                        alert('Adicione pelo menos um pedido');
                        return false;
                    }
                    
                    if (this.totalQuantity > 32000) {
                        event.preventDefault();
                        alert('O total do pedido excede o limite de 32.000 litros');
                        return false;
                    }
                    
                    if (!this.deliveryDate) {
                        event.preventDefault();
                        alert('Selecione a data de entrega');
                        return false;
                    }
                    
                    // Preparar os dados antes do submit
                    const form = event.target;
                    
                    // Atualizar os valores dos inputs hidden
                    const totalQuantityInput = form.querySelector('input[name="total_quantity"]');
                    const deliveryDateInput = form.querySelector('input[name="delivery_date"]');
                    
                    if (totalQuantityInput) {
                        totalQuantityInput.value = this.totalQuantity;
                    } else {
                        console.error('Input total_quantity não encontrado');
                        event.preventDefault();
                        return false;
                    }
                    
                    if (deliveryDateInput) {
                        deliveryDateInput.value = this.deliveryDate;
                    } else {
                        console.error('Input delivery_date não encontrado');
                        event.preventDefault();
                        return false;
                    }
                    
                    // Limpar e recriar os inputs hidden dos requests
                    const hiddenContainer = document.getElementById('hidden-requests');
                    if (!hiddenContainer) {
                        console.error('Container de requests não encontrado');
                        event.preventDefault();
                        return false;
                    }
                    
                    hiddenContainer.innerHTML = '';
                    
                    // Criar os inputs hidden para cada request
                    this.requests.forEach((request, index) => {
                        const stationInput = document.createElement('input');
                        stationInput.type = 'hidden';
                        stationInput.name = `requests[${index}][station_id]`;
                        stationInput.value = String(request.station_id || '');
                        hiddenContainer.appendChild(stationInput);
                        
                        const fuelInput = document.createElement('input');
                        fuelInput.type = 'hidden';
                        fuelInput.name = `requests[${index}][fuel_id]`;
                        fuelInput.value = String(request.fuel_id || '');
                        hiddenContainer.appendChild(fuelInput);
                        
                        const quantityInput = document.createElement('input');
                        quantityInput.type = 'hidden';
                        quantityInput.name = `requests[${index}][quantity]`;
                        quantityInput.value = parseInt(request.quantity) || 0;
                        hiddenContainer.appendChild(quantityInput);
                    });
                    
                    // Se chegou aqui, tudo está OK - permitir que o formulário seja submetido
                    // Não fazer preventDefault, deixar o submit natural acontecer
                }
            }
        }
    </script>
</x-app-layout>