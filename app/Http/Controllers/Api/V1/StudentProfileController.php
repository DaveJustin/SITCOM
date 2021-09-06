<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\UpdateStudentProfileRequest;
use App\Http\Requests\StoreStudentProfileRequest;
use App\Http\Resources\StudentProfileResource;
use App\Http\Resources\ProfileCollection;
use App\Http\Requests\GetStudentRequest;
use App\Http\Resources\StudentResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Passport\Token;
use App\Models\Student;
use App\Rules\ValidId;
use App\Models\User;
use Hash;

class StudentProfileController extends Controller
{
    public function index(GetStudentRequest $request, Student $student)
    {
        $students = $student->query();

        if($request->has('tup_id'))
        {
            $students->where('student_tup_id','LIKE', '%'.$request->tup_id.'%');
        }

        if($request->has('name'))
        {
            $students->whereHas('User', function($query) use ($request) {
                $query->where('fname','LIKE', '%'.$request->name.'%')
                    ->orWhere('lname','LIKE', '%'.$request->name.'%');
            });
        }

        if($request->has('course'))
        {
            $student->whereHas('Course', function($query) use ($request) {
                $query->where('course_name', 'LIKE', '%'.$request->course.'%');
            });
        }

        $lists = $students->latest()->paginate(12);

        return new ProfileCollection(StudentProfileResource::collection($lists),$lists);
    }

    // FOR COORDINATOR CREATE FUNCTION
    public function store(StoreUserRequest $userRequest,StoreStudentProfileRequest $request)
    {
        $userRequest['password'] = Hash::make($userRequest['password']);
    
        $user =  User::create(array_merge($userRequest->only(['fname','lname','email','password']),
                            ['email_verified_at' => now(),
                            'state'=> 1, 'role' => 3]));

        $student = Student::create(array_merge(['user_id' => $user->id],$request->validated()));
        return new StudentProfileResource(Student::find($student->id));
    }


    public function show($id)
    {
        return new StudentProfileResource(Student::findOrFail($id));
    }

    //FOR STUDENT ITSELF AND UPDATE ABILITY FOR COORDINATOR
    public function update($id,UpdateStudentProfileRequest $request)
    {
        if($request->has('password'))
        {
            $request['password'] = Hash::make($request['password']);
        }
        
        if($request->has('verified_at'))
        {
            $request['email_verified_at'] = now();
        }

        $student = Student::findOrFail($id);
        $user_id = $student->user_id;
        $student->update(array_merge($request->except(['student_id',
                                                'fname','lname',
                                                'email','password',
                                                'password_confirmation',
                                                'verified_at',
                                                'email_verified_at'],
                                                ['user_id' => $user_id])));
        $student->user()->update($request->except(['student_gender',
                                                'student_birthday',
                                                'student_contact',
                                                'course_id',
                                                'student_address',
                                                'student_id',
                                                'student_tup_id',
                                                'password_confirmation',
                                                'verified_at',
                                                'student_link']));

        return new StudentProfileResource($student->find($id));
    }


    // SET STATE TO 0 FOR INACTIVE ACCOUNT
    public function destroy($tup_id)
    {
        User::whereHas('Student', function($query) use($tup_id){
            $query->where('student_tup_id', $tup_id);
        })->update(['state' => 0]);
        
        $student = Student::where('student_tup_id',$tup_id)->first();
        
        //Revoke token of user 
        Token::where('name', $student->student_tup_id)
            ->orWhere('name',$student->user->email)
            ->update(['revoked' => true]);

        return new StudentProfileResource($student);
    }
}