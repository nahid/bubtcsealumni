<x-mail::message>
# Reference Approval Request

Hi there,

**{{ $applicant->name }}** has registered on BUBTAlumni and listed you as one of their references.

Here are their details:

<x-mail::table>
| Field | Details |
| --- | --- |
| **Name** | {{ $applicant->name }} |
| **Email** | {{ $applicant->email }} |
| **Intake** | {{ $applicant->intake }} |
| **Shift** | {{ ucfirst($applicant->shift) }} |
</x-mail::table>

Please log in to your dashboard to approve or decline this request.

<x-mail::button :url="route('dashboard')">
Review Pending Approvals
</x-mail::button>

If you do not recognize this person, please decline the request.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
