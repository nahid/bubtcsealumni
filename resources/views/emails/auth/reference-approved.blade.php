<x-mail::message>
# Good News — A Reference Has Approved You!

Hi {{ $applicant->name }},

**{{ $approverName }}** has approved your reference request on BUBTAlumni. You're one step closer to getting verified!

Your account is still waiting for the second reference to approve. Once both references have approved, your account will be fully verified and you'll be able to access the alumni network.

Hang tight — we'll notify you as soon as your account is fully verified.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
