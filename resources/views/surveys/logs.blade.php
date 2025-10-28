{{-- resources/views/surveys/logs.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Histórico de Alterações | {{ $survey->station->name }} | {{ $survey->getMonthYearLabel() }}
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

                    @if($logs->count() > 0)
                    <div class="space-y-4">
                        @foreach($logs as $log)
                        <div class="border border-gray-200 rounded-lg p-4 {{ $log->action === 'created' ? 'bg-green-50' : ($log->action === 'deleted' ? 'bg-red-50' : 'bg-blue-50') }}">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $log->action === 'created' ? 'bg-green-100 text-green-800' : ($log->action === 'deleted' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ $log->action_label }}
                                    </span>
                                    <span class="text-sm font-medium text-gray-900">{{ $log->user->name }}</span>
                                </div>
                                <span class="text-sm text-gray-500">{{ $log->created_at->format('d/m/Y H:i:s') }}</span>
                            </div>

                            @if($log->description)
                            <p class="text-sm text-gray-700 mb-2">{{ $log->description }}</p>
                            @endif

                            @if($log->action === 'updated' && $log->formatted_changes)
                            <div class="mt-3">
                                <h4 class="text-sm font-medium text-gray-800 mb-2">Alterações:</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-xs">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="px-2 py-1 text-left">Campo</th>
                                                <th class="px-2 py-1 text-left">Valor Anterior</th>
                                                <th class="px-2 py-1 text-left">Valor Novo</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            @foreach($log->formatted_changes as $change)
                                            <tr>
                                                <td class="px-2 py-1 font-medium">{{ $change['field'] }}</td>
                                                <td class="px-2 py-1 text-red-600">
                                                    @if(is_array($change['old']))
                                                    <span class="text-xs">Dados complexos alterados</span>
                                                    @else
                                                    {{ $change['old'] }}
                                                    @endif
                                                </td>
                                                <td class="px-2 py-1 text-green-600">
                                                    @if(is_array($change['new']))
                                                    <span class="text-xs">Dados complexos alterados</span>
                                                    @else
                                                    {{ $change['new'] }}
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif

                            <div class="mt-2 text-xs text-gray-500">
                                IP: {{ $log->ip_address }}
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $logs->links() }}
                    </div>
                    @else
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-6 text-center">
                        <p class="text-gray-500 dark:text-gray-400">Não existem logs registados para este registo.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>