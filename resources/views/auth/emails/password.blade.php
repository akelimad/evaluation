<p>{{ __("Bonjour") }} {{ $firstname }},</p>

<p>{{ __("Vous venez de demander la réinitialisation du mot de passe.") }}</p>

<p>{{ __("S'il vous plaît, utilisez le lien ci-dessous pour le renouveler :") }}</p>

<a href="{{ $reset_url }}">{{ $reset_url }}</a>

<p>{{ __('Cordialement') }}</p>