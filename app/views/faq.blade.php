@extends('layout.master-app')
@section('header')
    {{ HTML::style("css/admin.css") }}
@stop

@section('content')
    <h1>Veelgestelde vragen</h1>
    <div class="faq-container">
        <div class="faq-category">Kalender</div>

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
                    Als ouder kan je alle evenementen te bekijken. Vergeet niet aan de linkerkant te selecteren voor
                    welke je klassen de kalender wilt zien! Klik op de pijltjes om een leerjaar te bekijken, en klik dan
                    op de klassen die je wilt zien. Nu verschijnen op de kalender alle activiteiten van de klassen die
                    je geselecteerd hebt. Als je dan klikt op een activiteit zie je meer informatie.

                </p></div>
        </article>
        <article class="faq-question">
            <div class="faq-question-header"><span>Wat betekenen de verschillende kleuren?</span><span
                        class="faq-question-icon">▶</span></div>
            <div class="faq-question-content" style="display:none"><p>
                    Elke jaar heeft zijn eigen kleur. Aan de linkerkant kan je zien welk kleurtje bij welk jaar hoort.
                </p></div>
        </article>
        <article class="faq-question">
            <div class="faq-question-header"><span>Ik zie geen activiteiten. Hoe komt dat?</span><span
                        class="faq-question-icon">▶</span></div>
            <div class="faq-question-content" style="display:none"><p>

                    In het menu aan de linkerkant kan je aanduiden voor welke klassen je de informatie wilt zien. Op die
                    manier krijg je enkel de activiteiten te zien die voor jou belangrijk zijn.

                </p></div>
        </article>


    </div>
    <div class="faq-container">
        <div class="faq-category">Plannen</div>
        <article class="faq-question">
            <div class="faq-question-header"><span>Hoe voeg ik een event toe?</span><span
                        class="faq-question-icon">▶</span></div>
            <div class="faq-question-content" style="display:none"><p>

                    Alleen medewerkers kunnen activiteiten toevoegen. Je klikt simpelweg op de dag waarop je een
                    activiteit wilt
                    toevoegen, of je klikt op de knop ‘evenement aanmaken’ rechtsboven, en voegt dan de datum in.

                </p></div>
        </article>
        <article class="faq-question">
            <div class="faq-question-header"><span>Hoe kan ik een event op meerdere dagen plannen?</span><span
                        class="faq-question-icon">▶</span></div>
            <div class="faq-question-content" style="display:none"><p>
                    Dat gaat het makkelijkst als je klikt en sleept over de dagen die je wilt selecteren. Je kan ook op
                    de begindag
                    klikken, en dan de einddatum invullen in het venstertje dat openspringt.

                </p></div>
        </article>


    </div>
    <div class="faq-container">
        <div class="faq-category">Dashboard</div>

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