<x-mail::message>
# Account Suspended

Hi {{ $user->name }},

Your BUBTAlumni account has been suspended by an administrator. You will not be able to log in until your account is reactivated.

If you believe this was done in error, please contact an administrator for assistance.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
