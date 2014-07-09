@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h1>Settings</h1>
        {{ Form::open([
        'route' => 'settings.update',
        'data-ajax' => 'true',
        ]), PHP_EOL }}
        <div class="form-group">
            <label>Language</label>
            {{ Form::select('lang', ['nl' => 'nl','fr' => 'fr','en' => 'en','de' => 'de'], Session::get('lang'), array('class' => 'form-control')) }}
        </div>
        <button type="submit" class="btn btn-default btn-educal-primary">Save</button>
        {{ Form::close(), PHP_EOL }}
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus in nisi eu arcu tempus vehicula.
      Nulla faucibus cursus metus in sagittis. Nunc elit leo, imperdiet in ligula in, euismod varius est.
      Aenean pellentesque lorem a porttitor placerat. Vestibulum placerat nunc ac rutrum fringilla. Donec
      arcu leo, tempus adipiscing volutpat id, congue in purus. Pellentesque scelerisque mattis nibh vel
      semper. Sed a risus purus. Fusce pulvinar, velit eget rhoncus facilisis, enim elit vulputate nisl, a
      euismod diam metus eu enim. Nullam congue justo vitae justo accumsan, sit amet malesuada nulla sagittis.
      Nam neque tellus, tristique in est vel, sagittis congue turpis. Aliquam nulla lacus, laoreet dapibus
      odio vitae, posuere volutpat magna. Nam pulvinar lacus in sapien feugiat, sit amet vestibulum enim
      eleifend. Integer sit amet ante auctor, lacinia sem quis, consectetur nulla.</p>
    </div>
  </div>
</div>
@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop