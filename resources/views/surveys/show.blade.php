<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Registo | {{ $survey->station->name }} | {{ $survey->getMonthYearLabel() }}
            </h2>
            <div class="flex items-center space-x-2">
                @if($previousSurvey)
                <a href="{{ route('surveys.show', $previousSurvey) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    {{ $previousSurvey->getMonthYearLabel() }}
                </a>
                @endif

                @if($nextSurvey)
                <a href="{{ route('surveys.show', $nextSurvey) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ $nextSurvey->getMonthYearLabel() }}
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                @endif
            </div>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-gray-50 rounded-lg mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Posto</label>
                            <div class="text-lg font-semibold">{{ $survey->station->name }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mês</label>
                            <div class="text-lg font-semibold">
                                {{ $survey->getFormattedMonthName() }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ano</label>
                            <div class="text-lg font-semibold">{{ $survey->year }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dias no mês</label>
                            <div class="text-lg font-semibold">{{ $survey->days_in_month }}</div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <a href="{{ route('surveys.index') }}" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                            </svg>
                            Voltar
                        </a>

                        <a href="{{ route('surveys.edit', $survey) }}" class="ml-2 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar
                        </a>

                        <button
                            x-data="{}"
                            x-on:click="$dispatch('open-modal', 'add-fuel-load')"
                            type="button"
                            class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Adicionar Carga
                        </button>

                        <a href="{{ route('surveys.fuel-loads', $survey) }}" class="ml-2 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Ver Histórico de Cargas
                        </a>

                        @if (auth()->user()->is_admin)
                        <a href="{{ route('surveys.logs', $survey) }}" class="ml-2 inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Ver Histórico de Alterações
                        </a>

                        <a href="{{ route('surveys.export', $survey) }}" class="ml-2 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-4-4m4 4l4-4m3 8H5a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M9 7h6"></path>
                            </svg>
                            Exportar para Excel
                        </a>
                        @endif
                    </div>

                    <div class="space-y-4">
                        <div class="overflow-x-auto border border-gray-300 rounded-lg">
                            <table class="min-w-full border-collapse">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="border border-gray-300 px-3 py-2 text-sm font-medium text-left sticky left-0 bg-gray-100 z-10"
                                            style="min-width: 200px;" rowspan="2">

                                        </th>
                                        @for($day = 1; $day <= $survey->days_in_month; $day++)
                                            <th class="border border-gray-300 px-1 py-1 text-xs text-center"
                                                style="min-width: 120px;" colspan="2">{{ $day }}</th>
                                            @endfor
                                    </tr>
                                    <tr class="bg-gray-100">
                                        @for($day = 1; $day <= $survey->days_in_month; $day++)
                                            <th class="border border-gray-300 px-1 py-1 text-xs">Valores</th>
                                            <th class="border border-gray-300 px-1 py-1 text-xs">Total</th>
                                            @endfor
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($survey->station->fuels as $fuel)

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
                                    if(str_contains($fuel->name, $name)) {
                                    $fuelStyle = $style;
                                    break;
                                    }
                                    }
                                    @endphp

                                    <tr class="bg-gray-200">
                                        <td class="px-3 py-2 font-semibold sticky left-0 z-10"
                                            style="{{ $fuelStyle ?: 'background-color: #e5e7eb; color: #000;' }}">
                                            {{ $fuel->name }} {{ $fuel->capacity }}LT
                                        </td>
                                        @for($day = 1; $day <= $survey->days_in_month; $day++)
                                            <td style="{{ $fuelStyle ?: 'background-color: #e5e7eb; color: #000;' }}" colspan="2"></td>
                                            @endfor
                                    </tr>

                                    @for($counter = 1; $counter <= $fuel->counter; $counter++)
                                        <tr>
                                            <td class="border border-gray-300 px-3 py-2 bg-gray-50 sticky left-0 bg-gray-50 z-10">
                                                Contador {{ $counter }}
                                            </td>
                                            @for($day = 1; $day <= $survey->days_in_month; $day++)
                                                <td class="border border-gray-300 p-1 text-center">
                                                    <span class="text-xs">
                                                        {{ $survey->readings[$fuel->id]['counters'][$counter-1]['values'][$day] ?? '0' }}
                                                    </span>
                                                </td>
                                                <td class="border border-gray-300 p-1 bg-gray-50 text-center">
                                                    <span class="text-xs font-medium">
                                                        {{ abs(intval($survey->readings[$fuel->id]['counters'][$counter-1]['totals'][$day] ?? 0)) }}
                                                    </span>
                                                </td>
                                                @endfor
                                        </tr>
                                        @endfor

                                        <tr class="bg-green-100">
                                            <td class="border border-gray-300 px-3 py-2 font-semibold sticky left-0 bg-green-100 z-10">
                                                Litros Vendidos
                                            </td>
                                            @for($day = 1; $day <= $survey->days_in_month; $day++)
                                                @php
                                                $totalLitrosVendidos = 0;
                                                if(isset($survey->readings[$fuel->id]['counters'])) {
                                                for($i = 0; $i < $fuel->counter; $i++) {
                                                    if(isset($survey->readings[$fuel->id]['counters'][$i]['totals'][$day])) {
                                                    $totalLitrosVendidos += abs(intval($survey->readings[$fuel->id]['counters'][$i]['totals'][$day]));
                                                    }
                                                    }
                                                    }
                                                    @endphp
                                                    <td class="border border-gray-300 p-1 text-center">-</td>
                                                    <td class="border border-gray-300 p-1 bg-green-50 text-center">
                                                        <span class="text-xs font-medium">
                                                            {{ $totalLitrosVendidos }}
                                                        </span>
                                                    </td>
                                                    @endfor
                                        </tr>

                                        <tr class="bg-gray-400">
                                            <td class="border border-gray-300 px-3 py-2 font-semibold sticky left-0 bg-gray-400 z-10">
                                                Sondagem
                                            </td>
                                            @for($day = 1; $day <= $survey->days_in_month; $day++)
                                                <td class="border border-gray-300 p-1 text-center {{ isset($survey->readings[$fuel->id]['sounding']['loads'][$day]) ? 'bg-yellow-200' : '' }}">
                                                    <span class="text-xs">
                                                        {{ $survey->readings[$fuel->id]['sounding']['values'][$day] ?? '0' }}
                                                    </span>
                                                    @if(isset($survey->readings[$fuel->id]['sounding']['loads'][$day]))
                                                    <span class="block text-xs text-yellow-800" title="Carga adicionada neste dia">
                                                        <svg class="w-3 h-3 inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12z"></path>
                                                            <path d="M15 12a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 17.586V12z"></path>
                                                        </svg>
                                                    </span>
                                                    @endif
                                                </td>
                                                <td class="border border-gray-300 p-1 bg-gray-300 text-center">
                                                    @php
                                                    $sondagemTotal = intval($survey->readings[$fuel->id]['sounding']['totals'][$day] ?? 0);
                                                    
                                                    if($day > 1 && isset($survey->readings[$fuel->id]['stock_entries']['values'][$day - 1])) {
                                                        $entradaStockDiaAnterior = intval($survey->readings[$fuel->id]['stock_entries']['values'][$day - 1]);
                                                        if($entradaStockDiaAnterior > 0) {
                                                            $sondagemTotal += $entradaStockDiaAnterior;
                                                        }
                                                    }
                                                    @endphp
                                                    <span class="text-xs font-medium">
                                                        {{ $sondagemTotal }}
                                                    </span>
                                                </td>
                                            @endfor
                                        </tr>

                                        <tr class="bg-blue-100">
                                            <td class="border border-gray-300 px-3 py-2 font-semibold sticky left-0 bg-blue-100 z-10">
                                                Entrada Stock
                                            </td>
                                            @for($day = 1; $day <= $survey->days_in_month; $day++)
                                                <td class="border border-gray-300 p-1 text-center">
                                                    <span class="text-xs font-medium text-blue-700">
                                                        @if(isset($survey->readings[$fuel->id]['stock_entries']['values'][$day]) && $survey->readings[$fuel->id]['stock_entries']['values'][$day] > 0)
                                                        +{{ $survey->readings[$fuel->id]['stock_entries']['values'][$day] }}
                                                        @else
                                                        -
                                                        @endif
                                                    </span>
                                                </td>
                                                <td class="border border-gray-300 p-1 bg-blue-50 text-center">
                                                    <span class="text-xs">-</span>
                                                </td>
                                                @endfor
                                        </tr>

                                        <tr class="bg-yellow-100">
                                            <td class="border border-gray-300 px-3 py-2 font-semibold sticky left-0 bg-yellow-100 z-10">
                                                Resultado A-B
                                            </td>
                                            @for($day = 1; $day <= $survey->days_in_month; $day++)
                                                @php
                                                $totalLitrosVendidos = 0;
                                                if(isset($survey->readings[$fuel->id]['counters'])) {
                                                    for($i = 0; $i < $fuel->counter; $i++) {
                                                        if(isset($survey->readings[$fuel->id]['counters'][$i]['totals'][$day])) {
                                                            $totalLitrosVendidos += abs(intval($survey->readings[$fuel->id]['counters'][$i]['totals'][$day]));
                                                        }
                                                    }
                                                }
                                                
                                                $sondagemTotal = intval($survey->readings[$fuel->id]['sounding']['totals'][$day] ?? 0);
                                                
                                                if($day > 1 && isset($survey->readings[$fuel->id]['stock_entries']['values'][$day - 1])) {
                                                    $entradaStockDiaAnterior = intval($survey->readings[$fuel->id]['stock_entries']['values'][$day - 1]);
                                                    if($entradaStockDiaAnterior > 0) {
                                                        $sondagemTotal += $entradaStockDiaAnterior;
                                                    }
                                                }
                                                
                                                $resultadoAB = $totalLitrosVendidos - abs($sondagemTotal);

                                                $corClasse = '';
                                                if ($resultadoAB < 0) {
                                                    $corClasse = 'text-red-600 font-bold';
                                                } elseif ($resultadoAB > 0) {
                                                    $corClasse = 'text-green-600 font-bold';
                                                } else {
                                                    $corClasse = 'text-gray-900';
                                                }
                                                @endphp
                                                <td class="border border-gray-300 p-1 text-center">-</td>
                                                <td class="border border-gray-300 p-1 bg-yellow-50 text-center">
                                                    <span class="text-xs font-medium {{ $corClasse }}">
                                                        {{ $resultadoAB }}
                                                    </span>
                                                </td>
                                            @endfor
                                        </tr>
                                        @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <div class="text-sm text-gray-600">
                            <div><strong>Criado em:</strong> {{ $survey->created_at->format('d/m/Y H:i') }}</div>
                            <div><strong>Última atualização:</strong> {{ $survey->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-modal name="add-fuel-load" :show="false" focusable>
        <form method="POST" action="{{ route('surveys.add-fuel-load', $survey) }}" class="p-6">
            @csrf

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Adicionar Carga de Combustível
            </h2>

            <div class="mt-4">
                <x-input-label for="fuel_id" :value="__('Combustível')" class="dark:text-gray-300" />
                <select
                    id="fuel_id"
                    name="fuel_id"
                    class="block mt-1 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 border-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    required>
                    <option value="">Selecione o combustível</option>
                    @foreach($survey->station->fuels as $fuel)
                    <option value="{{ $fuel->id }}">{{ $fuel->name }} ({{ $fuel->capacity }}LT)</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('fuel_id')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="day" :value="__('Dia')" class="dark:text-gray-300" />
                <select
                    id="day"
                    name="day"
                    class="block mt-1 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 border-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    required>
                    <option value="">Selecione o dia</option>
                    @for($day = 1; $day <= $survey->days_in_month; $day++)
                        <option value="{{ $day }}">{{ $day }}</option>
                        @endfor
                </select>
                <x-input-error :messages="$errors->get('day')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="load_amount" :value="__('Quantidade da Carga (LT)')" class="dark:text-gray-300" />
                <x-text-input id="load_amount" class="block mt-1 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" type="number" name="load_amount" step="1" min="1" required />
                <x-input-error :messages="$errors->get('load_amount')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="notes" :value="__('Observações (opcional)')" class="dark:text-gray-300" />
                <textarea
                    id="notes"
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
                    Adicionar Carga
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <style>
        .overflow-x-auto {
            scrollbar-width: thin;
        }

        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f7fafc;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 4px;
        }

        .sticky {
            position: sticky;
        }
    </style>
</x-app-layout>