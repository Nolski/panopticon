<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $isUpdate = $this->getMethod() == 'POST' ? false : true;

        if ($this->getMethod() == 'POST' or $this->getMethod() == 'PUT') {
            $rules = [
                'name' => 'required|string',
                'email' => $this->emailValidation($isUpdate),
                'password' => $this->passwordValidation($isUpdate),
                'profile_picture' => 'image|nullable',
            ];
        }

        return $rules ?? [];
    }


    private function emailValidation($isUpdate)
    {
        if ($isUpdate) {
            $user = $this->route('user');
            return 'required|email|unique:users,email,' . $user->id;
        }

        return 'required|email|unique:users';
    }

    private function passwordValidation($isUpdate)
    {
        $passwordRegexValidation = config('irc.password_regex_validation');


        if ($isUpdate) {
            if ($this->get('password', null)) {
                return "confirmed|min:8|regex:$passwordRegexValidation";
            }
            return '';
        }

        return "required|confirmed|min:8|regex:$passwordRegexValidation";

    }

    public function messages()
    {
        return [
            'password.regex' => trans('passwords.password_rules')
        ];
    }

    public function attributes()
    {
        return [
            'password' => trans('validation.attributes.password'),
            'name' => trans('validation.attributes.name'),
            'email' => trans('validation.attributes.email'),
            'profile_picture' => trans('validation.attributes.profile_picture'),
        ];
    }


}
