<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class EventStoreRequest extends SecureFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Only authenticated users can create events
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:10000',
            'location' => 'required|string|max:255',
            'date' => 'required|string|max:255',
            'herolink' => 'required|url|max:2048',
            'image' => 'required|url|max:2048',
            'heroimage' => 'required|url|max:2048',
            'category' => 'nullable|string|max:50',

            // Ticket types
            'ticket_name' => 'sometimes|array',
            'ticket_name.*' => 'required_with:ticket_price.*|string|max:100',
            'ticket_price' => 'sometimes|array',
            'ticket_price.*' => 'required_with:ticket_name.*|numeric|min:0',
            'ticket_description' => 'sometimes|array',
            'ticket_description.*' => 'nullable|string|max:1000',
            'ticket_capacity' => 'sometimes|array',
            'ticket_capacity.*' => 'nullable|integer|min:0',
            'ticket_sales_start' => 'sometimes|array',
            'ticket_sales_start.*' => 'nullable|date',
            'ticket_sales_end' => 'sometimes|array',
            'ticket_sales_end.*' => 'nullable|date|after_or_equal:ticket_sales_start.*',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'The event name is required.',
            'description.required' => 'The event description is required.',
            'location.required' => 'The event location is required.',
            'date.required' => 'The event date is required.',
            'herolink.required' => 'The hero link is required.',
            'herolink.url' => 'The hero link must be a valid URL.',
            'image.required' => 'The event image is required.',
            'image.url' => 'The image must be a valid URL.',
            'heroimage.required' => 'The hero image is required.',
            'heroimage.url' => 'The hero image must be a valid URL.',
            'ticket_price.*.numeric' => 'Ticket prices must be valid numbers.',
            'ticket_price.*.min' => 'Ticket prices cannot be negative.',
            'ticket_capacity.*.integer' => 'Ticket capacity must be a whole number.',
            'ticket_capacity.*.min' => 'Ticket capacity cannot be negative.',
            'ticket_sales_end.*.after_or_equal' => 'The sales end date must be after or equal to the sales start date.',
        ];
    }

    /**
     * Fields that should allow HTML content.
     *
     * @return array
     */
    protected function getHtmlAllowedFields(): array
    {
        return [
            'description',
            'ticket_description',
        ];
    }
}
