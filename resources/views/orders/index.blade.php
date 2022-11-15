@extends('layouts.app')

@section('content')

    {{ Breadcrumbs::render('list_orders') }}

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 class="h2">{{__('order.title')}}</h1>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>Id</th>
                <th>ФИО</th>
                <th>E-mail</th>
                <th>Статус</th>
                {{--<th>Кол-во товаров</th>--}}
                {{--<th>Стоимость</th>--}}
                <th></th>
            </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    {{--@php--}}
                        {{--$total = $order->total();--}}
                    {{--@endphp--}}
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->getFullName() }}</td>
                        <td>{{ $order->user->email }}</td>
                        <td class="status">{{ $order->getStatus() }}</td>
                        {{--<td>{{ $total->qty }}</td>--}}
                        {{--<td>{{ $total->cost }}</td>--}}
                        <td>
                            <a href="{{ route('show_order', ['order' => $order->id]) }}">{{ __('Show') }}</a>
                            <a href="{{ route('edit_order', ['order' => $order->id]) }}">{{ __('Edit') }}</a>
                            @if ($order->status != 3)
                                <a href="{{ route('cansele_order', ['order' => $order->id]) }}" class="btn btn-primary btn-block js-order-cansel">{{ __('Cancel') }}</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $orders->links() }}
    </div>

@endsection
