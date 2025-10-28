<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Painel de Controlo
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="sm:px-6 lg:px-8">
            @if(Auth::user()->is_admin)

            <div x-data="{ openGroup: null }">
                @forelse($stationsData as $group => $groupData)
                <div class="mb-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between cursor-pointer"
                                @click="openGroup = openGroup === '{{ $group }}' ? null : '{{ $group }}'">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-700 p-3 rounded flex-1">
                                    Grupo {{ $group }}
                                    <span class="text-sm font-normal text-gray-600 dark:text-gray-400 ml-2">
                                        ({{ count($groupData['stations']) }} postos)
                                    </span>
                                </h4>
                                <div class="ml-4 transition-transform duration-200"
                                    :class="openGroup === '{{ $group }}' ? 'rotate-90' : ''">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>

                            <div x-show="openGroup === '{{ $group }}'"
                                x-transition
                                class="mt-4">

                                @foreach($groupData['stations'] as $stationData)
                                <div class="mb-6 p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                                    <div class="flex justify-between items-center mb-4">
                                        <h5 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                                            {{ $stationData['station']->name }}
                                        </h5>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('surveys.navigateToCurrentMonth', $stationData['station']) }}"
                                                class="inline-flex items-center px-3 py-1.5 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                                Mês Atual
                                            </a>
                                            @if($stationData['latest_survey'])
                                            <a href="{{ route('surveys.show', $stationData['latest_survey']) }}"
                                                class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                                Ver Último Registo
                                            </a>
                                            @endif
                                        </div>
                                    </div>

                                    @if($stationData['latest_survey'])
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                        <div>
                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Último Registo:</span>
                                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                                {{ $stationData['latest_survey']->getMonthYearLabel() }}
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Último Dia Preenchido:</span>
                                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                                {{ $stationData['last_filled_day'] ? 'Dia ' . $stationData['last_filled_day'] : 'N/A' }}
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Estado:</span>
                                            <div class="text-sm">
                                                @if($stationData['station']->is_active)
                                                <span class="text-green-600 dark:text-green-400">Ativo</span>
                                                @else
                                                <span class="text-red-600 dark:text-red-400">Inativo</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if(count($stationData['fuels_data']) > 0)
                                    <div class="overflow-x-auto mb-4">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-700">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Combustível</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Sondagem Atual</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Consumo do Dia</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Média Diária (7d)</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Dias de Stock</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Espaço Disponível</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                @foreach($stationData['fuels_data'] as $fuelData)
                                                @php
                                                $fuelColors = [
                                                'G.Simples' => 'background-color: #000; color: #fff;',
                                                'G.Aditivado' => 'background-color: #fbbf24; color: #000;',
                                                'Agrícola Verde' => 'background-color: #bbf7d0; color: #000;',
                                                'Gasolina 95' => 'background-color: #16a34a; color: #fff;',
                                                'Gasolina 95 ADT' => 'background-color: #61A316; color: #fff;',
                                                'Gasolina 98' => 'background-color: #a3e635; color: #000;',
                                                'AdBlue' => 'background-color: #3b82f6; color: #fff;',
                                                'GPL' => 'background-color:rgb(246, 143, 59); color: #000;',
                                                ];

                                                $fuelStyle = '';
                                                foreach($fuelColors as $name => $style) {
                                                if(str_contains($fuelData['fuel']->name, $name)) {
                                                $fuelStyle = $style;
                                                break;
                                                }
                                                }
                                                @endphp
                                                <tr style="{{ $fuelStyle ?: 'background-color: #f3f4f6; color: #000;' }}">
                                                    <td class="px-4 py-2 text-sm font-medium">
                                                        {{ $fuelData['fuel']->name }} ({{ $fuelData['fuel']->capacity }}LT)
                                                    </td>
                                                    <td class="px-4 py-2 text-sm font-medium">
                                                        {{ $fuelData['current_sounding'] }} LT
                                                    </td>
                                                    <td class="px-4 py-2 text-sm font-medium">
                                                        {{ $fuelData['current_total'] }} LT
                                                    </td>
                                                    <td class="px-4 py-2 text-sm font-medium">
                                                        {{ $fuelData['average_daily_consumption'] }} LT/dia
                                                    </td>
                                                    <td class="px-4 py-2 text-sm font-medium">
                                                        @if($fuelData['days_remaining'])
                                                        <span @class([ 'px-2 py-1 rounded text-xs font-medium' , 'bg-red-600 text-white dark:bg-red-900/20 dark:text-red-200'=> $fuelData['days_remaining'] <= 5, 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-200'=> $fuelData['days_remaining'] > 5 && $fuelData['days_remaining'] <= 15, 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-200'=> $fuelData['days_remaining'] > 15 ])>
                                                                    {{ $fuelData['days_remaining'] }} dias
                                                        </span>
                                                        @else
                                                        <span class="px-2 py-1 rounded text-xs font-medium bg-red-600 text-white dark:bg-red-900/20 dark:text-red-200">ZERO</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-2 text-sm font-medium">
                                                        {{ $fuelData['available_space'] }} LT
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="flex justify-end">
                                        <a href="{{ route('fuel-requests.create') }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Pedir Combustível
                                        </a>
                                    </div>
                                    @endif
                                    @else
                                    <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                                        Nenhum registo encontrado para este posto.
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                        Nenhum posto encontrado.
                    </div>
                </div>
                @endforelse
            </div>

            @else
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    Os Meus Postos
                </h3>
            </div>

            @forelse($userStations as $stationData)
            <div class="mb-4 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ $stationData['station']->name }}
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Grupo {{ $stationData['station']->group }}
                            </p>
                            @if($stationData['has_current_survey'])
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                Registo do mês atual existe
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                Registo do mês atual em falta
                            </span>
                            @endif
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('surveys.navigateToCurrentMonth', $stationData['station']) }}"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                Ir para Mês Atual
                            </a>
                            <a href="{{ route('surveys.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Ver Todos os Registos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                    Não tem postos associados. Contacte o administrador.
                </div>
            </div>
            @endforelse

            @if(Auth::user()->hasStationWithoutCurrentSurvey())
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100">
                            Tem postos sem registo do mês atual
                        </h4>
                        <p class="text-sm text-blue-700 dark:text-blue-300">
                            Crie os registos em falta para manter os dados atualizados.
                        </p>
                    </div>
                    <a href="{{ route('surveys.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        Criar Registo
                    </a>
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>
</x-app-layout>