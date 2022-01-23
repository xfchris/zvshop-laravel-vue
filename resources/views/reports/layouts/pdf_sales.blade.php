@extends('layouts.pdf_reports')

@section('title', 'Sales Report')

@section('content')
    <h3 class="center">(from: {{ $start_date }}, to: {{ $end_date }})</h3>
    <br>

    <h2 class="center">List of products sold</h2>

    <table class="tableUsers">
        <thead>
            <tr>
                <th scope="col" style="width:30%">Name</th>
                <th scope="col" style="width:30%">Category</th>
                <th scope="col" style="width:30%">Quantity</th>
                <th scope="col" style="width:30%">Total Price</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product['name'] }}</td>
                    <td>{{ $product['category']['name'] }}</td>
                    <td>{{ $product['quantity'] }}</td>
                    <td>@money($product['totalPrice'])</td>
                </tr>
            @empty
            <tr>
                <td class="center" colspan="4">Empty</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection
