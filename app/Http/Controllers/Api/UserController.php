<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($flag)
    {
    
    // flag -> 1 (Active)
    // flag -> 0 (All)
    // All users (Active and Inactive)
    // Active
    //  $users = User::all();
    // $users = User::select('name', 'email')->where('status', 1)->get();

    $query = User::select('name','email');
    if($flag == 1) {
        $query->where('status', 1);
    }elseif ($flag == 0){
        //empty
    }else{
        return response()->json([
            'message' => 'Invalid parameter passed, it can be either 1 or 0',
            'status' => 0
        ], 
        400
    );
    }
    $users = $query->get();

      if(count($users) > 0) {
        //user exists
       $response = [
        'message' => count($users) . 'user found',
        'status' => 1,
        'data' => $users

       ];
      }else{
        //doesn't exists
        $response = [
            'message' => count($users) . 'user found',
            'status' => 0,
        ];
      }
      return response()->json( $response, 200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required',  'min:8', 'confirmed'],
            'password_confirmation' => ['required']

        ]);
        if($validator->fails()){
            return response()->json($validator->messages(), 400);
        }
        else{
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                // 'password' => Hash::make($request->password)
                'password' => $request->password
            ];
            
            DB::beginTransaction();
            try{
                $user = User::create($data);
                DB::commit();

            } catch(\Exception $e) {

                DB::rollBack();
                p($e->getMessage());
                $user = null;
            }

            if($user != null){
                return response()->json([
                    'message' => 'User registerd successfully'

                ], 200);
            }else{
                return response()->json([
                    'message' => 'Internal server error'

                ],
                 500
                );
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if(is_null($user)){
            $response = [
                'message' => 'User not found',
                'status' => 0,

            ];
        }else{
            $response = [
                'message' => 'User  found',
                'status' => 1,
                'data' => $user
            
            ];
        }
        return response()->json($response, 200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        // p($request->all());
        // die;
        if(is_null($user)){
            // user doesn't exists
            return response()->json(
                [
                'status' => 0,
                'message' => 'User does not exists'
 
                ], 
                404
        );
        }else{
            DB::beginTransaction();
            try{
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->pincode = $request['pincode'];
            $user->contact = $request['contact'];
            $user->address = $request['address'];
            $user->save();
            DB::commit();
        }
        catch(\Exception $e){
            DB::rollBack();
            $user = null;
        }
        if(is_null($user)){
            return response()->json(
                [
                    'status' => 0,
                    'message' => 'Internal server error',
                    'error_msg' => $e->getMessage()
                ],
                500
            );
        
    }else{
        return response()->json(
            [
                'status' => 1,
                'message' => 'Data updated successfully'
            ],
            200
        );
    }

 function changepassword(Request $request, $id){
        $user = User::find($id);
        // p($request->all());
        // die;
        if(is_null($user)){
            // user doesn't exists
            return response()->json(
                [
                'status' => 0,
                'message' => 'User does not exists'
 
                ], 
                404
        );
        }else{
            //main->change password code
            if($user->password == $request['old_password']){
                //change
                if($request['new_password'] == $request['confirm_password']){
                    //change
                    DB::beginTransaction();
                    try{
                        $user->password = $request['New_password'];
                        $user->save();
                        DB::commit();

                    }catch(\Exception $e){
                        
                         $user = null;
                         DB:: rollBack();

                    
                    }
                    if(is_null($user)){
                        return response()->json(
                            [
                                'status' => 0,
                                'message' => 'Internal server error',
                                'error_msg' => $e->getMessage()
                            ],
                            500
                        );
                    
                }else{
                    return response()->json(
                        [
                            'status' => 1,
                            'message' => 'Password updated successfully'
                        ],
                        200
                    );
                }
            }else{
                    return response()->json(
                        [
                        'status' => 0,
                        'message' => 'New password and confirm password does not match'
         
                        ], 
                        400
                );

            

            }
            }else{
                return response()->json(
                    [
                    'status' => 0,
                    'message' => 'Old password does not match'
     
                    ], 
                    400
            );

            }
        }
    


        }
}
        

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     function destroy($id)
    {
        $user = User::find($id);
        if(is_null($user)) {
            $response = [
                'message' => "User doesn't exists",
                'status' => 0 
            ];
            $resCode = 404;
        }else{
            DB::beginTransaction();
           
            try{
                $user->delete();
                DB::commit();
                $response = [
                    'message' => "User deleted successfully",
                    'status' => 1
                ];
                $resCode = 200;

            }catch (\Exception $e) {
                DB::rollBack();
                $response = [
                    'message' => "Internal server error",
                    'status' => 0
                ];
                $resCode = 500;
                


            }
        }
        return response()->json($response, $resCode);
    }
}
}