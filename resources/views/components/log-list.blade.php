@props(['logs'])

@if(collect($logs)->isNotEmpty())
    <div class="bg-white rounded-md p-4 mt-6 border border-gray-200">
        <h2 class="text-2xl font-semibold mb-4 text-gray-800">Histórico de Alterações</h2>

        @php
            // Closure para traduzir os valores
            $friendlyValue = function($key, $val) {
                if ($key === 'user_id' || $key === 'origem') {
                    return \App\Models\User::find($val)?->name ?? $val;
                }
                if ($key === 'recorrente') {
                    return $val == 1 ? 'Sim' : 'Não';
                }
                // adicione outras traduções aqui se desejar
                return $val;
            };

            $fieldLabels = [
                'user_id' => 'Entregue Para',
                'data_entrega' => 'Data da Entrega',
                'status' => 'Status',
                'origem_id' => 'Origem',
                'recorrente' => 'Recorrente',
                // outros labels aqui
            ];
        @endphp

        <ul class="space-y-4">
            @foreach($logs as $log)
                <li class="border-l-4 border-yellow-400 bg-gray-50 p-4 rounded-md shadow-sm">
                    <div class="flex justify-between items-center mb-1">
                        <div class="text-sm text-gray-600">
                            <span class="font-semibold text-blue-800">
                                {{ $log->causer?->name ?? 'Sistema' }}
                            </span>
                            atualizou em {{ $log->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    <div class="mt-2 text-sm text-gray-700 space-y-1">
                       @foreach($log->properties['attributes'] ?? [] as $key => $value)
                            @php
                                $label = $fieldLabels[$key] ?? ucfirst(str_replace('_', ' ', $key));
                                $oldRaw = $log->properties['old'][$key] ?? '—';

                                // Se for array, converte para string (separando por vírgula)
                                $oldValueRaw = is_array($oldRaw) ? implode(', ', $oldRaw) : $oldRaw;
                                $newValueRaw = is_array($value) ? implode(', ', $value) : $value;

                                $oldValue = $friendlyValue($key, $oldValueRaw);
                                $newValue = $friendlyValue($key, $newValueRaw);
                            @endphp

                            <div class="flex flex-col md:flex-row md:items-center md:gap-2">
                                <span class="font-medium">{{ $label }}:</span>
                                <span class="text-red-600 line-through">{{ $oldValue }}</span>
                                <span class="text-gray-500">→</span>
                                <span class="text-green-700">{{ $newValue }}</span>
                            </div>
                        @endforeach

                        
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endif