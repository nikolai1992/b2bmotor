@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="cart">
            <h1>{{ __('cart.title') }}</h1>

            <div class="cart-block">
                @if($isEmpty)
                    <div class="form-group align-items-center">
                        {{ __('cart.empty') }}
                    </div>
                @else
                    {!! Form::open(['route' => 'store_order', 'method' => 'post']) !!}
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>@lang('site_labels.name')</th>
                                    <th>@lang('site_labels.Qty')</th>
                                    <th>@lang('site_labels.price')</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        {{Form::hidden('rowId', $item->rowId, ['class' => 'js-cart-rowId'])}}
                                        {{Form::hidden('product_id[]', $item->id)}}

                                        <td><span class="product-name">{{ $item->name }}</span></td>

                                        <td>{{Form::number('product_quantity[]', $item->qty, ['min' => 1, 'step' => 1, 'class' => 'js-cart-update-qty', 'style' => 'width: 50px', 'required' => true])}}</td>

                                        <td><span class="price">{{ App\Currency::getPrice(str_replace(",", "", $item->price)) }} {{ App\Services\CurrencyService::getCurrentCurrency()->alias }}</span></td>

                                        <td>
                                            <a class="btn btn-primary" href="{{ route('remove_from_cart', ['rowId' => $item->rowId]) }}">{{ __('cart.remove_btn') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td><b>{{ __('cart.total') }}</b>:</td>
                                    <td>qty</td>
                                    <td>
                                        <span class="price">
                                            <span class="js-cart-total">
                                                {{ App\Currency::getPrice(str_replace(",", "", $total)) }}
                                            </span>
                                            {{ App\Services\CurrencyService::getCurrentCurrency()->alias }}
                                        </span>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="form-group" style="float: right">
                            {{Form::hidden('cost', $total)}}

                            <div class="custom-control custom-checkbox">
                                {{Form::checkbox('сash_payment', null, false, ['class' => 'custom-control-input', 'id' => 'сash-payment'])}}
                                {{Form::label('сash-payment', __('cart.payment'), ['class' => 'custom-control-label'])}}
                            </div>
                            <br>
                            <a class="btn btn-primary" href="{{ route('clear_cart') }}">{{ __('cart.clear_btn') }}</a>

                            {{Form::submit(__('cart.order'), ['class' => 'btn btn-primary btn-dark'])}}
                        </div>
                    {!! Form::close() !!}
                @endif
            </div>
        </div>
    </div>

@endsection
