<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\User;
use App\Category;
use Illuminate\Support\Facades\Mail;
use App\Transformers\UserTransformer;

class UserController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:'.UserTransformer::class)->only(['store', 'update']);
    }


    public function index()
    {
        $users = User::all();

        $categories = Category::all();

        //return $this->showAll($users);

       return response()->json(['data' => $users, 'categories' => $categories], 200);
        //return response()->json($categories,  200);

        // return $users;


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['admin'] = User::REGULAR_USER;

        $user = User::create($data);

        return $this->showOne($user, 201);

        //return response()->json(['data' => $user], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
       // $user = User::findOrFail($id);

        return $this->showOne($user);

       // return response()->json(['data' => $user], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //$user = User::findOrFail($id);

        $rules = [
            'email' => 'email|unique:users, email' . $user->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:'. User::ADMIN_USER. ','. User::REGULAR_USER,
        ];

        if($request->has('name')) {
            $user->name = $request->name;
        }

        if($request->has('email') && $user->email != $request->email)
        {
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }

        if($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        if($request->has('admin')) {
            if(!$user->isVerified()) {
                //return response()->json(['error' => 'Only verified user can change the admin field!', 'code' => 409], 409);
                return $this->errorResponse( 'Only verified user can change the admin field!', 409);
            }
            $user->admin = $request->admin;

            if(!$user->isDirty()) {
                //return response()->json(['error' => 'Please provide different value for update', 'code' => 422], 422);
                return $this->errorResponse('Please provide different value for update', 422);
            }

            $user->save();

            return $this->showOne($user);

            //return response()->json(['data' => $user], 200);
        }

        //$this->validate($request, $rules);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
       // $user = User::findOrFail($id);

        $user->delete();

        return response()->json(['data' => $user], 200);
    }

    public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();

        $user->verified = User::VERIFIED_USER;
        $user->verification_token = null;

        $user->save();

        return $this->showMessage('The account has been verified successfully');

        // return response()->json('Verified successfully.');
    }

    public function resend(User $user)
    {
        if($user->isVerified()) 
        {
            return $this->errorResponse('This user is already verified', 409);
        }

        retry(5, function() use ($user) {
            Mail::to($user)->send(new UserCreated($user));
        }, 100);

        return $this->showMessage('The verification email has been resend');
    }
}
