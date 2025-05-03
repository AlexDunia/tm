<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SecureFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Base implementation always requires authentication
        // Override in child classes if needed
        return Auth::check();
    }

    /**
     * Additional sanitization of input data after validation.
     *
     * @param array $sanitized The data after validation
     * @return array
     */
    protected function sanitizeInput(array $sanitized): array
    {
        foreach ($sanitized as $key => $value) {
            if (is_string($value)) {
                // Remove unwanted HTML and potentially harmful content
                $sanitized[$key] = $this->sanitizeString($value, $key);
            } elseif (is_array($value)) {
                // Recursively sanitize arrays
                $sanitized[$key] = $this->sanitizeInput($value);
            }
        }

        return $sanitized;
    }

    /**
     * Sanitize a single string value.
     *
     * @param string $value The string to sanitize
     * @param string $key The input field key
     * @return string
     */
    protected function sanitizeString(string $value, string $key = ''): string
    {
        // Remove potentially harmful HTML (except for fields that should allow HTML)
        $html_allowed_fields = $this->getHtmlAllowedFields();

        if (empty($key) || !in_array($key, $html_allowed_fields)) {
            // Basic HTML filtering - remove all tags
            $value = strip_tags($value);
        } else {
            // For fields that should allow HTML, use a more targeted approach
            // Remove script tags and on* attributes
            $value = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $value);
            $value = preg_replace('/on\w+="[^"]*"/is', '', $value);
            $value = preg_replace('/on\w+=\'[^\']*\'/is', '', $value);
        }

        // Trim whitespace
        $value = trim($value);

        return $value;
    }

    /**
     * Get validated and sanitized input data.
     *
     * @return array
     */
    public function validated($key = null, $default = null)
    {
        $validatedData = parent::validated($key, $default);

        if (is_array($validatedData)) {
            return $this->sanitizeInput($validatedData);
        }

        return $validatedData;
    }

    /**
     * Fields that should allow HTML content.
     * Override in child classes.
     *
     * @return array
     */
    protected function getHtmlAllowedFields(): array
    {
        return [
            // List fields that should allow HTML here, e.g., 'description'
        ];
    }
}
