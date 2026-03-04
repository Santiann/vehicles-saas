<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Veículos') }}
            </h2>
            <a href="{{ route('vehicles.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Novo Veículo
            </a>
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

            {{-- Filters --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('vehicles.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                            {{-- Global Search --}}
                            <div class="lg:col-span-2">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Busca</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                       placeholder="Placa, marca, modelo..."
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            {{-- Marca Filter --}}
                            <div>
                                <label for="marca" class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                                <select name="marca" id="marca" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Todas</option>
                                    @foreach($marcas as $marca)
                                        <option value="{{ $marca }}" {{ request('marca') === $marca ? 'selected' : '' }}>
                                            {{ $marca }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Modelo Filter --}}
                            <div>
                                <label for="modelo" class="block text-sm font-medium text-gray-700 mb-1">Modelo</label>
                                <select name="modelo" id="modelo" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Todos</option>
                                    @foreach($modelos as $modelo)
                                        <option value="{{ $modelo }}" {{ request('modelo') === $modelo ? 'selected' : '' }}>
                                            {{ $modelo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Order By --}}
                            <div>
                                <label for="order_by" class="block text-sm font-medium text-gray-700 mb-1">Ordenar por</label>
                                <div class="flex gap-2">
                                    <select name="order_by" id="order_by" 
                                            class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="created_at" {{ request('order_by', 'created_at') === 'created_at' ? 'selected' : '' }}>Data</option>
                                        <option value="valor_venda" {{ request('order_by') === 'valor_venda' ? 'selected' : '' }}>Valor</option>
                                        <option value="km" {{ request('order_by') === 'km' ? 'selected' : '' }}>KM</option>
                                        <option value="marca" {{ request('order_by') === 'marca' ? 'selected' : '' }}>Marca</option>
                                    </select>
                                    <select name="order_direction" 
                                            class="w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="asc" {{ request('order_direction') === 'asc' ? 'selected' : '' }}>↑</option>
                                        <option value="desc" {{ request('order_direction', 'desc') === 'desc' ? 'selected' : '' }}>↓</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Filtrar
                            </button>
                            @if(request()->hasAny(['search', 'marca', 'modelo', 'placa', 'order_by']))
                                <a href="{{ route('vehicles.index') }}" 
                                   class="text-sm text-gray-600 hover:text-gray-900 underline">
                                    Limpar filtros
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Vehicles Grid --}}
            @if($vehicles->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">Nenhum veículo encontrado</h3>
                        <p class="mt-1 text-sm text-gray-500">Comece criando um novo veículo.</p>
                        <div class="mt-6">
                            <a href="{{ route('vehicles.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Novo Veículo
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($vehicles as $vehicle)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-200">
                            <a href="{{ route('vehicles.show', $vehicle) }}" class="block">
                                {{-- Cover Image --}}
                                <div class="aspect-video bg-gray-200 relative overflow-hidden">
                                    @if($vehicle->coverImage)
                                        <img src="{{ $vehicle->coverImage->url }}" 
                                             alt="{{ $vehicle->marca }} {{ $vehicle->modelo }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="absolute top-2 right-2 px-2 py-1 bg-black/60 rounded text-white text-xs font-medium">
                                        {{ $vehicle->formatted_placa }}
                                    </div>
                                </div>

                                {{-- Vehicle Info --}}
                                <div class="p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h3 class="font-semibold text-gray-900">{{ $vehicle->marca }}</h3>
                                            <p class="text-sm text-gray-600">{{ $vehicle->modelo }} {{ $vehicle->versao }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $vehicle->formatted_km }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $vehicle->cambio->label() }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $vehicle->combustivel->label() }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-bold text-indigo-600">{{ $vehicle->formatted_valor_venda }}</span>
                                        <span class="text-xs text-gray-500">{{ $vehicle->cor }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $vehicles->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
