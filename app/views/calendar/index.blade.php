@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

        @section('content')
            <div id="calendar"></div>
            <h1>Dashboard</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus in nisi eu arcu tempus vehicula.
                Nulla faucibus cursus metus in sagittis. Nunc elit leo, imperdiet in ligula in, euismod varius est.
                Aenean pellentesque lorem a porttitor placerat. Vestibulum placerat nunc ac rutrum fringilla. Donec
                arcu leo, tempus adipiscing volutpat id, congue in purus. Pellentesque scelerisque mattis nibh vel
                semper. Sed a risus purus. Fusce pulvinar, velit eget rhoncus facilisis, enim elit vulputate nisl, a
                euismod diam metus eu enim. Nullam congue justo vitae justo accumsan, sit amet malesuada nulla sagittis.
                Nam neque tellus, tristique in est vel, sagittis congue turpis. Aliquam nulla lacus, laoreet dapibus
                odio vitae, posuere volutpat magna. Nam pulvinar lacus in sapien feugiat, sit amet vestibulum enim
                eleifend. Integer sit amet ante auctor, lacinia sem quis, consectetur nulla.</p>

            <p>Vestibulum porttitor massa eget pellentesque eleifend. Suspendisse tempor, nisi eu placerat auctor,
                est erat tempus neque, pellentesque venenatis eros lorem vel quam. Nulla luctus malesuada porttitor.
                Fusce risus mi, luctus scelerisque hendrerit feugiat, volutpat gravida nisi. Quisque facilisis risus
                in lacus sagittis malesuada. Suspendisse non purus diam. Nunc commodo felis sit amet tortor
                adipiscing varius. Fusce commodo nulla quis fermentum hendrerit. Donec vulputate, tellus sed
                venenatis sodales, purus nibh ullamcorper quam, sit amet tristique justo velit molestie lorem.</p>

            <p>Fusce sollicitudin lacus lacinia mi tincidunt ullamcorper. Aenean velit ipsum, vestibulum nec
                tincidunt eu, lobortis vitae erat. Nullam ultricies fringilla ultricies. Sed euismod nibh quis
                tincidunt dapibus. Nulla quam velit, porta sit amet felis eu, auctor fringilla elit. Donec
                convallis tincidunt nibh, quis pellentesque sapien condimentum a. Phasellus purus dui, rhoncus
                id suscipit id, ornare et sem. Duis aliquet posuere arcu a ornare. Pellentesque consequat libero
                id massa accumsan volutpat. Fusce a hendrerit lacus. Nam elementum ac eros eu porttitor.
                Phasellus enim mi, auctor sit amet luctus a, commodo fermentum arcu. In volutpat scelerisque
                quam, nec lacinia libero.</p>

            <p>Aliquam a lacinia orci, iaculis porttitor neque. Nullam cursus dolor tempus mauris posuere, eu
                scelerisque sem tincidunt. Praesent blandit sapien at sem pulvinar, vel egestas orci varius.
                Praesent vitae purus at ante aliquet luctus vel quis nibh. Mauris id nulla vitae est lacinia
                rhoncus a vel justo. Donec iaculis quis sapien vel molestie. Aliquam sed elementum orci.
                Vestibulum tristique tempor risus et malesuada. Sed eget ligula sed quam placerat dapibus.
                Integer accumsan ac massa at tempus.</p>

@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop