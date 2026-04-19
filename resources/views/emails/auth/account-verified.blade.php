<x-mail::message>
# Account Verified! 🎉

Hi {{ $user->name }},

An administrator has manually verified your BUBTAlumni account. You now have full access to the alumni network.

Your Alumni ID: **{{ $user->alumni_id }}**

<x-mail::button :url="route('login')">
Log In Now
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
