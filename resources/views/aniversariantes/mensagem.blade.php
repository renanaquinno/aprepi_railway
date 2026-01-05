<x-app-layout>
    <x-page-header 
        title="Mensagem de Aniversário"
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Aniversariantes', 'url' => route('aniversariantes.index')], ['label' => 'Mensagem']]"
    />

    <div class="py-2">
        <div class="bg-white rounded-md mb-6 p-4">
            <form action="{{ route('aniversariantes.mensagem.update') }}" method="POST">
    @csrf
    <label for="mensagem" class="block text-sm font-medium text-gray-700 mb-2">
        Mensagem Padrão
    </label>

    <textarea id="mensagem" name="mensagem" rows="10" 
              class="w-full border rounded-md p-2">{{ $mensagem->mensagem }}</textarea>

  <div class="flex justify-end gap-4 mt-6">
    <x-secondary-button onclick="window.location='{{ route('aniversariantes.index') }}'">
        Voltar
    </x-secondary-button>

    <x-primary-button type="submit" class="bg-sky-800 hover:bg-sky-900">
        Salvar Mensagem
    </x-primary-button>
</div>

</form>

        <!-- TinyMCE -->
        <script src="https://cdn.tiny.cloud/1/nk3no2mqtqx19mq4ft0b8u7if7z1yxuuasxvtjp0x6y2qehn/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
        <script>
            tinymce.init({
                selector: '#mensagem',
                height: 350,
                menubar: false,
                plugins: 'advlist autolink lists link charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime table code help wordcount',
                toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | preview',
                language: 'pt_BR'
            });
        </script>
        </div>
    </div>
</x-app-layout>
