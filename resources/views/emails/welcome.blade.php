Hello {{$user->name}},
Thank you for creating an account. please verify your email using this link:
{{ route('verify', $user->verification_token) }}

With Regards,
Laravel