<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('vehicles.index') }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $vehicle->marca }} {{ $vehicle->modelo }} - {{ $vehicle->formatted_placa }}
                </h2>
            </div>
            @can('update', $vehicle)
                <div class="flex items-center gap-2">
                    <a href="{{ route('vehicles.edit', $vehicle) }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </a>
                    <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}" 
                          onsubmit="return confirm('Tem certeza que deseja excluir este veículo?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Excluir
                        </button>
                    </form>
                </div>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Gallery --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Galeria de Imagens</h3>
                        
                        @if($vehicle->images->isEmpty())
                            <div class="aspect-video bg-gray-200 rounded-lg flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="mx-auto w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">Nenhuma imagem cadastrada</p>
                                </div>
                            </div>
                        @else
                            {{-- Main Image --}}
                            <div class="aspect-video bg-gray-200 rounded-lg overflow-hidden mb-4" id="main-image-container">
                                <img src="{{ $vehicle->coverImage?->url ?? $vehicle->images->first()->url }}" 
                                     alt="{{ $vehicle->marca }} {{ $vehicle->modelo }}"
                                     class="w-full h-full object-cover"
                                     id="main-image">
                            </div>

                            {{-- Thumbnails --}}
                            @if($vehicle->images->count() > 1)
                                <div class="grid grid-cols-6 gap-2">
                                    @foreach($vehicle->images as $image)
                                        <button type="button" 
                                                onclick="document.getElementById('main-image').src = '{{ $image->url }}'"
                                                class="aspect-square bg-gray-200 rounded overflow-hidden hover:ring-2 hover:ring-indigo-500 focus:ring-2 focus:ring-indigo-500 {{ $image->is_cover ? 'ring-2 ring-indigo-500' : '' }}">
                                            <img src="{{ $image->url }}" alt="" class="w-full h-full object-cover">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- Vehicle Details --}}
                <div class="space-y-6">
                    {{-- Basic Info --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Veículo</h3>
                            
                            <div class="mb-4">
                                <span class="text-3xl font-bold text-indigo-600">{{ $vehicle->formatted_valor_venda }}</span>
                            </div>

                            <dl class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Placa</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $vehicle->formatted_placa }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Chassi</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $vehicle->chassi }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Marca</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->marca }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Modelo</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->modelo }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Versão</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->versao }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Cor</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->cor }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Quilometragem</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->formatted_km }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Câmbio</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->cambio->label() }}</dd>
                                </div>
                                <div class="col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Combustível</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->combustivel->label() }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    {{-- Audit Info --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informações de Auditoria</h3>
                            
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Proprietário</dt>
                                    <dd class="text-sm text-gray-900">{{ $vehicle->user->name }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Criado por</dt>
                                    <dd class="text-sm text-gray-900">{{ $vehicle->creator?->name ?? 'N/A' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Data de criação</dt>
                                    <dd class="text-sm text-gray-900">{{ $vehicle->created_at->format('d/m/Y H:i') }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Última atualização por</dt>
                                    <dd class="text-sm text-gray-900">{{ $vehicle->updater?->name ?? 'N/A' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Data da última atualização</dt>
                                    <dd class="text-sm text-gray-900">{{ $vehicle->updated_at->format('d/m/Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
