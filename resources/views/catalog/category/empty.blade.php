@inject('catalog', 'App\Http\Controllers\CatalogController')

@extends('layouts.app')

@section('content')

    {{ Breadcrumbs::render('catalog') }}

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 class="h2">{{__('site_labels.products')}}</h1>
    </div>
    <div class="table-responsive">
        @foreach($subcategory as $category)
            <div>
                <div class="img">
                    <a href="{{ route('catalog_inner', ['path' => $category->path]) }}">
                        <img src="{{ URL::to('/') }}/{{ $category->thumb }}" alt="{{ $category->title }}">
                    </a>
                </div>
                <div class="name"> <a href="{{ route('catalog_inner', ['path' => $category->path]) }}">{{$category->title}}</a></div>
            </div>
        @endforeach

    </div>

    <style>
        .content {
            width: calc(100% - 400px);
            float:left;
        }

        .sidebar {
            width: 400px;
            padding-top: 75px;
            float: left;
        }
        .sidebar ul {
            padding-left: 0;
            list-style: none;
        }

        .sidebar ul ul {
            padding-left: 10px;
        }
        .glyphicon-plus:before {
            content: "\2b";
        }
        .glyphicon-minus:before {
            content: "\2212";
        }
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

@section('sidebar')
    <div class="sidebar treeview" id="treeview">
        <p><b>Каталог:</b></p>

        {!! $catalog->showMenu() !!}
    </div>
@endsection
