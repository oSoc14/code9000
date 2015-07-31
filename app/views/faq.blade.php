@extends('layout.master-app')
@section('header')
    {{ HTML::style("css/admin.css") }}
@stop

@section('content')
    <h1>Veelgestelde vragen</h1>
    <div class="faq-container">
        <div class="faq-category">Kalenders</div>

        <article class="faq-question">
            <div class="faq-question-header"><span>Hoe voeg ik een gebeurtenis toe?</span><span
                        class="faq-question-icon">▶</span></div>
            <div class="faq-question-content" style="display:none"><p>
                    Om een event toe te voegen kan je in het vakje klikken van de dag waar je het event wilt aanmaken.
                    Je kan ook rechtsboven klikken op de knop "Voeg event toe".
                </p>

                <p>
                    Hierna vul je alle gegevens in in de popup, en klik je op opslaan. Het evenement is nu zichtbaar
                    voor iedereen.
                </p></div>
        </article>

        <article class="faq-question">
            <div class="faq-question-header"><span>Hoe bekijk ik een event?</span><span
                        class="faq-question-icon">▶</span></div>
            <div class="faq-question-content" style="display:none"><p>
                    Lorem ipsum dolor
                </p></div>
        </article>
    </div>
    <div class="faq-container">
        <div class="faq-category">Dashboard</div>

    </div>
    <div class="faq-container">
        <div class="faq-category">Exporteren</div>

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
            $content.slideToggle(500, 'swing', false);
            $header.find('.faq-question-icon').toggleClass('faq-question-icon-rotate');
        });
    </script>
@stop