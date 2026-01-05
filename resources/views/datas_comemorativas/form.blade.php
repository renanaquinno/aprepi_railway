<x-app-layout>
    
    <x-page-header 
        title="Gerenciamento de Datas Comemorativas"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Datas Comemorativas', 'url' => route('datas_comemorativas.index')],
            ['label' => isset($data) ? 'Editar' : 'Nova']
        ]"
    />

    <div class="py-2">
        <div class="bg-white shadow-md rounded-lg p-8">
            <form method="POST" 
                  action="{{ isset($data) ? route('datas_comemorativas.update', $data->id) : route('datas_comemorativas.store') }}" 
                  enctype="multipart/form-data">
                @csrf
                @if(isset($data))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Título --}}
                    <div>
                        <x-input-label for="titulo" value="Título" />
                        <x-text-input type="text" name="titulo" id="titulo"
                            value="{{ old('titulo', $data->titulo ?? '') }}"
                            class="w-full" required />
                       
                        <x-input-error :messages="$errors->get('titulo')" class="mt-2" />
                    </div>

                    {{-- Data --}}
                    <div>
                        <x-input-label for="data" value="Data" />
                        <x-text-input type="date" name="data" id="data"
                            value="{{ old('data', isset($data->data) ? \Carbon\Carbon::parse($data->data)->format('Y-m-d') : '') }}"
                            class="w-full" required />
                        <x-input-error :messages="$errors->get('data')" class="mt-2" />
                    </div>

                    {{-- Mensagem --}}
                    <div class="md:col-span-2">
                        <x-input-label for="mensagem" value="Mensagem" />
                        <textarea name="mensagem" id="mensagem" rows="8" class="w-full border-gray-300 rounded shadow-sm focus:border-blue-500 focus:ring-blue-500">{!! old('mensagem', $data->mensagem ?? '') !!}</textarea>
                        <x-input-error :messages="$errors->get('mensagem')" class="mt-2" />
                    </div>

                </div>

                {{-- Botões --}}
                <div class="flex justify-end gap-4 mt-6">
                    <x-secondary-button type="button" onclick="window.location='{{ route('datas_comemorativas.index') }}'">
                        Cancelar
                    </x-secondary-button>

                    <x-primary-button class="bg-sky-800 hover:bg-sky-900">
                        {{ isset($data) ? 'Atualizar' : 'Cadastrar' }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    {{-- Scripts TinyMCE --}}
    @push('scripts')
<script src="https://cdn.tiny.cloud/1/nk3no2mqtqx19mq4ft0b8u7if7z1yxuuasxvtjp0x6y2qehn/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
        <script>
            tinymce.init({
                selector: '#mensagem',
                plugins: 'lists link image code table media paste',
                toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image media | code',
                menubar: false,
                height: 350,
                branding: false,
                language: 'pt_BR',
                relative_urls: false,
                remove_script_host: false,
                document_base_url: "{{ url('/') }}/",
                /* Se quiser upload via rota, eu já posso adicionar images_upload_handler abaixo */
            });
        </script>
    @endpush

</x-app-layout>
