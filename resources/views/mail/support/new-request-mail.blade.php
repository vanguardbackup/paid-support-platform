<x-mail::message>
# New Support Request

Hey {{ $staff->name }},

A new support request has been submitted by {{ $requester->name }} for **{{ $supportRequest->formatted_category }}**.

You have received this email as you have staff permissions. It does not mean you been assigned to this request.

<x-mail::button :url="$supportRequestUrl">
Read Request
</x-mail::button>

{{ config('app.name') }}
</x-mail::message>
