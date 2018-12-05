<p> Bonjour {{ $mentor->name }} </p>

<p> Vous êtes invités à évaluer les personnes de votre équipe avant la date : {{ Carbon\Carbon::parse($endDate)->format('d-m-Y') }} </p>

<p> Vous pouvez vous connecter à votre espace en cliquant sur le lien suivant : <a href="{{url('/login')}}"> {{url('/login')}} </a> </p>

<p> Voilà vos accès :  </p>
<p> E-mail : {{ $mentor->email }} </p>
<p> Password : {{ $password }} </p>
