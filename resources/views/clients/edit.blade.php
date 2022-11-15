@extends('layouts.app')

@section('content')

    {{ Breadcrumbs::render('edit_client', $client) }}

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 class="h2">{{__('Edit client')}}</h1>
    </div>

    {!! Form::open(['route' => ['update_client', $client->id], 'method' => 'post', 'class' => 'needs-validation']) !!}

        <div class="form-group">
            {{Form::label('manager-lastname', __('Last name'))}}

            {{Form::text('last_name', $client->last_name, ['class' => 'form-control', 'id' => 'manager-lastname', 'required' => true])}}

            <div class="invalid-feedback">{{__('Fill in the field')}}</div>
        </div>

        <div class="form-group">
            {{Form::label('manager-firstname', __('First name'))}}

            {{Form::text('first_name', $client->first_name, ['class' => 'form-control', 'id' => 'manager-firstname', 'required' => true])}}

            <div class="invalid-feedback">{{__('Fill in the field')}}</div>
        </div>

        <div class="form-group">
            {{Form::label('manager-middlename', __('Middle name'))}}

            {{Form::text('second_name', $client->second_name, ['class' => 'form-control', 'id' => 'manager-middlename', 'required' => true])}}

            <div class="invalid-feedback">{{__('Fill in the field')}}</div>
        </div>

        <div class="form-group">
            {{Form::label('manager-email', __('E-mail'))}}

            {{Form::email('email', $client->email, ['class' => 'form-control', 'id' => 'manager-email', 'required' => true])}}

            <div class="invalid-feedback">{{__('Fill in the field')}}</div>
        </div>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#categoriesModal">
            Exclude categories
        </button>

        {{Form::submit(__('Update'), ['class' => 'btn btn-dark'])}}

    {!! Form::close() !!}

    <!-- Modal -->
    <div class="modal fade" id="categoriesModal" tabindex="-1" role="dialog" aria-labelledby="categoriesModal" aria-hidden="true" data-client-id="{{ $client->id }}">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Exclude categories</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('clients.partials.category', compact('client', 'categories'))
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary js-exclude-categories">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
