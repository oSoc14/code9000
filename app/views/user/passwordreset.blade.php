@section('form')
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
        <label for="password_confirmation"
               class="col-md-2 control-label">{{ucfirst(trans('educal.repeatpassword'))}}</label>

        <div class="col-md-8">
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-2 col-md-8">
            <button type="submit" class="btn btn-default btn-educal-primary"><i
                        class="fa fa-save"></i> {{ucfirst(trans('educal.savechanges'))}}</button>
        </div>
    </div>
    {{ Form::close(), PHP_EOL }}
    {{ Session::get('errorMessage') }}
@stop
