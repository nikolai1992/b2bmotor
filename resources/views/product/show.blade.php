@extends('layouts.app')

@section('content')

    {{ Breadcrumbs::render('product', ['slug' => $product->slug]) }}

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 class="h2">{{ $product->title }}</h1>
    </div>

    <div class="row">
        <div class="col-md-4">
{{--            @if($product->gallery)--}}
{{--                @foreach($product->gallery as $image)--}}
                    <img src="{{ getImageOrDefault($product->thumb) }}" alt="{{ $product->title }}" width="100%">
{{--                @endforeach--}}
{{--            @endif--}}

        </div>
        <div class="col-md-8">
            <p><b>@lang('site_labels.article'):</b> {{ $product->article }}</p>
            @if($product->cat_page)
                <p><a href="{{$product->cat_page}}" target="_blank">@lang('site_labels.catalog_link')</a></p>
            @endif

            @foreach($product->files->sortBy('file_name') as $file)
                <a href="{{ Storage::url((new App\Services\Product\ProductServiceImpl)->checkFileExists($file->url)) }}" target="_blank">
                    @php
                        $arr = explode("/", $file->url);
                        echo array_pop($arr);
                    @endphp
                </a>
               <br>
            @endforeach
            {{--<p><b>Price:</b> {{ $product->price }}</p>--}}
            {{--<p><b>Brand:</b> {{ $product->brand->name }}</p>--}}
        </div>
    </div>

@endsection
