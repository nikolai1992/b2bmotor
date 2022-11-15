@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 class="h2">{{__('site_labels.profile')}}</h1>
    </div>

    <h2>{{ __('site_labels.change_password') }}</h2>

    @if ($errors->all())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p class="yellow-text font lato-normal center">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {!! Form::open(['route' => ['update_profile'], 'method' => 'post', 'class' => 'needs-validation']) !!}

        <div class="form-group">
            {{Form::label('current-password', __('site_labels.current_password'))}}

            {{Form::password('current_password', null, ['class' => 'form-control', 'id' => 'current-password', 'required' => true])}}

            <div class="invalid-feedback">{{__('site_labels.fill_this_field')}}</div>
        </div>

        <div class="form-group">
            {{Form::label('password', __('site_labels.new_password'))}}

            {{Form::password('password', null, ['class' => 'form-control', 'id' => 'password', 'required' => true])}}

            <div class="invalid-feedback">{{__('site_labels.fill_this_field')}}</div>
        </div>

        <div class="form-group">
            {{Form::label('repeat-password', __('site_labels.reenter_pass'))}}

            {{Form::password('repeat_password', null, ['class' => 'form-control', 'id' => 'repeat-password', 'required' => true])}}

            <div class="invalid-feedback">{{__('site_labels.fill_this_field')}}</div>
        </div>

        {{Form::submit(__('site_labels.update'), ['class' => 'btn btn-dark'])}}

    {!! Form::close() !!}

@endsection
