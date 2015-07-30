@extends('layout.form')

@section('form')
    <h2 class="form-header">Reset wachtwoord</h2>
    @if(Session::has('errorMessage'))
        <div class="alert alert-danger" role="alert">
            <strong>{{ucfirst(trans('educal.errors'))}}</strong>
            <ul>
                <li>{{ Session::get('errorMessage') }}</li>
            </ul>
        </div>
    @endif
    {{Form::open(array('route' => array('user.resetPassword', $hash), 'class'=>'form form-horizontal', 'method' => 'post')) }}

    @if($errors->count())
        <div class="alert alert-danger" role="alert">
            <strong>{{ucfirst(trans('educal.errors'))}}</strong>
            <ul>
                @foreach ($errors->all() as $message)
                    <li>{{$message}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-group">
        <label for="password">{{ucfirst(trans('educal.newpassword'))}}</label>


            <input type="password" class="form-control" id="password" name="password">

    </div>
    <div class="form-group">
        <label for="password_confirmation">{{ucfirst(trans('educal.repeatpassword'))}}</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>
    </div>
    <button type="submit"
            class="btn btn-info">Opslaan
    </button>
    {{ Form::close(), PHP_EOL }}
    {{ Session::get('errorMessage') }}
@stop
