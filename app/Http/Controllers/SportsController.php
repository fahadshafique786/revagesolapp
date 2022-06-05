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
        if(!empty($request->id))
        {
            $this->validate($request, [
                'name' => 'required|unique:sports,name,'.$request->id,
                'sports_type' => 'required',
                'multi_league' => 'required'
            ]);
        }
        else
        {
            $this->validate($request, [
                'name' => 'required|unique:sports,name,'.$request->id,
                'sports_type' => 'required',
                'multi_league' => 'required'
            ]);
        }

        $input = array();
        $input['name'] = $request->name;
        $input['multi_league'] = $request->multi_league;
        $input['sports_type'] = $request->sports_type;
        $input['image_required'] = $request->image_required;

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
                foreach($Filterdata as $index => $sports)
                {

                    $response[$i]['srno'] = $i + 1;
                    $response[$i]['icon'] = $sports->icon;
                    $response[$i]['name'] = $sports->name;
                    $response[$i]['sports_type'] = $sports->sports_type;
                    $response[$i]['multi_league'] = $sports->multi_league;
                    $response[$i]['image_required'] = $sports->image_required;

                    if(auth()->user()->hasRole('super-admin') || auth()->user()->can('manage-sports'))
                    {
                        $response[$i]['action'] = '<a href="javascript:void(0)" class="btn edit" data-id="'. $sports->id .'"><i class="fa fa-edit  text-info"></i></a>
											<a href="javascript:void(0)" class="btn delete" data-id="'. $sports->id .'"><i class="fa fa-trash text-danger"></i></a>';
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
