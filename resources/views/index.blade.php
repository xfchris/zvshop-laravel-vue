<x-app-layout>
    <x-slot name="header">

        <h2 class="h4 font-weight-bold mb-0">
            {{ __('Principal') }}
        </h2>

    </x-slot>

    <main class="px-3 text-center">
        <h1>Compra lo que quieras al 50% de descuento!</h1>

        <img alt="products" class="rounded mx-auto d-block mw-400px my-5"
            src="https://s.libertaddigital.com/2021/05/27/1920/1080/fit/algunos-productos-del-surtido-sin-gluten-de-mercadona-1.jpg" />

        <p class="lead">Con zvchop puedes comprar cualquier producto con el 50% de descuento, sin intermediarios,
            directamente con el proveedor
            <br />Registrate facil y comienza a usar este novedoso servicio
        </p>
        <p class="lead">
            <a href="{{ route('register') }}"
                class="btn btn-lg btn-success font-weight-bold border-white">Registrarse</a>
        </p>
    </main>

</x-app-layout>
