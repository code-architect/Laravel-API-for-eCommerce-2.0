Hello {{$user->name}},
You have changed your email account address. please verify your email using this link:
{{ route('verify', $user->verification_token) }}

With Regards,
Laravel