<x-mail::message>
# Registration Declined

Hi {{ $applicant->name }},

Unfortunately, one of your references has declined your registration request on BUBTAlumni.

As a result, your registration could not be completed. If you believe this was a mistake, you are welcome to register again with different references.

<x-mail::button :url="route('register')">
Register Again
</x-mail::button>

If you have any questions, please reach out to an administrator.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
