<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sports;

class SportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role_or_permission:super-admin|view-sports', ['only' => ['index','fetchsportsdata']]);
        $this->middleware('role_or_permission:super-admin|manage-sports',['only' => ['edit','store','editProfile','updateRole','destroy']]);
    }
    public function index(Request $request)
    {
        return view('sports');
    }

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

        $user   =   Sports::updateOrCreate(
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
        $user  = Sports::where($where)->first();
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
        Sports::where('id',$request->id)->update($input);
        $user = Sports::where('id',$request->id)->delete();

        return response()->json(['success' => true]);
    }

    public function fetchsportsdata()
    {
        if(request()->ajax()) {

            $response = array();
            $Filterdata = Sports::select('*')->orderBy('id','desc')->get();
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

                    if(auth()->user()->hasRole('super-admin') || auth()->user()->can('manage-sports'))
                  //  if(auth()->user()->user_type == "superadmin")
                    {
                        $response[$i]['action'] = '<a href="javascript:void(0)" class="btn btn-primary edit" data-id="'. $user->id .'"><i class="fa fa-edit"></i></a>
											<a href="javascript:void(0)" class="btn btn-danger delete" data-id="'. $user->id .'"><i class="fa fa-trash"></i></a>';
                    }
                    else
                    {
                       /* if($user->user_type != "superadmin")
                            $response[$i]['action'] = '<a href="javascript:void(0)" class="btn edit text-info" data-id="'. $user->id .'"><i class="fa fa-edit"></i></a>';
                        else*/
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
        $user  = Sports::where($where)->first();
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
        $user   =   Sports::where($where)->update($input);

        return response()->json(['success' => true]);


    }
}
