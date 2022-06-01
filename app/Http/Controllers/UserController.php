<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('users');
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
				'phone' => 'required',
				'is_status' => 'required',
			], $customMessages);
		}
		else
		{
			$this->validate($request, [
				'name' => 'required',
				'user_name' => 'required|unique:users,user_name',
				'email' => 'required|email|unique:users,email',
				'phone' => 'required',
				'is_status' => 'required',
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

		$input['is_status'] = $request->is_status;
		$input['phone'] = $request->phone;

        $user   =   User::updateOrCreate(
                    [
                        'id' => $request->id
                    ],
                    $input);

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
		$where = array('id' => $request->id);
        $user  = User::where($where)->first();
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

			$response = array();
			$Filterdata = User::select('*')->orderBy('id','desc')->get();
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
					$response[$i]['phone'] = $user->phone;
					$response[$i]['status'] = $status;

					if(auth()->user()->user_type == "superadmin")
					{
						$response[$i]['action'] = '<a href="javascript:void(0)" class="btn btn-primary edit" data-id="'. $user->id .'"><i class="fa fa-edit"></i></a>
											<a href="javascript:void(0)" class="btn btn-danger delete" data-id="'. $user->id .'"><i class="fa fa-trash"></i></a>';
					}
					else
					{
						if($user->user_type != "superadmin")
							$response[$i]['action'] = '<a href="javascript:void(0)" class="btn edit text-info" data-id="'. $user->id .'"><i class="fa fa-edit"></i></a>';
						else
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
			'user_name' => 'required|unique:users,user_name,'.$id,
			'email' => 'required|email|unique:users,email,'.$id,
			'phone' => 'required',
		], $customMessages);

		$input = array();
		$input['name'] = $request->name;
		$input['user_name'] = $request->user_name;
		$input['email'] = $request->email;
		$input['updated_by'] = $id;

		if(!empty($request->password))
			$input['password'] = Hash::make($request->password);

		$input['phone'] = $request->phone;

		$where = array('id' => $id);
        $user   =   User::where($where)->update($input);

        return response()->json(['success' => true]);


    }
}
