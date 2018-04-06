<p> Bonjour {{ $user->name }} </p>

<p> Vous êtes invités à remplir une évaluation avant la date : {{ Carbon\Carbon::parse($endDate)->format('d-m-Y') }} </p>

<p> Vous pouvez vous connecter à votre espace en cliquant sur le lien suivant : <a href="{{url('/login')}}"> {{url('/login')}} </a> </p>

<p> Voilà vos accès :  </p>
<p> E-mail : {{ $user->email }} </p>
<p> Password : {{ $password }} </p>
