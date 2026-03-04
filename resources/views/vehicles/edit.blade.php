<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('vehicles.show', $vehicle) }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Veículo') }} - {{ $vehicle->formatted_placa }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Vehicle Form --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Dados do Veículo</h3>
                    @include('vehicles.partials.form', ['vehicle' => $vehicle, 'action' => route('vehicles.update', $vehicle), 'method' => 'PUT'])
                </div>
            </div>

            {{-- Images Section --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Imagens do Veículo</h3>
                    
                    {{-- Upload Form --}}
                    <form method="POST" action="{{ route('vehicles.images.store', $vehicle) }}" enctype="multipart/form-data" class="mb-6">
                        @csrf
                        <div class="flex items-end gap-4">
                            <div class="flex-1">
                                <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Adicionar Imagens</label>
                                <input type="file" name="images[]" id="images" multiple accept="image/*"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <p class="mt-1 text-xs text-gray-500">JPEG, PNG, GIF, WebP. Máx. 2MB cada.</p>
                            </div>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Upload
                            </button>
                        </div>
                        @error('images')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('images.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </form>

                    {{-- Images Grid --}}
                    @if($vehicle->images->isEmpty())
                        <p class="text-gray-500 text-center py-8">Nenhuma imagem cadastrada.</p>
                    @else
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($vehicle->images as $image)
                                <div class="relative group aspect-square bg-gray-200 rounded-lg overflow-hidden">
                                    <img src="{{ $image->url }}" alt="Imagem do veículo" class="w-full h-full object-cover">
                                    
                                    {{-- Cover Badge --}}
                                    @if($image->is_cover)
                                        <span class="absolute top-2 left-2 px-2 py-1 bg-indigo-600 text-white text-xs font-medium rounded">
                                            Capa
                                        </span>
                                    @endif

                                    {{-- Actions Overlay --}}
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                        @if(!$image->is_cover)
                                            <form method="POST" action="{{ route('vehicles.images.cover', [$vehicle, $image]) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="p-2 bg-white rounded-full text-gray-700 hover:bg-indigo-100" title="Definir como capa">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('vehicles.images.destroy', [$vehicle, $image]) }}" 
                                              onsubmit="return confirm('Tem certeza que deseja excluir esta imagem?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-white rounded-full text-red-600 hover:bg-red-100" title="Excluir">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
