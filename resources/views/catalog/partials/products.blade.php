<div style="overflow-x: auto; overflow-y: auto;">
    <table class="table table-striped table-hover">
        <thead class="">
            <tr>
                <th class="align-middle" width="125">Зображення</th>
                <th class="align-middle">
                    <a href="#" class="js-sort-link" data-sort="title" data-dir="asc">@lang('site_labels.name_product')</a>
                </th>
                <th width="100" class="align-middle">
                    <a href="#" class="js-sort-link" data-sort="article" data-dir="asc">@lang('site_labels.article')</a>
                </th>
                {{--                <th width="100" class="align-middle">Категория</th>--}}
                {{--                <th width="100" class="align-middle">Бренд</th>--}}
                {{--                <th width="175" class="align-middle">--}}
                {{--                    <select id="brand-select" class="form-control js-select">--}}
                {{--                        <option value="0" selected>All brand</option>--}}
                {{--                        @foreach($brands as $brand)--}}
                {{--                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>--}}
                {{--                        @endforeach--}}
                {{--                    </select>--}}
                {{--                </th>--}}
                <th width="100" class="align-middle">
                    <a href="#" class="js-sort-link" data-sort="total_amount" data-dir="desc">@lang('site_labels.the_rest')</a>
                </th>
                <th width="250" class="align-middle">
                    <a href="#" class="js-sort-link" data-sort="total_price" data-dir="desc">@lang('site_labels.price')</a>
                </th>
                <th width="80" class="align-middle">@lang('site_labels.Qty')</th>
                <th width="80"></th>
            </tr>
            {{--<tr>--}}
                {{--<th class="align-middle" width="125"></th>--}}
                {{--<th class="align-middle">--}}
                    {{--<form method="GET" action="{{route('catalog_search_by_fields')}}" id="searching_fields" accept-charset="UTF-8">--}}
                        {{--<input class="form-control table-search-field" name="title" value="{{isset($title) ? $title : ''}}">--}}
                    {{--</form>--}}
                {{--</th>--}}
                {{--<th class="align-middle">--}}
                    {{--<input form="searching_fields" class="form-control table-search-field" name="short_title" value="{{isset($short_title) ? $short_title : ''}}">--}}
                {{--</th>--}}
                {{--<th width="100" class="align-middle">--}}
                    {{--<input form="searching_fields" class="form-control table-search-field" name="article" value="{{isset($article) ? $article : ''}}">--}}
                {{--</th>--}}
                {{--<th width="100" class="align-middle">--}}
                {{--</th>--}}
                {{--<th width="250" class="align-middle">--}}
                {{--</th>--}}
                {{--<th width="80" class="align-middle"></th>--}}
                {{--<th width="80"></th>--}}
            {{--</tr>--}}
        </thead>
    <tbody class="js-update-filter">
    @foreach($products as $product)
        <tr>
            <td class="align-middle"><img src="{{ getImageOrDefault($product->thumb) }}" alt="{{ $product->title }}" width="100"></td>
            <td class="align-middle"><a href="{{ route('show_product', ['slug' => $product->slug]) }}">{{ $product->title }}</a></td>
            <td class="align-middle">{{ $product->article }}</td>
            {{--<td class="align-middle">--}}
            {{--@foreach($product->categories as $category)--}}
            {{--{{ $category->title }}--}}
            {{--@endforeach--}}
            {{--</td>--}}
            {{--<td class="align-middle">{{ $product->brand->name }}</td>--}}
            {{--<td class="align-middle"><span class="js-cart-item-dealer-price">{{ $product->price }}</span> грн.</td>--}}
            {{--        <td class="align-middle">Категория</td>--}}
            {{--        <td class="align-middle">Бренд</td>--}}
            <td class="align-middle">
                @foreach($product->stores as $item)
                    {{$item->title}}: {{$item->amount}}<br>
                @endforeach

            </td>
            <td class="align-middle">
                @if(!auth()->user()->all_price)
                    @if($product->price_type_title && $product->price)
                        <input type="hidden" class="js-cart-item-price" value="{{ App\Currency::getPrice($product->price) }}">
                        <?php
                        $titl = $product->price_type_title;
                        if($titl == "Дилерская") {
                            $titl = "Дилерська";
                        }
                        if($titl == "Розничная") {
                            $titl = "Роздрібна";
                        }
                        ?>
                        {{ $titl }} : {{ App\Currency::getPrice($product->price) }}({{App\Services\CurrencyService::getCurrentCurrency()->alias}})
                        {{--@if($product->productPriceRetail)--}}
                        {{--{{ $product->productPriceRetail->priceType->title}} : {{ App\Currency::convertCurrency($product->productPriceRetail->price) }}({{App\Services\CurrencyService::getCurrentCurrency()->alias}})<br>--}}
                        {{--@endif--}}
                        {{--@if($product->productPriceOpt)--}}
                        {{--{{ $product->productPriceOpt->priceType->title}} : {{ App\Currency::convertCurrency($product->productPriceOpt->price) }}({{App\Services\CurrencyService::getCurrentCurrency()->alias}})<br>--}}
                        {{--@endif--}}
                    @else
                        <input type="hidden" class="js-cart-item-price" value="0">
                    @endif
                @else
                    <input type="hidden" class="js-cart-item-price" value="{{$product->findSmallestPrice()}}">
                    @foreach($product->prices as $price)
                        <?php
                        $titl = $price->priceType->title;
                        if($titl == "Дилерская") {
                            $titl = "Дилерська";
                        }
                        if($titl == "Розничная") {
                            $titl = "Роздрібна";
                        }
                        ?>
                        {{ $titl }}: {{ App\Currency::getPrice($price->price) }} {{ App\Services\CurrencyService::getCurrentCurrency()->alias }}<br>
                    @endforeach
                @endif
                {{--********************************************************--}}

            </td>
            {{--<td class="align-middle">--}}
                {{--            @foreach($product->warehouse as $item)--}}
                {{--                @if ($item->availability > 0)--}}
                {{--                    @if ($item->warehouse)--}}
                {{--                        {{ $item->warehouse->name }}--}}
                {{--                    @endif--}}
                {{--                    {{ $item->availability }}<br>--}}
                {{--                @endif--}}
                {{--            @endforeach--}}
            {{--</td>--}}
            <td class="align-middle"><input type="number" style="width: 74px;" class="form-control js-cart-item-quantity" value="1" min="1" step="1"></td>
            <td class="align-middle">
                <div class="d-flex justify-content-end">
                    <a href="{{ route('add_to_cart', ['product' => $product->id]) }}" class="btn btn-primary btn-block js-cart-add">{{ __('cart.Add') }}</a>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="row">
    <div class="col-md-8">
        {{ $products->appends(Request::except('page'))->links() }}
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="paginationElementsCount">@lang('site_labels.number_of_items')</label>
            <select class="form-control" id="paginationElementsCount">
                <option {{Session::get('pagination_items_count') == '10' ? "selected" : '' }} value="10">10</option>
                <option {{Session::get('pagination_items_count') == '25' ? "selected" : '' }} value="25">25</option>
                <option {{Session::get('pagination_items_count') == '50' ? "selected" : '' }} value="50">50</option>
                <option {{Session::get('pagination_items_count') == '75' ? "selected" : '' }} value="75">75</option>
                <option {{Session::get('pagination_items_count') == '100' ? "selected" : '' }} value="100">100</option>
            </select>
        </div>
    </div>
</div>


