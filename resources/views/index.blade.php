<x-app-layout>
    <x-slot name="header">

        <h2 class="h4 font-weight-bold mb-0">
            {{ __('By: ZShop') }}
        </h2>

    </x-slot>

    <main class="px-3 text-center">
        <h1>Buy what you want at a 50% discount!</h1>

        <img alt="products" class="rounded mx-auto d-block mw-400px my-5"
            src="https://s.libertaddigital.com/2021/05/27/1920/1080/fit/algunos-productos-del-surtido-sin-gluten-de-mercadona-1.jpg" />

        <p class="lead">Here you can buy any product with a 50% discount, without intermediaries, directly with the
            supplier
            <br />Register, it's free and start using this new service!
        </p>
        <p class="lead">
            <a href="{{ route('register') }}" class="btn btn-lg btn-danger font-weight-bold border-danger text-light">
                <i class="fas fa-thumbs-up"></i>
                <br/>
                Register now!
            </a>
        </p>
    </main>

</x-app-layout>
