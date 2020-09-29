<p>{{ __("Bonjour") }} {{ $user->name }},</p>

<p>{{ __("Vous venez de demander la réinitialisation du mot de passe.") }}</p>

<p>{{ __("S'il vous plaît, utilisez le lien ci-dessous pour le renouveler :") }}</p>

<a href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}">{{ $link }}</a>

<p>{{ __('Cordialement') }}</p>