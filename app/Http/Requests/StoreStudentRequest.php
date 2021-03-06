<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidNumber;
use App\Rules\ValidId;

class StoreStudentRequest extends FormRequest
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

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['status'=>'failed',
                                                        'message'=>'unprocessable entity',
                                                        'errors'=>$validator->errors()->all()], 422));
    }

    public function attributes()
    {
        return [
            'student_fname' => 'Firstname',
            'student_lname' => 'Lastname',
            'course_id'=> 'Course'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fname'=>['required','string','max:20'],
            'lname'=>['required','string','max:20'],
            'gender'=>['nullable'],
            'email'=>['required','email:rfc,dns','string','max:50', 'unique:App\Models\User,email'],
            'password'=>['required','confirmed','string','min:8'],
            'course_id'=>['required','integer'],
            'contact'=> ['nullable',new ValidNumber()],
            'address'=>['nullable','string','max:250'],
            'tup_id'=> [new ValidId(),'unique:App\Models\Student,student_tup_id'],
            // 'birthday'=>['nullable','date_format:Y-m-d'],
            // link
            // state
        ];
    }

    public function messages()
    {
        return [
            'tup_id.unique' => 'TUPT ID has already been taken'
        ];
    }
}
