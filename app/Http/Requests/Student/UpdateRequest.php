<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $decodedId = base64_decode($this->route('student'));

        return [
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'email' => [
                'required',
                'email',
                Rule::unique('students', 'email')->ignore($decodedId, 'id')
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'phone' => ['required', 'digits:10'],
            'age' => ['required', 'integer', 'min:1', 'max:100'],
            'dob' => ['required', 'date'],
            'preferred_time' => ['required'],
            'gender' => ['required', 'in:male,female'],
            'skills' => ['required', 'array', 'min:1'],
            'skills.*' => ['string'],
            'country' => ['required', 'string'],
            'languages' => ['required', 'array', 'min:1'],
            'languages.*' => ['string'],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:2048'],
            'about' => ['required', 'string', 'min:10'],
            'fav_color' => ['required', 'string']
        ];
    }

    /**
     * Custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Full name is required.',
            'name.min' => 'Full name must be at least 3 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already taken.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must include uppercase, lowercase, number & special character.',
            'phone.required' => 'Phone number is required.',
            'phone.digits' => 'Phone number must be 10 digits.',
            'age.required' => 'Age is required.',
            'age.integer' => 'Age must be a number.',
            'age.min' => 'Age cannot be less than 1.',
            'age.max' => 'Age cannot be more than 100.',
            'dob.required' => 'Date of Birth is required.',
            'dob.date' => 'Please enter a valid date.',
            'preferred_time.required' => 'Preferred time is required.',
            'gender.required' => 'Please select your gender.',
            'gender.in' => 'Gender must be male or female.',
            'skills.required' => 'Please select at least one skill.',
            'country.required' => 'Country is required.',
            'languages.required' => 'Please select at least one language.',
            'photo.mimes' => 'Only jpg, jpeg, or png files are allowed.',
            'photo.max' => 'Maximum file size is 2MB.',
            'about.required' => 'Please write something about the student.',
            'about.min' => 'About section must be at least 10 characters.',
            'fav_color.required' => 'Please select your favorite color.',
        ];
    }
}