<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" style="padding: 3px;">
    <a class="navbar-brand" href="{{ url('/') }}"><img style="width: 118px; margin-left: 20px;" src="{{asset('/images/_Motorimpex_logo.png')}}"></a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">

            @can('see-managers-tab')
                <li class="nav-item">
                    <a href="{{ route('list_managers') }}" class="nav-link {{ request()->is('managers*') ? 'active' : '' }}">{{__('menu.managers')}}</a>
                </li>
            @endcan

            @can('see-clients-tab')
                <li class="nav-item">
                    <a href="{{ route('list_clients') }}" class="nav-link {{ request()->is('clients*') ? 'active' : '' }}">{{__('menu.clients')}}</a>
                </li>
            @endcan

            <li class="nav-item nav-item-dropdown">
                <a href="{{ route('catalog') }}" class="nav-link {{ request()->is('catalog*') ? 'active' : '' }}">{{__('menu.catalog')}}</a>
            </li>

            @can('see-orders-tab')
                <li class="nav-item">
                    <a href="{{ route('list_orders') }}" class="nav-link {{ request()->is('orders*') ? 'active' : '' }}">{{__('menu.orders')}}</a>
                </li>
            @endcan

        </ul>

        {!! Form::open(['url' => route('catalog'), 'method' => 'get', 'class' => 'form-inline mr-sm-5']) !!}
            {{--<input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">--}}
            <input class="form-control mr-sm-2" required="" name="query" type="search" value="{{$search ?? ''}}">
            {{--{{Form::submit(__('Search'), ['class' => 'btn btn-outline-info my-2 my-sm-0'])}}--}}
        {!! Form::close() !!}

        <div class="mr-sm-5">
            <a href="{{ route('show_cart') }}">@lang('cart.title'): <span class="cart-product-count">{{ Cart::count() }}</span> @lang('cart.products')</a>
        </div>
        @if(isset($currencies))
            <div class="mr-sm-5" style="margin-top: 12px;">
                <label for="currency" style="color: #fff">Валюта</label>
                <select id="currency">
                    @foreach($currencies as $currency)
                        <option {{Session::get("current_currency") == $currency->id ? "selected" : ""}} value="{{$currency->id}}">{{$currency->name}}</option>
                    @endforeach
                </select>
            </div>
        @endif
        @if(auth()->user())
            <div class="mr-sm-5" style="margin-top: 4px;">
                <form action="{{route('change_prices_tax_status')}}" method="get">
                    <select id="nds" name="tax_price_status">
                        <option {{auth()->user()->price_tax_status == "without_tax" ? "selected" : ""}} value="without_tax">@lang('site_labels.without_tax')</option>
                        <option {{auth()->user()->price_tax_status == "with_tax" ? "selected" : ""}} value="with_tax">@lang('site_labels.with_tax')</option>
                    </select>
                </form>
            </div>

            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" style="font-size: 10px;" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->last_name }} {{ Auth::user()->first_name }}<br> <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('profile') }}">{{ __('site_labels.profile') }}</a>

                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('site_labels.log_out') }}
                        </a>

                        {!! Form::open(['route' => 'logout', 'method' => 'post', 'id' => 'logout-form', 'style' => 'display: none']) !!}
                        {!! Form::close() !!}
                    </div>
                </li>
            </ul>
        @endif
    </div>
</nav>
