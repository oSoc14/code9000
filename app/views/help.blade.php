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
          <h3>Kalendar</h3>
          <p>De kalender is eigenlijk de centrale hub van educal. Alle events binnenin je groepen en school zijn hier terug te vinden. Je kan ook nieuwe events aanmaken en toevoegen tot de kalender (als je daar gerechtigd voor bent). De kalender op zich kan in drie verschillende modussen bekeken worden: maandelijks, wekelijks of dagelijks.</p>
          <p>Wanneer je op een event klikt verschijnt er een apart venster dat alle details over dat event toont, zoals de omschrijving en de begin- en einddatum. Onderaan dat venster vind je ook opties terug om dat evenement te exporteren, z'n details te wijzigen of om het te verwijderen uit de kalender, afhankelijk van je groepsrechten.</p>
        </li>
        <li>
          <h3>Scholen</h3>
          <p><mark>Opgelet: deze pagina is enkel zichtbaar als u superadmin bent binnen educal!</mark></p>
          <p>Hier vind je een overzicht van alle scholen die momenteel geregistreerd zijn binnen educal. Als een superadmin heb je de opties om ze te wijzigen of verwijderen via de acties-kolom, aan de rechterkant.</p>
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
        <li>
            <h3>Instellingen</h3>
            <p>Als gebruiker heb je enkele instellingen die je kan aanpassen. Deze instellingen zijn:
                <ul>
                    <li>Familienaam</li>
                    <li>Naam</li>
                    <li>E-mail adres</li>
                    <li>Taal</li>
                    <li>Paswoord aanpassen</li>
                </ul>
            </p>

            <h4><u>Taal wijzigen</u></h4>
            <p>EduCal is nu beschikbaar in 3 verschillende talen: Engles, Nederlands and Frans.</p>
        </li>
        <li>
            <h3>Feedback</h3>
            <p>Wil je bijdragen aan de vooruitgang van educal? Heb je een goed idee dat je wil delen? Contacteer ons dan, we stellen feedback ten zeerste op prijs. :)</p>
        </li>
        <li>
            <h3>FAQ</h3>
            <p><b>Ik heb me net geregistreerd maar ik kan niet inloggen. Waarom niet?</b></p>
            <p>Als je je eerst registreert bij een school, dan moet iemand van de schooladministratie uw account activeren. Dit is nodig voor veiligheidsredenen.</p>
            <p><b>Mijn organisatie is geen school. Kan ik dit platform dan nog steeds gebruiken?</b></p>
            <p>Natuurlijk! Educal kan gebruikt worden door elke organisatie die er baat bij heeft van een centrale kalender te hebben die ze gemakkelijk kunnen delen.</p>
            <p><b>Wat is het verschil tussen scholen en groepen?</b></p>
            <p>Scholen zijn de organisaties die zich op het educal platform registreren. Elke school heeft dan verschillende groepen waar ze gebruikers aan kunnen toekennen. De kalenders worden gegenereerd op basis van de groepen, niet op basis van de school.</p>
            <p><b>Kan ik uitzonderingen toevoegen voor herhalende events?</b></p>
            <p>Helaas niet. Dit is een feature die hoog op de ToDo-lijst staat, maar we waren niet in staat om dit af te leveren met deze versie. Voorlopig zal je dus manueel rekening moeten houden met uitzonderingen.</p>
            <p><b>Kan een gebruiker in meer dan 1 school zitten tegelijk?</b></p>
            <p>Nee. Deze feature staat ook hoog op de ToDo-lijst, maar is ook nog niet geïmplementeerd in deze versie.</p>
            <p><b>Zal de iCal link alle events van de school inladen?</b></p>
            <p>Neen! De exports zijn gebaseerd op groepen. Dus je laadt enkel de events in van de groep waarvan je de link hebt, alsook de globale events die tellen voor alle groepen. Ook zal je enkel events binnenhalen van 1 jaar terug t.e.m. 1 jaar in de toekomst.</p>
            <p><b>Moet ik me inloggen of registreren om de iCal of PDF files te kunnen downloaden?</b></p>
            <p>Neen. Zolang je de link hebt, kan je deze bestanden op eender welk moment raadplegen.</p>
            <p><b>Educal is net gecrasht, hoe komt dit?</b></p>
            <p>Educal is zeer recent gemaakt en er is nog veel ruimte voor verbetering, alsook zijn er waarschijnlijk nog enkele bugs die zich verschuilen hier en daar. We hopen op feedback als je ervaring niet optimaal was zodat we verbeteringen kunnen uitvoeren. Je kan ons contacteren met de feedback link in het linker menu.</p>
        </li>
      </ul>
    </div>
  </div>
</div>
@stop

@section('footerScript')
{{ HTML::script('js/app.js') }}
@stop
