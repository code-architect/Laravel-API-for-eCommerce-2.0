<?php

namespace App\Http\Controllers\User;

use App\Mail\UserCreated;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{

    public function __construct()
    {
        $this->middleware('client.credentials')->only(['store', 'resend']);
        $this->middleware('auth:api')->except(['store', 'verify', 'resend']);
        $this->middleware('transform.input:'. UserTransformer::class)->only(['store', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::all();
        return $this->showAll($users);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:6|confirmed',
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['admin'] = User::REGULAR_USER;

        $user = User::create($data);

        return $this->showOne($user);
    }


    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'email'     => 'email|unique:users, email'.$user->id,
            'password'  => 'min:6|confirmed',
            'admin'     => 'in:'. User::ADMIN_USER . ',' . User::REGULAR_USER,
        ];

        if($request->has('name')){
            $user->name = $request->name;
        }

        // If user register a new email id
        if($request->has('email') && $user->email != $request->email){
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }

        if($request->has('password')){
            $user->password = bcrypt($request->password);
        }

        if($request->has('admin')){
            if(!$user->isVerified()){
                return $this->errorResponse('Only verified users can modify the admin field', 409);
            }
            $user->admin = $request->admin;
        }

        // If nothing has changed
        if(!$user->isDirty()) {
            return $this->errorResponse('You need to specify a different value to update', 422);
        }
        $user->save();
        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->showOne($user);
    }


    //-------------------------- Native Methods ------------------------------------//
    public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();

        $user->verified = User::VERIFIED_USER;
        $user->verification_token = null;   // after verification remove the token
        $user->save();

        return $this->showMessage('The account has been verified successfully');
    }

    /**
     * Resend mail to user if not received  or has been deleted by any action
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(User $user)
    {
        if($user->isVerified())
        {
            return $this->errorResponse('The user is already verified', 409);
        }
        // retry to send mail 5 times every 1 second
        retry(5, function() use($user) {
            Mail::to($user)->send(new UserCreated($user));
        }, 100);
        return $this->showMessage("The verification email has been send");
    }
}
