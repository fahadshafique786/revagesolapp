<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Response;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role_or_permission:super-admin|view-users', ['only' => ['index','fetchusersdata']]);
        $this->middleware('role_or_permission:super-admin|manage-users', ['only' => ['edit','store','editProfile','updateProfile','destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {$roles = Role::all();
        return view('users',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$customMessages = [
			'unique' => ':attribute already registered.'
		];

		if(!empty($request->id))
		{
			$this->validate($request, [
				'name' => 'required',
				'user_name' => 'required|unique:users,user_name,'.$request->id,
				'email' => 'required|email|unique:users,email,'.$request->id,
//				'phone' => 'required',
//				'is_status' => 'required',
			], $customMessages);
		}
		else
		{
			$this->validate($request, [
				'name' => 'required',
				'user_name' => 'required|unique:users,user_name',
				'email' => 'required|email|unique:users,email',
			//	'phone' => 'required',
                'role_id'=>'required|integer'
//				'is_status' => 'required',
			], $customMessages);
		}


		$input = array();
		$input['name'] = $request->name;
		$input['user_name'] = $request->user_name;
		$input['email'] = $request->email;
		$input['updated_by'] = auth()->user()->id;

		if(empty($request->id))
		{
			$input['user_type'] = "admin";
			$input['created_by']	= auth()->user()->id;
		}

		if(!empty($request->password))
			$input['password'] = Hash::make($request->password);

	//	$input['is_status'] = $request->is_status;
	//	$input['phone'] = $request->phone;

        $user   =   User::updateOrCreate(
                    [
                        'id' => $request->id
                    ],
                    $input);

        $user->syncRoles([$request->role_id]);

        return response()->json(['success' => true]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
//        $with = [
//            'roles',
//        ];

		$where = array('id' => $request->id);
        $user  = User::where($where)->first();
        $da  = DB::table('model_has_roles')
            ->select('role_id')
            ->where('model_id',$request->id)
            ->first();


        $user->role_id = $da->role_id;

//        dd($user->id,$user->role_id,$da->role_id);
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	public function destroy(Request $request)
    {
		 $input['deleted_by'] = auth()->user()->id;
		User::where('id',$request->id)->update($input);
        $user = User::where('id',$request->id)->delete();

        return response()->json(['success' => true]);
    }

	public function fetchusersdata()
    {
        if(request()->ajax()) {
            $with = [
                'permissions',
                'roles',
                'roles.permissions'
            ];
			$response = array();
			$Filterdata = User::with($with)->select('*')->orderBy('id','desc')->get();
			if(!empty($Filterdata))
			{
				$i = 0;
				foreach($Filterdata as $index => $user)
				{
					$status = (!empty($user->is_status)) ? "Active" : "Inactive";

					$response[$i]['srno'] = $i + 1;
					$response[$i]['id'] = $user->id;
					$response[$i]['name'] = $user->name;
					$response[$i]['user_name'] = $user->user_name;
					$response[$i]['email'] = $user->email;
					$response[$i]['role'] = $user->roles;
					$response[$i]['permissions'] = $user->permissions;
//					$response[$i]['phone'] = $user->phone;
//					$response[$i]['status'] = $status;

					if(auth()->user()->hasRole("super-admin") OR auth()->user()->can("manage-users") )
					{
						$response[$i]['action'] = '<a href="javascript:void(0)" class="btn edit" data-id="'. $user->id .'"><i class="fa fa-edit  text-info"></i></a>
											<a href="javascript:void(0)" class="btn delete" data-id="'. $user->id .'"><i class="fa fa-trash  text-danger"></i></a>';
					}
					else
					{
						/*if($user->user_type != "super-admin")
							$response[$i]['action'] = '<a href="javascript:void(0)" class="btn edit text-info" data-id="'. $user->id .'"><i class="fa fa-edit"></i></a>';*/
						//else
							$response[$i]['action'] = "-";
					}
					$i++;
				}
			}

            return datatables()->of($response)
            ->addIndexColumn()
            ->make(true);
        }
    }

	public function editProfile(Request $request)
    {

		$id = auth()->user()->id;
		$where = array('id' => $id);
        $user  = User::where($where)->first();

        return response()->json($user);
    }

	public function updateProfile(Request $request)
    {
		$customMessages = [
			'unique' => ':attribute already registered.'
		];

		$id = auth()->user()->id;

		$this->validate($request, [
			'name' => 'required',
//			'user_name' => 'required|unique:users,user_name,'.$id,
//			'email' => 'required|email|unique:users,email,'.$id,
			//'phone' => 'required',
//            'role_id'=>'required|array'
		], $customMessages);

		$input = array();
		$input['name'] = $request->name;


		if(!empty($request->password))
			$input['password'] = Hash::make($request->password);

		$where = array('id' => $id);
        $user   =   User::where($where)->update($input);

        //        $user->syncRoles($request->role_id);

        return response()->json(['success' => true]);


    }

	public function changePassword(Request $request)
    {
		$this->validate($request, [
			'password' => 'required',
		]);

        $validationResponse = [];

		if(!empty($request->current_password)){

            $current_password = bcrypt($request->current_password);
            //            $current_password = Hash::make($request->current_password);

		    dd($current_password);

		    $checkPassword = User::where('password',$current_password)
            ->where('id',$request->user_id);


            if($checkPassword->exists()){

                /******* Update Password ********/

                $input = array();
                $input['password'] = Hash::make($request->password);

                $user   =   User::where('id',$request->user_id)->update($input);

                return response()->json(['success' => true]);

            }
		    else{

                $validationResponse['message'] = "The given data was invalid.";
                $validationResponse['errors']['current_password'] = "Current Password not matched!";

                return Response::json($validationResponse,422);
                exit();

            }

		    exit();
        }




    }


}
