@extends('layouts.app')

@section('content')

    {{ Breadcrumbs::render('edit_order', $order) }}

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 class="h2">{{ __('Edit order') }}</h1>
    </div>

    {!! Form::open(['route' => ['update_order', $order->id], 'method' => 'post', 'class' => 'needs-validation']) !!}

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th class="align-middle">Article</th>
                    <th class="align-middle">Name</th>
                    <th class="align-middle">Price</th>
                    <th class="align-middle">qty</th>
                    <th class="align-middle">Total</th>
                    <th></th>
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
                            {{Form::hidden('product_id[]', $product->id)}}
                            {{Form::number('product_quantity[]', $product->pivot->qty, ['min' => 1, 'step' => 1, 'class' => 'form-control', 'required' => true])}}
                        </td>
                        <td class="align-middle">{{ $product->price * $product->pivot->qty }}</td>
                        <td><a class="card-link" href="{{ route('remove_product_from_order', [$order, $product]) }}">Remove</a></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"></td>
                    <td>{{ __('Total:') }}</td>
                    <td>{{ $totalQty }}</td>
                    <td>{{ $totalCost }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        {{Form::submit(__('Update'), ['class' => 'btn btn-dark'])}}

    {!! Form::close() !!}

@endsection
