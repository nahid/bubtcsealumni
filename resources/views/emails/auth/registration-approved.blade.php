<x-mail::message>
# Welcome to BUBTAlumni! 🎉

Hi {{ $user->name }},

Great news — both of your references have approved your registration. Your account is now **verified** and ready to use!

Your Alumni ID: **{{ $user->alumni_id }}**

<x-mail::button :url="route('login')">
Log In Now
</x-mail::button>

You can now access the full alumni network, browse job postings, connect with fellow alumni, and more.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
