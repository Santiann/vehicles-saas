@php
    use App\Enums\Cambio;
    use App\Enums\Combustivel;
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    {{-- Identification Section --}}
    <div>
        <h4 class="text-md font-medium text-gray-700 mb-3 pb-2 border-b">Identificação</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Placa --}}
            <div>
                <label for="placa" class="block text-sm font-medium text-gray-700 mb-1">
                    Placa <span class="text-red-500">*</span>
                </label>
                <input type="text" name="placa" id="placa" 
                       value="{{ old('placa', $vehicle?->placa) }}"
                       placeholder="ABC1D23"
                       maxlength="7"
                       class="w-full uppercase rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('placa') border-red-500 @enderror">
                @error('placa')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Formato Mercosul: ABC1D23</p>
            </div>

            {{-- Chassi --}}
            <div>
                <label for="chassi" class="block text-sm font-medium text-gray-700 mb-1">
                    Chassi <span class="text-red-500">*</span>
                </label>
                <input type="text" name="chassi" id="chassi" 
                       value="{{ old('chassi', $vehicle?->chassi) }}"
                       placeholder="9BWZZZ377VT004251"
                       maxlength="17"
                       class="w-full uppercase rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('chassi') border-red-500 @enderror">
                @error('chassi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">17 caracteres alfanuméricos (sem I, O, Q)</p>
            </div>
        </div>
    </div>

    {{-- Vehicle Info Section --}}
    <div>
        <h4 class="text-md font-medium text-gray-700 mb-3 pb-2 border-b">Dados do Veículo</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Marca --}}
            <div>
                <label for="marca" class="block text-sm font-medium text-gray-700 mb-1">
                    Marca <span class="text-red-500">*</span>
                </label>
                <input type="text" name="marca" id="marca" 
                       value="{{ old('marca', $vehicle?->marca) }}"
                       placeholder="Toyota"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('marca') border-red-500 @enderror">
                @error('marca')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Modelo --}}
            <div>
                <label for="modelo" class="block text-sm font-medium text-gray-700 mb-1">
                    Modelo <span class="text-red-500">*</span>
                </label>
                <input type="text" name="modelo" id="modelo" 
                       value="{{ old('modelo', $vehicle?->modelo) }}"
                       placeholder="Corolla"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('modelo') border-red-500 @enderror">
                @error('modelo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Versão --}}
            <div>
                <label for="versao" class="block text-sm font-medium text-gray-700 mb-1">
                    Versão <span class="text-red-500">*</span>
                </label>
                <input type="text" name="versao" id="versao" 
                       value="{{ old('versao', $vehicle?->versao) }}"
                       placeholder="XEi 2.0"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('versao') border-red-500 @enderror">
                @error('versao')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Characteristics Section --}}
    <div>
        <h4 class="text-md font-medium text-gray-700 mb-3 pb-2 border-b">Características</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Cor --}}
            <div>
                <label for="cor" class="block text-sm font-medium text-gray-700 mb-1">
                    Cor <span class="text-red-500">*</span>
                </label>
                <input type="text" name="cor" id="cor" 
                       value="{{ old('cor', $vehicle?->cor) }}"
                       placeholder="Prata"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('cor') border-red-500 @enderror">
                @error('cor')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- KM --}}
            <div>
                <label for="km" class="block text-sm font-medium text-gray-700 mb-1">
                    Quilometragem <span class="text-red-500">*</span>
                </label>
                <input type="number" name="km" id="km" 
                       value="{{ old('km', $vehicle?->km ?? 0) }}"
                       min="0"
                       placeholder="50000"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('km') border-red-500 @enderror">
                @error('km')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Câmbio --}}
            <div>
                <label for="cambio" class="block text-sm font-medium text-gray-700 mb-1">
                    Câmbio <span class="text-red-500">*</span>
                </label>
                <select name="cambio" id="cambio" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('cambio') border-red-500 @enderror">
                    <option value="">Selecione...</option>
                    @foreach(Cambio::options() as $option)
                        <option value="{{ $option['value'] }}" 
                                {{ old('cambio', $vehicle?->cambio?->value) === $option['value'] ? 'selected' : '' }}>
                            {{ $option['label'] }}
                        </option>
                    @endforeach
                </select>
                @error('cambio')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Combustível --}}
            <div>
                <label for="combustivel" class="block text-sm font-medium text-gray-700 mb-1">
                    Combustível <span class="text-red-500">*</span>
                </label>
                <select name="combustivel" id="combustivel" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('combustivel') border-red-500 @enderror">
                    <option value="">Selecione...</option>
                    @foreach(Combustivel::options() as $option)
                        <option value="{{ $option['value'] }}" 
                                {{ old('combustivel', $vehicle?->combustivel?->value) === $option['value'] ? 'selected' : '' }}>
                            {{ $option['label'] }}
                        </option>
                    @endforeach
                </select>
                @error('combustivel')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Price Section --}}
    <div>
        <h4 class="text-md font-medium text-gray-700 mb-3 pb-2 border-b">Valor</h4>
        <div class="max-w-xs">
            <label for="valor_venda" class="block text-sm font-medium text-gray-700 mb-1">
                Valor de Venda (R$) <span class="text-red-500">*</span>
            </label>
            <input type="number" name="valor_venda" id="valor_venda" 
                   value="{{ old('valor_venda', $vehicle?->valor_venda) }}"
                   min="0.01"
                   step="0.01"
                   placeholder="75000.00"
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('valor_venda') border-red-500 @enderror">
            @error('valor_venda')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Submit Button --}}
    <div class="flex justify-end gap-4 pt-4 border-t">
        <a href="{{ $vehicle ? route('vehicles.show', $vehicle) : route('vehicles.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Cancelar
        </a>
        <button type="submit" 
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ $vehicle ? 'Atualizar' : 'Cadastrar' }}
        </button>
    </div>
</form>
