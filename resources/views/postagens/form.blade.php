<x-app-layout>
    {{-- Erros de validação --}}
    @if ($errors->any())
        <div class="mb-4">
            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <x-page-header 
        title="{{ isset($postagem) ? 'Editar Postagem' : 'Novo Postagem' }}"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Blog', 'url' => route('postagens.admin.index')],
            ['label' => isset($postagem) ? 'Editar' : 'Novo']
        ]"
    />

    <div class="py-2">
        <div class="bg-white shadow-md rounded-lg p-8">
            <form method="POST" 
                  action="{{ isset($postagem) ? route('postagens.update', $postagem) : route('postagens.store') }}"
                  enctype="multipart/form-data">  
                @csrf
                @if(isset($postagem))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- Título --}}
                    <div class="md:col-span-2">
                        <x-input-label value="Título" />
                        <x-text-input class="w-full" name="titulo" 
                            value="{{ old('titulo', $postagem->titulo ?? '') }}" required />
                    </div>

                    {{-- Categoria fixa --}}
                    <div>
                        <x-input-label value="Categoria" />
                        <select name="categoria" class="w-full border-gray-300 rounded" required>
                            <option value="">Selecione...</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat }}" 
                                    {{ old('categoria', $postagem->categoria ?? '') == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    @if(isset($postagem) && $postagem->publicado_em)
                        <div>
                            <x-input-label value="Publicado em" />
                            <x-text-input class="w-full" value="{{ $postagem->publicado_em->format('d/m/Y H:i') }}" readonly />
                        </div>
                    @endif


                    {{-- Status --}}
                    <div>
                        <x-input-label value="Status" />
                        <select name="status" class="w-full border-gray-300 rounded">
                            <option value="rascunho" {{ old('status', $postagem->status ?? '') == 'rascunho' ? 'selected' : '' }}>Rascunho</option>
                            <option value="publicado" {{ old('status', $postagem->status ?? '') == 'publicado' ? 'selected' : '' }}>Publicado</option>
                        </select>
                    </div>


                    {{-- Conteúdo --}}
                    <div class="md:col-span-2">
                        <x-input-label value="Conteúdo" />
                        <textarea id="editor" name="conteudo" rows="8" class="w-full border-gray-300 rounded" required>
                            {{ old('conteudo', $postagem->conteudo ?? '') }}
                        </textarea>
                    </div>
                </div>

                {{-- Botões --}}
                <div class="flex justify-end gap-4 mt-6">
                    <x-secondary-button onclick="window.location='{{ route('postagens.admin.index') }}'">
                        Cancelar
                    </x-secondary-button>
                    
                    <x-primary-button type="submit" class="bg-sky-700 hover:bg-sky-900">
                        {{ isset($postagem) ? 'Atualizar' : 'Publicar' }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    
<script src="https://cdn.tiny.cloud/1/nk3no2mqtqx19mq4ft0b8u7if7z1yxuuasxvtjp0x6y2qehn/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>

    <script>
        tinymce.init({
            selector: '#editor',
            plugins: 'lists link image table code preview',
            toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | code preview | table',
            menubar: false,
            height: 700,
            branding: false,
            language: 'pt_BR', // opcional
            content_style: 'body { font-family:Arial,Helvetica,sans-serif; font-size:14px }',
            // Upload automático
            automatic_uploads: true,
            images_upload_url: "{{ route('postagens.upload.imagem') }}",
            
            // Envia CSRF Token junto
            images_upload_handler: function (blobInfo, progress) {
                return new Promise((resolve, reject) => {
                    let xhr = new XMLHttpRequest();
                    xhr.open('POST', "{{ route('postagens.upload.imagem') }}");
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                    xhr.onload = function() {
                        if (xhr.status < 200 || xhr.status >= 300) {
                            reject('Erro HTTP: ' + xhr.status);
                            return;
                        }
                        let json = JSON.parse(xhr.responseText);
                        if (!json.location) {
                            reject('Resposta inválida: ' + xhr.responseText);
                            return;
                        }
                        resolve(json.location);
                    };
                    let formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    xhr.send(formData);
                });
            }
            
        });
    </script>
    @endpush

</x-app-layout>
