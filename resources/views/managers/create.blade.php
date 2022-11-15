@extends('layouts.app')

@section('content')

    {{ Breadcrumbs::render('create_manager') }}

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 class="h2">{{__('Create manager')}}</h1>
    </div>

    {!! Form::open(['route' => ['store_manager'], 'method' => 'post', 'class' => 'needs-validation']) !!}

        <div class="form-group">
            {{Form::label('manager-lastname', __('Last name'))}}

            {{Form::text('last_name', null,['class' => 'form-control', 'id' => 'manager-lastname', 'required' => true])}}

            <div class="invalid-feedback">{{__('Fill in the field')}}</div>
        </div>

        <div class="form-group">
            {{Form::label('manager-firstname', __('First name'))}}

            {{Form::text('first_name', null, ['class' => 'form-control', 'id' => 'manager-firstname', 'required' => true])}}

            <div class="invalid-feedback">{{__('Fill in the field')}}</div>
        </div>

        <div class="form-group">
            {{Form::label('manager-middlename', __('Middle name'))}}

            {{Form::text('second_name', null, ['class' => 'form-control', 'id' => 'manager-middlename', 'required' => true])}}

            <div class="invalid-feedback">{{__('Fill in the field')}}</div>
        </div>

        <div class="form-group">
            {{Form::label('manager-email', __('E-mail'))}}

            {{Form::email('email', null, ['class' => 'form-control', 'id' => 'manager-email', 'required' => true])}}

            <div class="invalid-feedback">{{__('Fill in the field')}}</div>
        </div>

        {{Form::submit(__('Save'), ['class' => 'btn btn-dark'])}}

    {!! Form::close() !!}

@endsection
