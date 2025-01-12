<x-mail::message>
# Support Time Purchase Confirmation

Dear {{ $user->name }},

Thank you for your recent purchase of support time. We appreciate your trust in our services.

## Purchase Details
- **Support Time Purchased:** {{ $purchasedTime }} hours
- **Support Type:** {{ ucfirst($supportType) }}
- **Payment ID:** {{ $paymentId }}

@if ($details)
## Additional Details You Provided
{{ $details }}
@endif

## Your Support Time Balance
Your current total support time balance is **{{ $totalSupportTime }} hours**.

## Next Steps
A member of Vanguard's support team will be in touch with you soon to address your support needs. We aim to provide the best possible assistance for your {{ $supportType }} support request.

If you have any immediate questions or need to provide additional information, please don't hesitate to reply to this email or contact us at support@vanguardbackup.com.

<x-mail::button :url="route('support.dashboard')">
View Your Support Dashboard
</x-mail::button>

Thank you again for your purchase. We look forward to assisting you.

Best regards,<br>
The {{ config('app.name') }} Team
</x-mail::message>
