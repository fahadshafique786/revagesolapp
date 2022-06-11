<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sports;
use App\Models\Leagues;
use App\Models\Teams;
use App\Models\Schedules;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->sports_id = null;
//        $this->middleware('auth');
//        $this->middleware('role_or_permission:super-admin|view-schedules', ['only' => ['index','fetchschedulesdata']]);
//        $this->middleware('role_or_permission:super-admin|manage-schedules',['only' => ['edit','store','destroy']]);
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
                'label' => 'required|unique:schedules,lable,'.$request->id,
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

        $input = array();
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
                })->orderBy('schedules.id','desc')->get();

            if(!empty($Filterdata))
            {
                $i = 0;
                foreach($Filterdata as $index => $schedule)
                {

                    $response[$i]['srno'] = $i + 1;
                    $response[$i]['home_team_id'] = $schedule->home_team_name;
                    $response[$i]['away_team_id'] = $schedule->away_team_name;
                    $response[$i]['score'] = $schedule->home_points . " - " . $schedule->away_points;
                    $response[$i]['start_time'] = $schedule->start_time;
                    $response[$i]['is_live'] = strtoupper($schedule->is_live);
                    if(auth()->user()->hasRole('super-admin') || auth()->user()->can('manage-schedules'))
                    {
                        $response[$i]['action'] = '<a href="javascript:void(0)" class="btn edit" data-id="'. $schedule->id .'"><i class="fa fa-edit  text-info"></i></a>
											<a href="javascript:void(0)" class="btn delete " data-id="'. $schedule->id .'"><i class="fa fa-trash-alt text-danger"></i></a>';
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
