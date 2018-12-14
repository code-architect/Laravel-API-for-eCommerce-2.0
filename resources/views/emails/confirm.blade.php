@component('mail::message')
    # Hello {{$user->name}},

    You have changed your email account address. please verify your email using this button below:

    @component('mail::button', ['url' =>  route('verify', $user->verification_token) ])
        Button Text
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
