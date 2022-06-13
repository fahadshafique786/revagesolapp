<?php

namespace App\Http\Controllers;

use App\Models\Servers;
use App\Models\Sports;
use Illuminate\Http\Request;

class ServersController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth');
//        $this->middleware('role_or_permission:super-admin|view_servers', ['only' => ['index','fetchserversdata']]);
//        $this->middleware('role_or_permission:super-admin|manage-servers',['only' => ['edit','store','destroy']]);
    }

    public function index(Request $request)
    {
        $sports_list = Sports::all();
        return view('servers')->with('sports_list',$sports_list);
    }

    public function store(Request $request)
    {
        if(!empty($request->id))
        {
            $this->validate($request, [
                'name' => 'required',
                'sports_id' => 'required',
            ]);
        }
        else
        {
            $this->validate($request, [
                'name' => 'required',
                'sports_id' => 'required',
            ]);
        }

        $input = array();
        $input['name'] = $request->name;
        $input['sports_id'] = $request->sports_id;
        $input['link'] = $request->link;
        $input['isHeader'] = $request->isHeader;
        $input['isPremium'] = $request->isPremium;

        $servers   =   Servers::updateOrCreate(
            [
                'id' => $request->id
            ],
            $input);

        return response()->json(['success' => true]);
    }

    public function edit(Request $request)
    {
        $where = array('id' => $request->id);
        $servers  = Servers::where($where)->first();
        return response()->json($servers);
    }

    public function destroy(Request $request)
    {
        $servers = Servers::where('id',$request->id)->delete();
        return response()->json(['success' => true]);
    }

    public function fetchserversdata()
    {
        if(request()->ajax()) {

            $response = array();
            $Filterdata = Servers::select('servers.*','sports.name as sport_name')
                ->join('sports', function ($join) {
                    $join->on('servers.sports_id', '=', 'sports.id');
                })->orderBy('servers.id','desc')->get();

            if(!empty($Filterdata))
            {
                $i = 0;
                foreach($Filterdata as $index => $obj)
                {

                    $response[$i]['srno'] = $i + 1;
                    $response[$i]['name'] = $obj->name;
                    $response[$i]['sport_name'] = $obj->sport_name;
                    $response[$i]['link'] = $obj->link;
                    $response[$i]['isHeader'] = getBooleanStr($obj->isHeader,true);
                    $response[$i]['isPremium'] = getBooleanStr($obj->isPremium,true);
                    if(auth()->user()->hasRole('super-admin') || auth()->user()->can('manage_servers'))
                    {
                        $response[$i]['action'] = '<a href="javascript:void(0)" class="btn edit" data-id="'. $obj->id .'"><i class="fa fa-edit  text-info"></i></a>
											<a href="javascript:void(0)" class="btn delete " data-id="'. $obj->id .'"><i class="fa fa-trash-alt text-danger"></i></a>';
                    }
                    else
                    {
                        $response[$i]['action'] = "-";
                    }
                    $i++;
                }
            }

            return datatables()->of($response)
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }
    }




}
