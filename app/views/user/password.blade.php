<!doctype html>
<html>
<head>
  {{ HTML::style("css/app.css") }}
  {{ HTML::style("css/bootstrap.min.css") }}
  {{ HTML::style("css/bootstrap-theme.min.css") }}
  <!-- FontAwesome icons -->
  {{ HTML::style("//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css") }}
  <!-- Google Webfont -->
  {{ HTML::style("http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,700") }}
  {{ HTML::style("css/landing.css") }}
</head>
<body>



@if(Session::has('errorMessage'))
  <div class="modal fade" id="passwordResetModal" tabindex="-1" data-errors="true" role="dialog" aria-labelledby="passwordResetModal" aria-hidden="true">
@else
<div class="modal fade" id="passwordResetModal" tabindex="-1" data-errors="false" role="dialog" aria-labelledby="passwordResetModal" aria-hidden="true">
    @endif
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title">Stel uw nieuw wachtwoord in</h4>
        </div>
        <div class="modal-body">
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
            <label for="password" class="col-md-2 control-label">{{ucfirst(trans('educal.newpassword'))}}</label>
            <div class="col-md-8">
              <input type="password" class="form-control" id="password" name="password">
            </div>
          </div>
          <div class="form-group">
            <label for="password_confirmation" class="col-md-2 control-label">{{ucfirst(trans('educal.repeatpassword'))}}</label>
            <div class="col-md-8">
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-offset-2 col-md-8">
              <button type="submit" class="btn btn-default btn-educal-primary"><i class="fa fa-save"></i> {{ucfirst(trans('educal.savechanges'))}}</button>
            </div>
          </div>
          {{ Form::close(), PHP_EOL }}
          {{ Session::get('errorMessage') }}
        </div>

      </div>
    </div>

  </div>

  <div class="modal fade" id="success" tabindex="-1" role="dialog" aria-labelledby="success" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="success">Gelukt!</h4>
      </div>
      <div class="modal-body">
        Uw wachtwoord werd opnieuw ingesteld, sluit dit scherm om terug te keren naar te hoofdpagina.
      </div>
      {{Form::label('email', '', ['class' => 'hidden']) }}
      {{Form::email('email', '' , ['class'=>'hidden'])}}
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

  {{ HTML::script("js/jquery-1.11.1.min.js") }}
  {{ HTML::script("js/bootstrap.min.js") }}
  {{ HTML::script("js/password.js") }}
</body>
</html>