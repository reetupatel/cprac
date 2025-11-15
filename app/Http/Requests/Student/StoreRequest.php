<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'unique:students,email'],
            'password' => [
                'required',
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
            'photo' => ['required', 'mimes:jpg,jpeg,png', 'max:2048'],
            'about' => ['required', 'string', 'min:10'],
            'fav_color' => ['required', 'string']
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages()
    {
        return [
            'name.required' => 'Full name is required.',
            'name.min' => 'Full name must be at least 3 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already taken.',
            'password.required' => 'Password is required.',
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
            'photo.required' => 'Profile photo is required.',
            'photo.mimes' => 'Only jpg, jpeg, or png files are allowed.',
            'about.required' => 'Please write something about the student.',
            'about.min' => 'About section must be at least 10 characters.',
            'fav_color.required' => 'Please select your favorite color.'
        ];
    }
}