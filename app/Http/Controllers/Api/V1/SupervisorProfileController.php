<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\SupervisorProfileResource;
use App\Http\Requests\UpdateSupervisorProfile;
use App\Http\Resources\ProfileCollection;
use App\Notifications\AccountVerified;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Passport\Token;
use App\Models\Supervisor;
use App\Models\User;
use Hash;

class SupervisorProfileController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string',
            'limit'=>'nullable|integer',
            'company' => 'nullable|string'
        ]);

        //TOOD: 
        //ADD TABLE FOR HANDLED TRAINEES HERE ON WITH
        $visor = Supervisor::with(['user']);
        
        if($request->has('name'))
        {
            $visor->whereHas('User', function($query) use ($request) {
                $query->where('fname','LIKE', '%'.$request->name.'%')
                    ->orWhere('lname','LIKE', '%'.$request->name.'%');
            });
        }

        if($request->has('company'))
        {
            $visor->whereHas('Company', function($query) use ($request) {
                $query->where('comp_name', 'LIKE', '%'.$request->company.'%');
            });
        }

        // $request->has('limit') ? $limit = $request->limit : $limit = 12;

        // $lists = $visor->latest()->paginate($limit);
        $lists = $visor->get();

        return new ProfileCollection(SupervisorProfileResource::collection($lists),$lists);
    }

    public function update($id, UpdateSupervisorProfile $request)
    {
        $visor = Supervisor::findOrFail($id);
        $request['user_id'] = $visor->user_id;

        if($request->has('password'))
        {
            $request['password'] = Hash::make($request['password']);
        }
        
        if($request->has('verified_at') && $request->verified_at == 1)
        {
            $request['email_verified_at'] = now();
        }

        $visor->update($request->except(['fname','lname','email','password','supervisor_id','state','email_verified_at','verified_at']));
        $visor->user()->update($request->only(['fname','lname','email','password','email_verified_at','state']));

        $visor = Supervisor::with(['user'])->find($id);
        
        if($visor->user->email_verified_at !== null  && $request->verified_at == 1)
        {
            // Email student when account is verified
            $user = $visor;
            $visor->user->notify(new AccountVerified($user));
        }

        return (SupervisorProfileResource::make($visor))->additional(['message'=>'updated']);
    }


    public function show($id)
    {
        // return new SupervisorProfileResource(Supervisor::with(['user'])->findOrFail($id));
        return new SupervisorProfileResource(Supervisor::with(['user','company'])->where('user_id',$id)->first());
    }

    public function destroy($id)
    {
        User::whereHas('Supervisor', function($query) use($id){
            $query->where('id', $id);
        })->update(['state' => 0]);

        $visor= Supervisor::with(['user'])->findOrFail($id);
        
        //Revoke token of user 
        Token::where('name', $visor->user->email)
        ->update(['revoked' => true]);

        return (SupervisorProfileResource::make($visor))
                ->additional(['message'=>'Account has been deactivated']);
    }
}
