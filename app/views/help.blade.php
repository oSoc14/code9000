@extends('layout.master')

@section('header')
{{ HTML::style("css/app.css") }}
@stop

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12 col-md-10">
      <h1>Hulp nodig?</h1>
      <h2>Pagina's</h2>
      <ul>
        <li>
          <h3>Calendar</h3>
          <p>De kalender is eigenlijk de centrale hub van EduCal. Alle events binnenin je groepen en school zijn hier terug te vinden. Je kan ook nieuwe events aanmaken en toevoegen tot de kalender (als je daar gerechtigd voor bent). De kalender op zich kan in drie verschillende modussen bekeken worden: maandelijks, wekelijks of dagelijks.</p>
          <p>Wanneer je op een event klikt verschijnt er een apart venster dat alle details over dat event toont, zoals de omschrijving en de begin- en einddatum. Onderaan dat venster vind je ook opties terug om dat evenement te exporteren, z'n details te wijzigen of om het te verwijderen uit de kalender, afhankelijk van je groepsrechten.</p>
        </li>
        <li>
          <h3>Scholen</h3>
          <p><mark>Opgelet: deze pagina is enkel zichtbaar als u superadmin bent binnen EduCal !</mark></p>
          <p>Hier vind je een overzicht van alle scholen die momenteel geregistreerd zijn binnen EduCal. Als een superadmin heb je de opties om ze te wijzigen of verwijderen via de acties-kolom, aan de rechterkant.</p>
          <p>Alle kolommen zijn sorteerbaar en er is ook een zoekfunctie geïntegreerd that snel kan zoeken in de tabel naar een match. Wanneer u klikt op een schoolnaam wordt u doorverwezen naar de groepen binnenin die school.</p>
        </li>
        <li>
          <h3>Groepen</h3>
          <p>Hier vind je een overzicht van alle groepen binnen in uw school. Ook vindt u de optie terug om de kalender links te kopiëren die u daarna kunt delen met anderen. Verder kunt u ook groepen toevoegen via de knop bovenaan. Alle groepen vind je hier trouwens terug in de tabel, met een deelbare link ernaast.</p>
          <p>Standaard zal die altijd de iCal link aan u tonen. <strong>Dit is de URL die anderen nodig hebben om te kunnen abonneren !</strong> Kopiëer en deel de link met hen. Zij zullen in staat zijn om uw evenementen enkel te bekijken. Wijzigen of verwijderen zal niet gaan.</p>
          <p>De 2 knoppen naast het tekstveld zijn daar gezet om te kunnen wisselen tussen de iCal link en de PDF link. Probeer er op te klikken en zie het verschil!</p>
          <p>Alle beschikbare opties zijn:
            <ul>
              <li>Groep wijzigen</li>
              <li>Groep verwijderen</li>
              <li>Een deelbare link genereren voor iCal of PDF</li>
              <li>De PDF onmiddelijk downloaden</li>
            </ul>
          </p>
          <h4><u>Groep wijzigen</u></h4>
          <p>Hier kun je de groepsnaam en de rechten wijzigen. Let op! De groepen "global" en "admin" kunnen niet gewijzigd worden. Deze zijn de standaard groepen binnen in elke school. Je kunt wel gebruikers toevoegen uiteraard.</p>
          <p>De rechten zijn simpel. Hier kies je ervoor wat de personen in die groep gerechtigd zijn om te doen. Zo kunnen ze ofwel andere groepen maken, ze kunnen gebruikers toevoegen aan de school/groepen of ze kunnen nieuwe events aanmaken. Daaronder vind je twee velden terug om gebruikers aan deze groep toe te voegen en bestaande gebruikers uit de groep te verwijderen.</p>
        </li>
        <li>
          <h3>Gebruikers</h3>
          <p>Hier vind je een overzicht van alle gebruikers binnen uw school. Je kunt ze activeren (of deactiveren), hun details wijzigen of ze verwijderen uit uw school. Je kunt snel alle namen en emailadressen terugvinden. Net als de andere pagina's vind je alles terug in een handige tabel met sorteermogelijkheden en een zoekfunctie.</p>
        </li>
      </ul>
    </div>
  </div>
</div>
@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop