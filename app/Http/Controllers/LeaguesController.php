<?php

namespace App\Http\Controllers;

use App\Models\Teams;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use App\Models\Leagues;
use App\Models\Schedules;
use App\Models\Sports;

class LeaguesController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth');
//        $this->middleware('role_or_permission:super-admin|view-sports', ['only' => ['index','fetchsportsdata']]);
//        $this->middleware('role_or_permission:super-admin|manage-sports',['only' => ['edit','store','editProfile','updateRole','destroy']]);
    }

    public function index(Request $request)
    {
        $sports_list = Sports::all();
        return view('leagues')->with('sports_list',$sports_list);
    }

    public function store(Request $request)
    {
        if(!empty($request->id))
        {
            $this->validate($request, [
                'name' => 'required|unique:leagues,name,'.$request->id,
                'sports_id' => 'required',
            ]);
        }
        else
        {
            $this->validate($request, [
                'name' => 'required|unique:leagues,name,'.$request->id,
                'sports_id' => 'required',
            ]);
        }

        $input = array();
        $input['name'] = $request->name;
        $input['sports_id'] = $request->sports_id;


        if($request->hasFile('league_icon'))
        {
            $file_original_name  = '';
            $file_unique_name = '';

            $fileobj				= $request->file('league_icon');
            $file_original_name 	= $fileobj->getClientOriginalName('league_icon');
            $file_extension_name 	= $fileobj->getClientOriginalExtension('league_icon');
            $file_unique_name 		= strtolower($request->name).'-'.time().rand(1000,9999).'.'.$file_extension_name;
            $destinationPath		= public_path('/uploads/leagues/');
            $fileobj->move($destinationPath,$file_unique_name);

            $input['icon'] = $file_unique_name;
        }

        $user   =   Leagues::updateOrCreate(
            [
                'id' => $request->id
            ],
            $input);

        return response()->json(['success' => true]);
    }

    public function edit(Request $request)
    {
        $where = array('id' => $request->id);
        $leagues  = Leagues::where($where)->first();
        return response()->json($leagues);
    }

    public function destroy(Request $request)
    {

//        Schedule::where('leagues_id',$request->id)->delete();
        Teams::where('leagues_id',$request->id)->delete();
        Leagues::where('id',$request->id)->delete();

        return response()->json(['success' => true]);

    }

    public function fetchleaguesdata()
    {
        if(request()->ajax()) {

            $response = array();
            $Filterdata = Leagues::select('leagues.*','sports.name as sport_name')
                ->join('sports', function ($join) {
                    $join->on('leagues.sports_id', '=', 'sports.id');
                })->orderBy('leagues.id','asc')->get();

            if(!empty($Filterdata))
            {
                $i = 0;
                foreach($Filterdata as $index => $leagues)
                {

                    $sport_logo =  (!empty($leagues->icon)) ? '<img class="dataTable-image" src="'.url("/uploads/leagues/").'/'.$leagues->icon.'" />' : '<a href="javascript:void(0)" class="" ><i class="fa fa-image text-xl"></i></a>';

                    $response[$i]['srno'] = $i + 1;
                    $response[$i]['icon'] = $sport_logo;
                    $response[$i]['name'] = $leagues->name;
                    $response[$i]['sport_name'] = $leagues->sport_name;
                    if(auth()->user()->hasRole('super-admin') || auth()->user()->can('manage_leagues'))
                    {
                        $response[$i]['action'] = '<a href="javascript:void(0)" class="btn edit" data-id="'. $leagues->id .'"><i class="fa fa-edit  text-info"></i></a>
											<a href="javascript:void(0)" class="btn delete " data-id="'. $leagues->id .'"><i class="fa fa-trash-alt text-danger"></i></a>';
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
                ->rawColumns(['icon','action'])
                ->make(true);
        }
    }



}
