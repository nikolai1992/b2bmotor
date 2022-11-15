@foreach($products as $product)
    <tr>
        <td class="align-middle">{{ $product->title }}</td>
        <td class="align-middle">{{ $product->short_title }}</td>
        <td class="align-middle">{{ $product->article }}</td>
        <td class="align-middle">
            Категория
        </td>
        <td class="align-middle">
            @if($product->productPriceRetail)
                {{ $product->productPriceRetail->priceType->title}} : {{ $product->productPriceRetail->price }}<br>
            @endif
            @if($product->productPriceOpt)
                {{ $product->productPriceOpt->priceType->title}} : {{ $product->productPriceOpt->price }}<br>
            @endif
            <span class="js-cart-item-dealer-price">{{ $product->price }}</span> грн.</td>
        <td class="align-middle">
            <div class="d-flex justify-content-end">
                <a href="{{ route('add_to_cart', ['product' => $product->id]) }}" class="btn btn-primary btn-block js-cart-add">{{ __('Add') }}</a>
            </div>
        </td>
    </tr>
@endforeach
