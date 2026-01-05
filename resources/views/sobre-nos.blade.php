<x-app-layout>
 <x-page-header 
    title="Sobre nós"
    :breadcrumbs="[
        ['label' => 'Sobre nós']
    ]"
    />
<div class="bg-white rounded-md mb-6">
    <div class="bg-white shadow-md rounded-lg p-8">
        <h1 class="text-3xl font-bold text-blue-700 mb-4">Sobre Nós</h1>

        <p class="text-lg text-gray-700 mb-4">
            Somos uma organização da sociedade civil sem fins lucrativos, de caráter assistencial, filantrópico. Acolhemos pacientes renais de todo o estado que precisam de estadia na capital durante seu tratamento, de forma gratuita.

            Todos os seus colaboradores são voluntários

            A APREPI é mantida através de doações.
        </p>

        <p class="text-gray-700 mb-4">
            A associação fica localizada em Teresina, na Rua Rui Barbosa, s/n, próximo ao estádio Verdão, no Centro da capital. Nosso maior desafio é alcançar de maneira satisfatória a um número maior de necessitados.
        </p>

        

        <h2 class="text-2xl font-semibold text-blue-600 mt-6 mb-2">Nossos Objetivos</h2>
        <p class="text-gray-700 mb-4">
            <ul class="list-disc list-inside text-gray-700 space-y-1">

           <li>Congregar e prestar assistência aos pacientes renais em tratamento dialítico ou transplantado renal de todo o estado do Piuaí.</li>

            <li>Interceder junto ao Ministério da Saúde, à Secretaria do Estado da Saúde (Sesapi), secretarias municipais de saúde, serviços de diálise, centros transplantadores e outros órgãos públicos ou privados, visando a assegurar a todos os necessitados o tratamento e fornecimento de medicamento com regularidade, qualidade e segurança;</li>

            <li>Defender administrativamente e/ou judicialmente os direitos e interesses dos pacientes renais, todo e qualquer órgão;</li>

            <li>Fiscalizar, isolada ou conjuntamente com a vigilância sanitária e os demais gestores da saúde, o funcionamento dos serviços de diálise e dos centros transplantadores, objetivando a melhoria desses serviços;</li>

            <li>Colaborar com o Sistema Nacional de Transplantes e seus órgãos gestores representados pelas Centrais de Notificação, Captação Distribuição de Órgãos – CNCDOs e demais órgãos afins;</li>

            <li>Incentivar a criação de associações congêneres municipais, onde houver serviço de diálise e o acompanhamento de transplantados;</li>

            <li>Firmar convênios e outros instrumentos jurídicos com entidades congêneres ou não, pessoas jurídicas de direito público ou privado, visando a obter recursos materiais, humanos e financeiros para o atendimento dos objetivos da associação;</li>
    </ul>
        </p>

        <h2 class="text-2xl font-semibold text-blue-600 mt-6 mb-2">Nossos Valores</h2>
        <ul class="list-disc list-inside text-gray-700 space-y-1">
            <li>Empatia e solidariedade</li>
            <li>Compromisso social</li>
            <li>Ética e transparência</li>
            <li>Respeito à vida e à dignidade humana</li>
            <li>Excelência no atendimento</li>
        </ul>

        <h2 class="text-2xl font-semibold text-blue-600 mt-6 mb-2">Entre em Contato</h2>
        <p class="text-gray-700">
            Se você deseja saber mais sobre nosso trabalho, contribuir ou se voluntariar, não hesite em <a href="{{ route('contato') }}" class="text-blue-700 hover:underline">entrar em contato</a> conosco.
        </p>
    </div>
</div>
</x-app-layout>
