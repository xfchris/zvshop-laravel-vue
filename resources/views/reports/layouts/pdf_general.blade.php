@extends('layouts.pdf_reports')

@section('title', 'General Report')

@section('content')
    <h3 style="text-align:center">(from: {{ $start_date }}, to: {{ $end_date }})</h3>

    <table class="tableUsers mt-6">
        <tr>
            <td class="w-50">Total sales approved:</td>
            <td class="w-50">@money($totalPricePaymentApproved)</td>
        </tr>
        <tr>
            <td>Quantity of products sold:</td>
            <td>{{ $totalPaymentApproved }}</td>
        </tr>
        <tr>
            <td>Total sales canceled:</td>
            <td>@money($totalPricePaymentRejected)</td>
        </tr>
        <tr>
            <td>Quantity of rejected products:</td>
            <td>{{ $totalPaymentRejected }}</td>
        </tr>
    </table>
    <br>
    <br>

    <h2 style="text-align:center">List of users who have purchased</h2>

    <table class="tableUsers">
        <thead class="table-dark">
            <tr>
                <th scope="col" style="width:30%">Name</th>
                <th scope="col" style="width:30%">Surname</th>
                <th scope="col" style="width:30%">Email</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($userBuyers as $buyers)
                <tr>
                    <td>{{ $buyers->user->name }}</td>
                    <td>{{ $buyers->user->surname }}</td>
                    <td>{{ $buyers->user->email }}</td>
                </tr>
            @empty
            <tr>
                <td class="center" colspan="3">Empty</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection
