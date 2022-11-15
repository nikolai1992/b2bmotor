@extends('layouts.app')

@section('content')

    {{ Breadcrumbs::render('clients_manager', $manager) }}

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 class="h2">{{__('Manager clients')}}</h1>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>Id</th>
                <th>ФИО</th>
                <th>E-mail</th>
            </tr>
            </thead>
            <tbody>
                @foreach($clientsList as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->client->getFullName() }}</td>
                        <td>{{ $item->client->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button class="btn btn-primary" data-toggle="modal" data-target="#modal-add-client">
            {{ __('Add clients') }}
        </button>
    </div>

    <!-- The Modal -->
    <div class="modal" id="modal-add-client">
        <div class="modal-dialog w-75" style="max-width: 100%;">
            <div class="modal-content">
                {!! Form::open(['route' => ['manager_add_clients'], 'method' => 'post', 'class' => 'needs-validation']) !!}
                    {{Form::hidden('manager_id', $manager->id)}}
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('Add clients') }}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Id</th>
                                <th>ФИО</th>
                                <th>E-mail</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($clients as $client)
                                    <tr>
                                        <td>
                                            {{Form::checkbox('clients[]', $client->id, false)}}
                                        </td>
                                        <td>{{ $client->id }}</td>
                                        <td>{{ $client->getFullName() }}</td>
                                        <td>{{ $client->email }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
                        {{Form::submit(__('Add'), ['class' => 'btn btn-primary'])}}
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
