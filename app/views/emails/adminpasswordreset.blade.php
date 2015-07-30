<!DOCTYPE html>
<html lang="nl-BE">
<head>
    <meta charset="utf-8">
</head>
<body>
<p>
    Beste@if(isset($user) && isset($user->firstname)) {{$user->firstname}}@endif,

    Iemand heeft voor jou een account gemaakt op Educal.<br>
    @if(isset($user) && isset($user->school) && isset($user->school->name))Je account werd aangemaakt voor volgende
    school: {{$user->school->name}}.<br>@endif
    Om je account te activeren, dien je enkel nog je wachtwoord in te stellen.<br><br>

    Volg volgende link om je wachtwoord in te stellen. Indien de link niet werkt, kopieer en plak hem dan in de
    adresbalk van je browser.<br>
    <a href="{{$url}}">{{$url}}</a><br><br>
    Alvast veel plezier bij het plannen!<br>
    Het educal team<br><br>
    Ben je niet verbonden met de school die je account aanmaakte? Negeer deze mail dan.<br>
    Bij verdere vragen of problemen, bezoek <a href="http://educal.gent.be/">educal.gent.be</a>.
</p>
</body>
</html>