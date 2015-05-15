<!doctype html>
<html>
<head>
{{ HTML::style("css/app.css") }}
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      {{Form::open(array('route' => array('user.sendResetLink'), 'class'=>'form form-horizontal', 'method' => 'post')) }}

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
        {{Form::label('email', 'Geef het email adres op van uw account', array('class' => 'col-md-2 control-label'))}}
      <div class="col-md-8">
        {{Form::email('email', '' , ['class'=>'form-control'])}}
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-offset-2 col-md-8">
        <button type="submit" class="btn btn-default btn-educal-primary"><i class="fa fa-save"></i> Verstuur</button>
      </div>
    </div>
    {{ Form::close(), PHP_EOL }}
    {{ Session::get('errorMessage') }}

  </div>
  </div>
</div>

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop
</body>
</html>