<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Sports;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role_or_permission:super-admin|view-roles', ['only' => ['index','fetchRolesdata']]);
        $this->middleware('role_or_permission:super-admin|manage-roles',['only' => ['edit','store','editProfile','updateRole','destroy']]);
    }
    public function index(Request $request)
    {
        $permissions = Permission::all();
        return view('roles.index',compact('permissions'));
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
            'unique' => ':attribute already exist.'
        ];

        if(!empty($request->id))
        {
            $this->validate($request, [
               // 'name' => 'required|unique:roles,name,\'.$request->id',
                'name' => 'required|string',
                Rule::unique('roles', 'name')->ignore($request->id),
                'permissions' => 'required|array',

//				'phone' => 'required',
//				'is_status' => 'required',
            ], $customMessages);
        }
        else
        {
            $this->validate($request, [
                'name' => 'required|unique:roles,name',
                'permissions' => 'required|array',

//				'is_status' => 'required',
            ], $customMessages);
        }

        $input = array();
        $input['name'] = $request->name;




        $role  =  Role::updateOrCreate(
            [
                'id' => $request->id
            ],
            $input);
        $role -> syncPermissions($request->permissions);

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
        $with = [
            'permissions',
        ];
        $where = array('id' => $request->id);
        $user  =Role::with($with)->where($where)->first();
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
      //  $input['deleted_by'] = auth()->user()->id;
       // Role::where('id',$request->id);
        $user = Role::where('id',$request->id)->delete();

        return response()->json(['success' => true]);
    }

    public function fetchRolesdata()
    {
        if(request()->ajax()) {

            $response = array();
            $Filterdata = Role::select('*')->orderBy('id','desc')->get();
            if(!empty($Filterdata))
            {
                $i = 0;
                foreach($Filterdata as $index => $role)
                {


                    $response[$i]['srno'] = $i + 1;
                    $response[$i]['id'] = $role->id;
                    $response[$i]['name'] = $role->name;
                    $response[$i]['permissions'] = $role->permissions;;

                    if(auth()->user()->hasRole("super-admin") OR auth()->user()->can("manage-roles") )
                    {
                        $response[$i]['action'] = '<a href="javascript:void(0)" class="btn  editRole" data-id="'. $role->id .'"><i class="fa fa-edit  text-info"></i></a>
											<a href="javascript:void(0)" class="btn  delete" data-id="'. $role->id .'"><i class="fa fa-trash text-danger"></i></a>';
                    }
                    else
                    {
                        /*if(!auth()->user()->hasRole('superadmin'))
                            $response[$i]['action'] = '<a href="javascript:void(0)" class="btn editRole text-info" data-id="'. $role->id .'"><i class="fa fa-edit"></i></a>';
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
        $user  = User::where($where)->first();
        return response()->json($user);
    }

    public function updateRole(Request $request)
    {
        $customMessages = [
            'unique' => ':attribute already registered.'
        ];

        $id = auth()->user()->id;

        $this->validate($request, [
            'name' => 'required|string',
            Rule::unique('roles', 'name')->ignore($id),
            'permissions' => 'required|array',
        ], $customMessages);

        $input = array();
        $input['name'] = $request->name;
     //   $input['permissions'] = $request->permisions;


        $where = array('id' => $id);
        $role  =   Role::where($where)->update($input);
        $role -> syncPermissions($request->permissions);

        return response()->json(['success' => true]);


    }
}
