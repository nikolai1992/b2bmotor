@extends('layouts.app')

@section('content')

    {{ Breadcrumbs::render('show_order', $order) }}

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 class="h2">{{__('order.title')}}</h1>
    </div>

    <table class="table table-striped table-hover">
        <thead class="">
            <tr>
                <th class="align-middle">@lang('site_labels.article')</th>
                <th class="align-middle">@lang('site_labels.name_product')</th>
                <th class="align-middle">@lang('site_labels.price')</th>
                <th class="align-middle">@lang('site_labels.Qty')</th>
                <th class="align-middle">@lang('cart.total')</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalQty = 0;
                $totalCost = 0;

            @endphp
            @foreach($order->products as $product)
                @php
                       $totalQty += $product->pivot->qty;
                       $totalCost += $product->productPrice->price * $product->pivot->qty;
                @endphp
                <tr>
                    <td class="align-middle">{{ $product->article }}</td>
                    <td class="align-middle">{{ $product->title }}</td>
                    <td class="align-middle">{{ $product->productPrice->price }}</td>
                    <td>
                        {{ $product->pivot->qty }}
                    </td>
                    <td class="align-middle">{{ $product->productPrice->price * $product->pivot->qty }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="2"></td>
            <td>@lang('cart.total'): </td>
            <td>{{ $totalQty }}</td>
            <td>{{ $totalCost }}</td>
        </tr>
        </tfoot>
    </table>

@endsection
