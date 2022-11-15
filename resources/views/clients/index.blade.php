@extends('layouts.app')

@section('content')

    {{ Breadcrumbs::render('list_clients') }}

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 class="h2">{{__('Clients')}}</h1>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>Id</th>
                <th>ФИО</th>
                <th>E-mail</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                    <tr>
                        <td>{{$client->id}}</td>
                        <td>{{$client->getFullName()}}</td>
                        <td>{{$client->email}}</td>
                        <td>
                            <div class="d-flex justify-content-end">
                                @can('update-client', $client)
                                    <a href="{{ route('edit_client', ['id' => $client->id]) }}" class="card-link">редактировать</a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
