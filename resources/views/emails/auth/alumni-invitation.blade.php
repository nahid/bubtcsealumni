<x-mail::message>
# Welcome to BUBTAlumni! 🎓

Hi {{ $user->name }},

An administrator has created an alumni account for you at {{ config('app.name') }}. To finish setting up your account, click the button below to set your password. This will also verify your email address.

Your Alumni ID: **{{ $user->alumni_id }}**

<x-mail::button :url="$invitationUrl">
Set Password & Activate Account
</x-mail::button>

This invitation link will expire in 60 minutes. If it expires, please ask the administrator to resend the invitation.

If you weren't expecting this invitation, you can safely ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
