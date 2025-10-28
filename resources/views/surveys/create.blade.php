<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Criar Registo
        </h2>
    </x-slot>
    <div class="py-12" x-data="surveyForm()" x-init="window.surveyFormInstance = $data">
        <div class="sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">

                    <form action="{{ route('surveys.store') }}" method="POST" @submit="prepareFormData()">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Posto</label>
                                <select x-model="form.station_id" @change="loadStationFuels()"
                                    name="station_id" required
                                    class="w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Selecionar posto...</option>
                                    @foreach($stations as $station)
                                    <option value="{{ $station->id }}">{{ $station->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Mês</label>
                                <select x-model="form.month" @change="updateDaysInMonth()"
                                    name="month" required
                                    class="w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Selecionar mês...</option>
                                    <option value="1">Janeiro</option>
                                    <option value="2">Fevereiro</option>
                                    <option value="3">Março</option>
                                    <option value="4">Abril</option>
                                    <option value="5">Maio</option>
                                    <option value="6">Junho</option>
                                    <option value="7">Julho</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Setembro</option>
                                    <option value="10">Outubro</option>
                                    <option value="11">Novembro</option>
                                    <option value="12">Dezembro</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ano</label>
                                <input type="number" x-model="form.year" @input="updateDaysInMonth()"
                                    name="year" required
                                    min="2020" max="2030"
                                    class="w-full rounded-md border-gray-300 shadow-sm">
                            </div>

                            <input type="hidden" name="days_in_month" :value="form.days_in_month">
                        </div>

                        <div x-show="showTable">
                            <div class="flex justify-between items-center mb-4">
                            </div>
                            <div class="table-container">
                                <div x-html="generateTable()"></div>
                            </div>
                        </div>

                        <input type="hidden" name="readings" x-ref="readingsInput">

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('surveys.index') }}" class="mr-3 underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
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
        window.fuelColors = {
            'G.Simples': 'background-color: #000; color: #fff;',
            'G.Aditivado': 'background-color: #fbbf24; color: #000;',
            'Agrícola Verde': 'background-color: #bbf7d0; color: #000;',
            'Gasolina 95': 'background-color: #16a34a; color: #fff;',
            'Gasolina 95 ADT': 'background-color: #61A316; color: #fff;',
            'Gasolina 98': 'background-color: #a3e635; color: #000;',
            'AdBlue': 'background-color: #3b82f6; color: #fff;',
            'GPL': 'background-color: #f68f3b; color: #000;'
        };

        window.getFuelStyle = function(fuelName) {
            let fuelStyle = '';
            
            for (const [name, style] of Object.entries(window.fuelColors)) {
                if (fuelName.includes(name)) {
                    fuelStyle = style;
                    break;
                }
            }
            
            return fuelStyle || 'background-color: #e5e7eb; color: #000;';
        };

        function surveyForm() {
            return {
                form: {
                    station_id: '',
                    month: '',
                    year: new Date().getFullYear(),
                    days_in_month: 30,
                    readings: {}
                },
                fuels: [],
                selectedStationName: '',

                get canSubmit() {
                    return this.form.station_id && this.form.month && this.form.year && this.fuels.length > 0;
                },

                get showTable() {
                    return this.fuels.length > 0 && this.form.days_in_month > 0 && Object.keys(this.form.readings).length > 0;
                },

                async loadStationFuels() {
                    if (!this.form.station_id) {
                        this.fuels = [];
                        this.selectedStationName = '';
                        this.form.readings = {};
                        return;
                    }

                    const stationSelect = document.querySelector('select[name="station_id"]');
                    this.selectedStationName = stationSelect.options[stationSelect.selectedIndex].text;

                    try {
                        const response = await fetch(`/api/stations/${this.form.station_id}/fuels`);
                        if (response.ok) {
                            this.fuels = await response.json();
                            this.initializeReadings();
                        } else {
                            alert('Erro ao carregar combustíveis');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Erro de rede');
                    }
                },

                initializeReadings() {
                    this.form.readings = {};
                    
                    this.fuels.forEach(fuel => {
                        this.form.readings[fuel.id] = {
                            counters: [],
                            sounding: {
                                values: {},
                                totals: {}
                            },
                            stock_entries: {
                                values: {},
                                totals: {}
                            }
                        };

                        for (let i = 0; i < fuel.counter; i++) {
                            this.form.readings[fuel.id].counters[i] = {
                                values: {},
                                totals: {}
                            };
                        }
                    });
                },

                updateDaysInMonth() {
                    if (this.form.month && this.form.year) {
                        this.form.days_in_month = new Date(this.form.year, this.form.month, 0).getDate();
                    }
                },

                generateTable() {
                    if (!this.showTable) return '';

                    let html = '<table class="min-w-full border-collapse">';
                    
                    html += '<thead class="bg-gray-100">';
                    html += '<tr>';
                    html += '<th class="border border-gray-300 px-3 py-2 text-sm font-medium text-left sticky left-0 bg-gray-100 z-10" style="min-width: 200px;" rowspan="2"></th>';
                    for (let day = 1; day <= this.form.days_in_month; day++) {
                        html += `<th class="border border-gray-300 px-1 py-1 text-xs text-center" style="min-width: 120px;" colspan="2">${day}</th>`;
                    }
                    html += '</tr>';
                    html += '<tr>';
                    for (let day = 1; day <= this.form.days_in_month; day++) {
                        html += '<th class="border border-gray-300 px-1 py-1 text-xs">Valores</th>';
                        html += '<th class="border border-gray-300 px-1 py-1 text-xs">Total</th>';
                    }
                    html += '</tr>';
                    html += '</thead>';

                    html += '<tbody>';
                    this.fuels.forEach(fuel => {
                        html += '<tr class="bg-gray-200">';
                        html += `<td class="border border-gray-300 px-3 py-2 font-semibold sticky left-0 z-10" style="${getFuelStyle(fuel.name)}">${fuel.name} ${fuel.capacity}LT</td>`;
                        for (let day = 1; day <= this.form.days_in_month; day++) {
                            html += `<td style="${getFuelStyle(fuel.name)}" colspan="2"></td>`;
                        }
                        html += '</tr>';

                        for (let counter = 1; counter <= fuel.counter; counter++) {
                            html += '<tr>';
                            html += `<td class="border border-gray-300 px-3 py-2 bg-gray-50 sticky left-0 bg-gray-50 z-10">Contador ${counter}</td>`;
                            for (let day = 1; day <= this.form.days_in_month; day++) {
                                const inputId = `counter_${fuel.id}_${counter-1}_${day}`;
                                html += '<td class="border border-gray-300 p-0">';
                                html += `<input type="number" id="${inputId}" `;
                                html += 'class="w-full text-xs p-1 text-center border-0 focus:ring-1 focus:ring-blue-500" ';
                                html += 'style="min-width: 58px; height: 30px; background: white;" ';
                                html += `step="1" min="0" placeholder="0" oninput="window.updateCounterValue('${fuel.id}', ${counter-1}, ${day}, this.value)">`;
                                html += '</td>';
                                html += `<td class="border border-gray-300 p-1 bg-gray-50 text-center">`;
                                html += `<span class="text-xs" id="total_counter_${fuel.id}_${counter-1}_${day}">0</span>`;
                                html += '</td>';
                            }
                            html += '</tr>';
                        }

                        html += '<tr class="bg-green-100">';
                        html += '<td class="border border-gray-300 px-3 py-2 font-semibold sticky left-0 bg-green-100 z-10">Litros Vendidos</td>';
                        for (let day = 1; day <= this.form.days_in_month; day++) {
                            html += '<td class="border border-gray-300 p-1 text-center">-</td>';
                            html += `<td class="border border-gray-300 p-1 bg-green-50 text-center">`;
                            html += `<span class="text-xs font-medium" id="litros_vendidos_${fuel.id}_${day}">0</span>`;
                            html += '</td>';
                        }
                        html += '</tr>';

                        html += '<tr class="bg-gray-400">';
                        html += '<td class="border border-gray-300 px-3 py-2 font-semibold sticky left-0 bg-gray-400 z-10">Sondagem</td>';
                        for (let day = 1; day <= this.form.days_in_month; day++) {
                            const inputId = `sounding_${fuel.id}_${day}`;
                            html += '<td class="border border-gray-300 p-0">';
                            html += `<input type="number" id="${inputId}" `;
                            html += 'class="w-full text-xs p-1 text-center border-0 focus:ring-1 focus:ring-blue-500" ';
                            html += 'style="min-width: 58px; height: 30px; background: white;" ';
                            html += `step="1" min="0" placeholder="0" oninput="window.updateSoundingValue('${fuel.id}', ${day}, this.value)">`;
                            html += '</td>';
                            html += `<td class="border border-gray-300 p-1 bg-gray-300 text-center">`;
                            html += `<span class="text-xs" id="total_sounding_${fuel.id}_${day}">0</span>`;
                            html += '</td>';
                        }
                        html += '</tr>';

                        html += '<tr class="bg-yellow-100">';
                        html += '<td class="border border-gray-300 px-3 py-2 font-semibold sticky left-0 bg-yellow-100 z-10">Resultado A-B</td>';
                        for (let day = 1; day <= this.form.days_in_month; day++) {
                            html += '<td class="border border-gray-300 p-1 text-center">-</td>';
                            html += `<td class="border border-gray-300 p-1 bg-yellow-50 text-center">`;
                            html += `<span class="text-xs font-medium" id="resultado_ab_${fuel.id}_${day}">0</span>`;
                            html += '</td>';
                        }
                        html += '</tr>';
                    });
                    html += '</tbody>';
                    html += '</table>';

                    return html;
                },

                calculateCounterTotal(fuelId, counterIndex, day) {
                    if (!this.form.readings[fuelId] || !this.form.readings[fuelId].counters[counterIndex]) {
                        console.log('Counter not found:', fuelId, counterIndex);
                        return;
                    }
                    
                    const counter = this.form.readings[fuelId].counters[counterIndex];
                    const currentValue = parseInt(counter.values[day] || 0);
                    
                    if (day > 1) {
                        const previousValue = parseInt(counter.values[day - 1] || 0);
                        counter.totals[day] = currentValue - previousValue;
                    } else {
                        counter.totals[day] = currentValue;
                    }

                    const totalSpan = document.getElementById(`total_counter_${fuelId}_${counterIndex}_${day}`);
                    if (totalSpan) {
                        totalSpan.textContent = counter.totals[day];
                    }

                    this.calculateLitrosVendidos(fuelId, day);
                },

                calculateSoundingTotal(fuelId, day) {
                    if (!this.form.readings[fuelId]) {
                        console.log('Sounding not found:', fuelId);
                        return;
                    }
                    
                    const sounding = this.form.readings[fuelId].sounding;
                    const currentValue = parseInt(sounding.values[day] || 0);
                    
                    let total = 0;
                    if (day > 1) {
                        const previousValue = parseInt(sounding.values[day - 1] || 0);
                        total = previousValue - currentValue;
                    } else {
                        total = currentValue;
                    }
                    
                    if (day > 1 && this.form.readings[fuelId].stock_entries && 
                        this.form.readings[fuelId].stock_entries.values) {
                        const previousDayStock = parseInt(this.form.readings[fuelId].stock_entries.values[day - 1] || 0);
                        if (previousDayStock > 0) {
                            total += previousDayStock;
                        }
                    }
                    
                    sounding.totals[day] = total;

                    const totalSpan = document.getElementById(`total_sounding_${fuelId}_${day}`);
                    if (totalSpan) {
                        totalSpan.textContent = sounding.totals[day];
                    }

                    this.calculateResultadoAB(fuelId, day);
                },

                calculateLitrosVendidos(fuelId, day) {
                    if (!this.form.readings[fuelId]) return;

                    let totalLitrosVendidos = 0;
                    const fuel = this.fuels.find(f => f.id == fuelId);
                    
                    if (fuel && this.form.readings[fuelId].counters) {
                        for (let i = 0; i < fuel.counter; i++) {
                            const counterTotal = parseInt(this.form.readings[fuelId].counters[i].totals[day] || 0);
                            totalLitrosVendidos += Math.abs(counterTotal);
                        }
                    }

                    const litrosSpan = document.getElementById(`litros_vendidos_${fuelId}_${day}`);
                    if (litrosSpan) {
                        litrosSpan.textContent = totalLitrosVendidos;
                    }

                    this.calculateResultadoAB(fuelId, day);
                },

                calculateResultadoAB(fuelId, day) {
                    const litrosSpan = document.getElementById(`litros_vendidos_${fuelId}_${day}`);
                    const sondagemTotalSpan = document.getElementById(`total_sounding_${fuelId}_${day}`);
                    const resultadoSpan = document.getElementById(`resultado_ab_${fuelId}_${day}`);

                    if (litrosSpan && sondagemTotalSpan && resultadoSpan) {
                        const litrosVendidos = parseInt(litrosSpan.textContent || 0);
                        const sondagemTotal = parseInt(sondagemTotalSpan.textContent || 0);
                        
                        const resultado = litrosVendidos - Math.abs(sondagemTotal);
                        
                        resultadoSpan.textContent = resultado;
                        
                        resultadoSpan.classList.remove('text-red-600', 'text-green-600', 'text-gray-900');
                        if (resultado < 0) {
                            resultadoSpan.classList.add('text-red-600', 'font-bold');
                        } else if (resultado > 0) {
                            resultadoSpan.classList.add('text-green-600', 'font-bold');
                        } else {
                            resultadoSpan.classList.add('text-gray-900');
                        }
                    }
                },

                prepareFormData() {
                    this.fuels.forEach(fuel => {
                        for (let counter = 0; counter < fuel.counter; counter++) {
                            for (let day = 1; day <= this.form.days_in_month; day++) {
                                const input = document.getElementById(`counter_${fuel.id}_${counter}_${day}`);
                                if (input && input.value.trim() !== '') {
                                    this.form.readings[fuel.id].counters[counter].values[day] = input.value;
                                }
                            }
                        }
                        
                        for (let day = 1; day <= this.form.days_in_month; day++) {
                            const input = document.getElementById(`sounding_${fuel.id}_${day}`);
                            if (input && input.value.trim() !== '') {
                                this.form.readings[fuel.id].sounding.values[day] = input.value;
                            }
                        }
                    });

                    this.$refs.readingsInput.value = JSON.stringify(this.form.readings);
                }
            }
        }

        window.updateCounterValue = function(fuelId, counterIndex, day, value) {
            if (!window.surveyFormInstance) {
                console.error('Survey form instance not found');
                return;
            }
            
            const form = window.surveyFormInstance;
            
            if (!form.form.readings[fuelId] || !form.form.readings[fuelId].counters[counterIndex]) {
                console.error('Counter structure not found');
                return;
            }
            
            form.form.readings[fuelId].counters[counterIndex].values[day] = value;
            form.calculateCounterTotal(fuelId, counterIndex, day);
        };

        window.updateSoundingValue = function(fuelId, day, value) {
            if (!window.surveyFormInstance) {
                console.error('Survey form instance not found');
                return;
            }
            
            const form = window.surveyFormInstance;
            
            if (!form.form.readings[fuelId]) {
                console.error('Sounding structure not found');
                return;
            }
            
            form.form.readings[fuelId].sounding.values[day] = value;
            form.calculateSoundingTotal(fuelId, day);
        };
    </script>

    <style>
        .overflow-x-auto {
            scrollbar-width: thin;
        }

        table input[type="number"] {
            background-color: white !important;
            border: 1px solid #e5e5e5;
        }

        table input[type="number"]:focus {
            border-color: #3b82f6;
            outline: none;
        }

        .sticky {
            position: sticky;
        }

        thead {
            position: sticky;
            top: 0;
            z-index: 20;
        }

        thead th {
            background-color: #f3f4f6 !important;
            position: sticky;
            top: 0;
            z-index: 15;
        }

        .sticky.left-0 {
            z-index: 25 !important;
        }

        .table-container {
            max-height: 70vh;
            overflow-y: auto;
            overflow-x: auto;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
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
    </style>
</x-app-layout>