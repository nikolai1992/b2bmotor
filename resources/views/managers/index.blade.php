@extends('layouts.app')

@section('content')

    {{ Breadcrumbs::render('list_managers') }}

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 class="h2">{{__('Managers')}}</h1>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>Id</th>
                <th>ФИО</th>
                <th>E-mail</th>
                <th>Clients</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                @foreach($managers as $manager)
                    <tr>
                        <td>{{$manager->id}}</td>
                        <td>{{$manager->getFullName()}}</td>
                        <td>{{$manager->email}}</td>
                        <td>
                            <a href="{{ route('manager_list_clients', ['id' => $manager->id]) }}" class="card-link">{{ __('show') }}</a>
                        </td>
                        <td>
                            <div class="d-flex justify-content-end">
                                @can('update-manager', $manager)
                                    <a href="{{ route('edit_manager', ['id' => $manager->id]) }}" class="card-link">{{ __('edit') }}</a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @can('create-manager')
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
            <div class="btn-toolbar mb-2 mb-md-0 ml-auto">
                <a href="{{ route('create_manager') }}" class="btn btn-dark">{{ __('Create') }}</a>
            </div>
        </div>
    @endcan

@endsection
