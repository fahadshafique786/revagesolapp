<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sports;
use App\Models\Leagues;
use App\Models\Teams;
use App\Models\Schedules;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->sports_id = null;
//        $this->middleware('auth');
        $this->middleware('role_or_permission:super-admin|view_schedules', ['only' => ['index','fetchschedulesdata']]);
        $this->middleware('role_or_permission:super-admin|manage_schedules',['only' => ['edit','store','destroy']]);
    }

    public function index(Request $request, $sports_id)
    {
        $sportData = Sports::where('id',$sports_id)->first();
        if(!empty($sportData)){
            $teamsList = Teams::where('sports_id',$sports_id)->get();

            return view('schedules')
                ->with('sports_id',$sports_id)
                ->with('sportData',$sportData)
                ->with('teamsList',$teamsList);
        }
        else{
            abort(404);
        }

    }

    public function store(Request $request , $sports_id)
    {
        if(!empty($request->id))
        {
            $this->validate($request, [
                'label' => 'required|unique:schedules,label,'.$request->id,
                'home_team_id' => 'required',
                'away_team_id' => 'required',
                'start_time' => 'required',
            ]);
        }
        else
        {
            $this->validate($request, [
                'label' => 'required|unique:schedules,label,'.$request->id,
                'home_team_id' => 'required',
                'away_team_id' => 'required',
                'start_time' => 'required',
            ]);
        }


//        $dateArray = explode(' ',$request->start_time);
//        $aDateString = $dateArray[0];
//        $aTimeString = $dateArray[1];
//
//        $dateTimeString = $aDateString." ".$aTimeString;
//
//       $ddd = date('Y-m-d', strtotime('28/06/2021'));
//        dd($aDateString,$ddd,$dateArray,$dateTimeString,$request->start_time);
//
//        $dueDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $dateTimeString, 'Europe/London');
//
//        dd($dueDateTime,$request->start_time);


        $input = array();
        $input['label'] = $request->label;
        $input['home_team_id'] = $request->home_team_id;
        $input['away_team_id'] = $request->away_team_id;
        $input['start_time'] = date('Y-m-d H:i:s', strtotime($request->start_time));
        $input['sports_id'] = $sports_id;

        $schduledata   =   Schedules::updateOrCreate(
            [
                'id' => $request->id
            ],
            $input);

        return response()->json(['success' => true]);
    }

    public function edit(Request $request)
    {
        $where = array('id' => $request->id);
        $schduledata  = Schedules::where($where)->first();
        return response()->json($schduledata);
    }

    public function destroy(Request $request)
    {
        $schduledata = Schedules::where('id',$request->id)->delete();
        return response()->json(['success' => true]);
    }

    public function fetchschedulesdata($sports_id)
    {
        if(request()->ajax()) {

            $response = array();
            $Filterdata = Schedules::select('schedules.*','homeTeam.name as home_team_name','homeTeam.points as home_points','awayTeam.name as away_team_name','awayTeam.points as away_points')
                ->where('schedules.sports_id',$sports_id)
                ->join('teams as homeTeam', function ($join) {
                    $join->on('schedules.home_team_id', '=', 'homeTeam.id');
                })
                ->join('teams as awayTeam', function ($join) {
                    $join->on('schedules.away_team_id', '=', 'awayTeam.id');
                })->orderBy('schedules.id','asc')->get();

            if(!empty($Filterdata))
            {
                $i = 0;
                foreach($Filterdata as $index => $obj)
                {

                    $response[$i]['srno'] = '<a target="_blank" href="'.url("admin/servers/".$obj->id).'" class=""> <i class="fa fa-server"></i> </a>';
                    $response[$i]['label'] = $obj->label;
                    $response[$i]['home_team_id'] = $obj->home_team_name;
                    $response[$i]['away_team_id'] = $obj->away_team_name;
                    $response[$i]['score'] = $obj->home_points . " - " . $obj->away_points;
                    $response[$i]['start_time'] = $obj->start_time;
                    $response[$i]['is_live'] = strtoupper($obj->is_live);
                    if(auth()->user()->hasRole('super-admin') || auth()->user()->can('manage_schedules'))
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
                ->rawColumns(['srno','action'])
                ->make(true);
        }
    }


}
