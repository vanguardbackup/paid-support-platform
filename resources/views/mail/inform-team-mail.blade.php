<x-mail::message>
# Support Time Purchased

{{ $user->name }} has purchased support time.

## Purchase Details
- **Purchased Time:** {{ $purchasedTime }} hours
- **Support Type:** {{ ucfirst($supportType) }}
- **Payment ID:** {{ $paymentId }}

## User Details
- **Name:** {{ $user->name }}
- **Email:** {{ $user->email }}
- **Total Support Time Balance:** {{ $totalSupportTime }} hours

@if ($details)
## Additional Details
{{ $details }}
@endif

Please get in touch with the user as soon as possible to address their support needs.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
