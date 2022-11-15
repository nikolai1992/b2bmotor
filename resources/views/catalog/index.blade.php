@inject('catalog', 'App\Http\Controllers\CatalogController')

@extends('layouts.app')

@section('content')

    {{ Breadcrumbs::render('catalog') }}

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 class="h2">{{__('site_labels.products')}}</h1>
    </div>
    <div class="table-responsive">
        @include('catalog.partials.products', ['products' => $products])
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

@section('js')
    @parent
    <script>
        var sorting_field_name = "";
        var orderBy = "";

        $('body').on('input', '.table-search-field', function(e) {
            searchAction();
        });

        $('body').on('input', 'input[name="query"]', function(e) {
            console.log($(this).val());
            searchAction();
        });

        $('body').on('click', '.js-sort-link', function(e) {
            sorting_field_name = $(this).data('sort');
            orderBy = $(this).attr('data-dir');
            var _this = $(this);
            var next_orderBy = orderBy=="asc" ? "desc" : "asc";
            _this.attr("data-dir", next_orderBy);
            $('.js-sort-link.active').removeClass('active');
            $(this).addClass('active');

            searchAction();
        });

        function refreshData(data)
        {
            $('.js-update-filter').html($(data).find('.js-update-filter').html());
            var pagination = $(data).find('.pagination').html();

            $('.pagination').html(pagination ? pagination : "");
        }

        function searchAction() {
            var form = $('input[name="query"]').parent();
            var url = '{{url()->current()}}'; //get submit url [replace url here if desired]
            if ($('input[name="query"]').val()) {
                url = $('input[name="query"]').parent().attr("action");
            } else {
                url = url + "?sortBy=" + sorting_field_name + "&orderBy=" + orderBy;
            }
            console.log(form)
            console.log(url)
            $.ajax({
                type: "GET",
                url: url,
                data: form.serialize(), // serializes form input
                success: function(data) {
                    if (data) {
                        refreshData(data);
                    }
                }
            });
        }

        $('body').on('change', '#paginationElementsCount', function(e) {
            var count = $(this).val();
            $.ajax({
                type: "GET",
                url: '/change_pagination_items_count/' + count,
                success: function(data) {
                    document.location.reload();
                }
            });
        });
    </script>
@stop
