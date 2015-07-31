<!DOCTYPE html>
<html lang="nl-BE">
<head>
    <meta charset="utf-8">
</head>
<body>
<p>

    Beste @if (isset($user) && isset($user->first_name)) {{$user->first_name}}@endif,<br><br>

    Je gaf aan je wachtwoord vergeten te zijn. Via onderstaande link kan je je wachtwoord herstellen.<br>

    Volg volgende link om je wachtwoord opnieuw in te stellen. Indien de link niet werkt, kopieer en plak hem dan in de
    adresbalk van je browser.<br>
    <a href="{{$url}}">{{$url}}</a><br><br>
    Alvast veel plezier bij het plannen!<br>
    Het educal team<br><br><br><br>
    Heb je dit niet aangevraagd? Negeer deze mail dan.<br>
    Bij verdere vragen of problemen, bezoek <a href="http://educal.gent.be/">educal.gent.be</a>.
</p>
</body>
</html>