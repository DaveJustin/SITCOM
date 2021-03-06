<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\StoreCoordinatorRequest;
use App\Http\Requests\StoreSupervisorRequest;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Resources\UserAuthResource;
use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Models\Coordinator;
use App\Models\Supervisor;
use App\Models\Student;
use App\Models\Course;
use App\Models\User;
use Hash;

class RegisterController extends Controller
{

    protected $auth;

    public function __construct()
    {
        $this->auth = new AuthService();
    }

    public function studentRegister(StoreStudentRequest $request, Student $student)
    {   
        $request['password'] = Hash::make($request['password']);
        $user = User::firstOrCreate(array_merge($request->only('fname','lname','email','password'),['role'=> 3]));
        $stu = $student->firstOrCreate(array_merge(['user_id'=>$user->id],$this->auth->mapToNewName('student',$request->only('course_id','contact','address','tup_id','gender'))));
        $stu->save();
        if($stu)
        {    
            $token = $user->createToken($request->email, ['student'])->accessToken;
            $user->setToken($token);
            return new UserAuthResource($user);
        }
        return '';
    }

    public function coordinatorRegister(StoreCoordinatorRequest $request, Coordinator $coordinator)
    {
        $request['password'] = Hash::make($request['password']);
        $request['department_id'] = Course::where('id',$request->course_id)->first()->department_id;
        
        $user = User::firstOrCreate(array_merge($request->only('fname','lname','email','password'),['role'=> 4, 'email_verified_at'=>now()]));
        $coor = $coordinator->firstOrCreate(array_merge(['user_id'=>$user->id],$this->auth->mapToNewName('coordinator',$request->only('department_id','course_id','contact','position','gender'))));
        $user->save();
        $coor->save();
        if($coor)
        {
            $token = $user->createToken($request->email,['coordinator'])->accessToken;
            $user->setToken($token);
            return new UserAuthResource($user);
        }
        return '';
    }

    public function supervisorRegister(StoreSupervisorRequest $request, Supervisor $supervisor)
    {
        $request['password'] = Hash::make($request['password']);
        $user = User::firstOrCreate(array_merge($request->only('fname','lname','email','password'),['role'=> 5]));
        $visor = $supervisor->firstOrCreate(array_merge(['user_id'=>$user->id],$this->auth->mapToNewName('supervisor',$request->only('company_id','contact','position','gender'))));
        $visor->save();
        if($visor)
        { 
            $token = $user->createToken($request->email,['supervisor'])->accessToken;
            $user->setToken($token);
            return new UserAuthResource($user);
        }
        return '';
    }

    // public function adminRegister(StoreSupervisorRequest $request, Supervisor $supervisor)
    // {
    //     $user = $supervisor->firstOrCreate($this->auth->mapToNewName('supervisor',$request->validated()));
    //     if($user)
    //     {
    //         $token = $user->createToken($request->email,['supervisor'])->accessToken;
    //         $user->setToken($token);
    //         $user->save();
    //         return new SupervisorAuthResource($user);
    //     }
    //     return '';
    // }
}
