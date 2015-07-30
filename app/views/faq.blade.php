@extends('layout.master-app')
@section('header')
    {{ HTML::style("css/calendar.css") }}
@stop

@section('content')
    <h1>Veelgestelde vragen</h1>
    <div class="col-md-3">
        <div class="btn btn-primary">Kalenders</div>

        <article class="faq-question">
            <div class="faq-question-header"><span>Hoe voeg ik een gebeurtenis toe?</span></div>
            <div class="faq-question-content"><p>
                    Om een event toe te voegen kan je in het vakje klikken van de dag waar je het event wilt aanmaken.
                    Je kan ook rechtsboven klikken op de knop "Voeg event toe".
                </p>

                <p>
                    Hierna vul je alle gegevens in in de popup, en klik je op opslaan. Het evenement is nu zichtbaar
                    voor iedereen.
                </p></div>
        </article>

        <article class="faq-question">
            <div class="faq-question-header"><span>Hoe bekijk ik een event?</span></div>
            <div class="faq-question-content"><p>
                    Lorem ipsum dolor
                </p></div>
        </article>
    </div>
    <div class="col-md-3">
        <div class="btn btn-primary">Dashboard</div>
    </div>
    <div class="col-md-3">
        <div class="btn btn-primary">Exporteren</div>
    </div>
@stop

@section('footerScript')
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script type="text/javascript">
        $(".faq-question-header").click(function () {

            $header = $(this);
            //getting the next element
            $content = $header.next();
            //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
            $content.slideToggle(500, function () {
                //execute this after slideToggle is done
                //change text of header based on visibility of content div
                $header.find('.faq-question-icon').toggleClass('glyphicon-chevron-down');
                $header.find('.faq-question-icon').toggleClass('glyphicon-chevron-up');
            });

        });
    </script>
@stop