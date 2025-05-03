<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UserRegisterRequest extends SecureFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Allow anyone to register
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
            ],
            'password' => [
                'required',
                'string',
                'min:8',             // minimum 8 characters instead of 12
                'regex:/[a-z]/',      // at least one lowercase letter
                'regex:/[A-Z]/',      // at least one uppercase letter
                'regex:/[0-9]/',      // at least one number
                'regex:/[@$!%*#?&]/', // at least one special character
                'confirmed',          // requires password_confirmation field
            ],
            'password_confirmation' => 'required',  // Add confirmation field
            'profilepic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'firstname.required' => 'Your first name is required.',
            'lastname.required' => 'Your last name is required.',
            'email.required' => 'Your email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'A password is required.',
            'password.min' => 'Your password must be at least 8 characters long.',
            'password.regex' => 'Your password must include at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password_confirmation.required' => 'Please confirm your password.',
            'profilepic.image' => 'The profile picture must be an image.',
            'profilepic.mimes' => 'The profile picture must be a JPEG, PNG, JPG or GIF file.',
            'profilepic.max' => 'The profile picture must be less than 2MB in size.',
        ];
    }

    /**
     * Apply additional filters before validation.
     *
     * @return array
     */
    public function validationData()
    {
        $data = parent::validationData();

        // Clean up email input before validation
        if (isset($data['email'])) {
            $data['email'] = strtolower(trim($data['email']));
        }

        return $data;
    }

    /**
     * Fields that should allow HTML content.
     *
     * @return array
     */
    protected function getHtmlAllowedFields(): array
    {
        return [];
    }
}
