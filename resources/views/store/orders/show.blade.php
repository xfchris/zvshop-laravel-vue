<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                {{ __('Online store') }}
            </h2>
            @include('store.products.components.head_right')
        </div>

    </x-slot>

    <div class="card my-4">
        <div class="card-body">

            Lista de productos

            {{ $order }}
        </div>
    </div>
</x-app-layout>
