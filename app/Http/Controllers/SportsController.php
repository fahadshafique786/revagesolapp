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
//                'multi_league' => 'required'
            ]);
        }
        else
        {
            $this->validate($request, [
                'name' => 'required|unique:sports,name,'.$request->id,
                'sports_type' => 'required',
//                'multi_league' => 'required'
            ]);
        }

        $input = array();
        $input['name'] = $request->name;
        $input['multi_league'] = $request->multi_league;
        $input['sports_type'] = $request->sports_type;
        $input['image_required'] = $request->image_required;


        if($request->hasFile('sport_logo'))
        {
            $file_original_name  = '';
            $file_unique_name = '';

            $fileobj				= $request->file('sport_logo');
            $file_original_name 	= $fileobj->getClientOriginalName('sport_logo');
            $file_extension_name 	= $fileobj->getClientOriginalExtension('sport_logo');
            $file_unique_name 		= strtolower($request->name).'-'.time().rand(1000,9999).'.'.$file_extension_name;
            $destinationPath		= public_path('/uploads/');
            $fileobj->move($destinationPath,$file_unique_name);

            $input['icon'] = $file_unique_name;
        }

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
        $sports  = Sports::where($where)->first();
        return response()->json($sports);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $sports = Sports::where('id',$request->id)->delete();
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

                    $sport_logo =  (!empty($sports->icon)) ? '<img class="dataTable-image" src="'.url("/uploads/").'/'.$sports->icon.'" />' : '<a href="javascript:void(0)" class="" ><i class="fa fa-image text-xl"></i></a>';

                    $response[$i]['srno'] = $i + 1;
                    $response[$i]['icon'] = $sport_logo;
                    $response[$i]['name'] = $sports->name;
                    $response[$i]['sports_type'] = $sports->sports_type;
                    $response[$i]['multi_league'] = $sports->multi_league;
                    $response[$i]['image_required'] = $sports->image_required;
                    if(auth()->user()->hasRole('super-admin') || auth()->user()->can('manage-sports'))
                    {
                        $response[$i]['action'] = '<a href="javascript:void(0)" class="btn edit" data-id="'. $sports->id .'"><i class="fa fa-edit  text-info"></i></a>
											<a href="javascript:void(0)" class="btn delete " data-id="'. $sports->id .'"><i class="fa fa-trash-alt text-danger"></i></a>';
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
                ->rawColumns(['icon','action'])
                ->make(true);
        }
    }
}
