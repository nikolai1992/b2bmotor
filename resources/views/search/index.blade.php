@extends('layouts.app')

@section('content')

    {{ Breadcrumbs::render('catalog') }}

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 class="h2">{{__('Products')}}</h1>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="">
            <tr>
                <th class="align-middle">Название</th>
                <th class="align-middle">Краткое название</th>
                <th class="align-middle">Артикул</th>
                <th class="align-middle">Категория</th>
                <th width="125" class="align-middle">Цена</th>
                <th width="75"></th>
            </tr>
            </thead>
            <tbody class="js-update-filter">
                @include('search.partials.products', ['products' => $products])
            </tbody>
        </table>
    </div>

    <style>
        .js-sort-link:after {
            content: '';
            width: 5px;
            margin-left: 5px;
            display: inline-block;
        }
        .js-sort-link.active:after {
            content: "\02193";
        }
        .js-sort-link.desc:after {
            content: "\02191";
        }
    </style>

@endsection
