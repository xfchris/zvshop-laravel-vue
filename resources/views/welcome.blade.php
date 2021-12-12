<x-app-layout>
    <x-slot name="header">

        <h2 class="h4 font-weight-bold">
            {{ __('Principal') }}
            <num-cart num="20" />
        </h2>

    </x-slot>

    <div class="card my-4">

        <btn-add-cart />

    </div>
</x-app-layout>
