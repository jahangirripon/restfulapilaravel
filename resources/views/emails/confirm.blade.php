Hello {{ $user->name }}

You changed your mail,  please verify using this link =>
{{ route('verify', $user->verification_token) }}