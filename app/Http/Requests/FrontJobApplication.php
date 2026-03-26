<?php

namespace App\Http\Requests;

use App\Job;
use App\Question;
use App\JobApplication;
use App\Rules\CheckApplication;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class FrontJobApplication extends CoreRequest
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
        $job = Job::select('id', 'required_columns', 'section_visibility')->where('id', $this->job_id)->first();
        $applicationMail = JobApplication::with('status')->where('email', request()->email)->where('job_id', $this->job_id)->first();
        $requiredColumns = $job->required_columns;
        $sectionVisibility = $job->section_visibility;
        if(!is_null($applicationMail) && $applicationMail->status->status != 'rejected' ){
            $rules = [
                'full_name' => 'required',
                'email' => [
                    'required','email', new CheckApplication
                    // Rule::unique('job_applications')->where(function ($query) {
                    //     return $query->where('job_id', $this->job_id);
                    // })
                ],
                'phone' => 'required|numeric',
    
            ];
    
        }else{
            $rules = [
                'full_name' => 'required',
                'email' => 'required|email',
                'phone' => 'required|numeric',
    
            ];
        }
        
        if($sectionVisibility){
            foreach ($sectionVisibility as $key => $section) {
                if ($section === 'yes') {
                    if ($key === 'profile_image') {
                        $rules = Arr::add($rules, 'photo', 'required|mimes:jpeg,jpg,png');
                    }
                    if ($key === 'resume') {
                        $rules = Arr::add($rules, 'resume', 'required|mimes:jpeg,jpg,png,doc,docx,rtf,xls,xlsx,pdf');
                    }
                    if ($key === 'terms_and_conditions') {
                        $rules = Arr::add($rules, 'term_agreement', 'required');
                    }
                }
            }
        }

        if ($requiredColumns['gender']) {
            $rules = Arr::add($rules, 'gender', 'required|in:male,female,others');
        }
        if ($requiredColumns['dob']) {
            $rules = Arr::add($rules, 'dob', 'required|date');
        }
        if ($requiredColumns['country']) {
            $rules = Arr::add($rules, 'country', 'required|integer|min:1');
            $rules = Arr::add($rules, 'state', 'required|integer|min:1');
            $rules = Arr::add($rules, 'city', 'required');
            $rules = Arr::add($rules, 'zip_code', 'required|integer');
        }

        if ($requiredColumns['address']) {
            $rules = Arr::add($rules, 'address', 'required|string');
        }

        // Work experience fields (enabled via admin -> job.required_columns['work_experience']).
        if (!empty($requiredColumns['work_experience'])) {
            $rules = Arr::add($rules, 'total_work_experience_years', 'required|numeric|min:0');
            $rules = Arr::add($rules, 'employer_name', 'required|string|max:255');
            $rules = Arr::add($rules, 'employer_address', 'required|string');
            $rules = Arr::add($rules, 'job_position', 'required|string|max:255');
            $rules = Arr::add($rules, 'employer_salary', 'required|numeric|min:0');
            $rules = Arr::add($rules, 'supervisor_name', 'required|string|max:255');
            $rules = Arr::add($rules, 'supervisor_mobile', 'required|string|max:50');
            $rules = Arr::add($rules, 'expected_monthly_salary', 'required|numeric|min:0');
        }

        $this->get('answer');
        if(!empty($this->get('answer')))
        {
            foreach($this->get('answer') as $key => $value){

                $answer = Question::where('id', $key)->first();
                if($answer->required == 'yes')
                $rules["answer.{$key}"] = 'required';
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'answer.*.required' => 'This answer field is required.',
            'dob.required' => 'Date of Birth field is required.',
            'country.min' => 'Please select country.',
            'address.required' => 'Address field is required.',
            'total_work_experience_years.required' => 'Total Work Experience (in years) is required.',
            'employer_name.required' => 'Most Recent / Current Employer name is required.',
            'employer_address.required' => 'Employer address is required.',
            'job_position.required' => 'Job position is required.',
            'employer_salary.required' => 'Salary is required.',
            'supervisor_name.required' => 'Immediate supervisor name is required.',
            'supervisor_mobile.required' => 'Supervisor mobile number is required.',
            'expected_monthly_salary.required' => 'Expected monthly salary is required.',
            'state.min' => 'Please select state.',
            'city.required' => 'Please enter city.',
            'email.unique' => 'You have already applied for this job with this email. Try different one.'
        ];
    }
}
