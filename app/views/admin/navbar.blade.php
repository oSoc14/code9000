<div class="navbar">
  <ul>
    <li>
      <a href="{{ route('admin.dashboard', ['org_slug'=>$org->slug]) }}" {{ Route::currentRouteName()=='admin.dashboard' ? ' class="active"' : '' }}>
        Dashboard
      </a>
    </li>
    <li>
      <a href="{{ route('admin.calendars', ['org_slug'=>$org->slug]) }}" {{ Route::currentRouteName()=='admin.calendars' ? ' class="active"' : '' }}>
        Klassen
      </a>
    </li>
    <li>
      <a href="{{ route('admin.users', ['org_slug'=>$org->slug]) }}" {{ Route::currentRouteName()=='admin.users' ? ' class="active"' : '' }}>
        Medewerkers
      </a>
    </li>
  </ul>
</div>
