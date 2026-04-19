<x-mail::message>
# Account Reactivated

Hi {{ $user->name }},

Your BUBTAlumni account has been reactivated by an administrator. You can now log in and access the alumni network again.

<x-mail::button :url="route('login')">
Log In Now
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
