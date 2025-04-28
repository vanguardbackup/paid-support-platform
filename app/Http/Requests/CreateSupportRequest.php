<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSupportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'category' => [
                'required',
                'string',
                'in:'.implode(',', array_keys(config('support-requests.available_categories'))),
            ],
            'additional_category_info' => [
                'required_if:category,other',
                'nullable',
                'string',
                'max:255',
            ],
            'preferred_assistance_type' => [
                'required',
                'string',
                'in:'.implode(',', array_keys(config('support-requests.available_assistance_types'))),
            ],
            'preferred_date' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:today',
                'before_or_equal:'.now()->addMonths(3)->format('Y-m-d'), // Limit bookings to 3 months ahead
            ],
            'preferred_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    // Ensure time is within business hours (9 AM to 5 PM)
                    $hour = (int) explode(':', $value)[0];
                    if ($hour < 9 || $hour >= 17) {
                        $fail('Support is only available between 9:00 AM and 5:00 PM.');
                    }
                },
            ],
            'timezone' => [
                'required',
                'string',
                'regex:/^UTC[+-]([0-1][0-9]|2[0-3]):[0-5][0-9]$/', // Format like UTC+01:00 or UTC-08:00
            ],
            'additional_details' => [
                'required',
                'string',
                'min:30',
                'max:2000',
            ],
            'acceptTerms' => [
                'required',
                'accepted',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Please provide a title for your support request.',
            'category.required' => 'Please select the type of issue you are experiencing.',
            'additional_category_info.required_if' => 'Please describe your issue when selecting "Other".',
            'preferred_assistance_type.required' => 'Please select your preferred method of assistance.',
            'preferred_date.required' => 'Please select your preferred date for support.',
            'preferred_date.after_or_equal' => 'The preferred date must be today or a future date.',
            'preferred_date.before_or_equal' => 'Support sessions can only be scheduled up to 3 months in advance.',
            'preferred_time.required' => 'Please select your preferred time for support.',
            'preferred_time.date_format' => 'The preferred time must be in a valid time format (HH:MM).',
            'timezone.required' => 'Please select your timezone.',
            'timezone.regex' => 'Please select a valid timezone from the dropdown.',
            'additional_details.required' => 'Please provide details about your issue.',
            'additional_details.min' => 'Please provide more details about your issue (minimum 30 characters).',
            'acceptTerms.accepted' => 'You must accept the terms to submit a support request.',
        ];
    }
}
