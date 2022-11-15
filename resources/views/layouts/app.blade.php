@extends('layout')

@section('layout')
    @include('./layouts/partials/header')

    <div class="container-fluid" style="margin-top: 31px;">
        @section('sidebar')
        @show
        <main class="content" role="main">
            @yield('content')
        </main>

    </div>
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script>
        $('body').on('change', '#currency', function(e) {
            var id = $(this).val();
            $.ajax({
                type: "GET",
                url: '/change_currency/' + id,
                success: function(data) {
                    document.location.reload();
                }
            });
        });

        $('body').on('change', '#nds', function(e) {
            $(this).parent().submit();
        });
    </script>
    @yield('js')
@endsection

