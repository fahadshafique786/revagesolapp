<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sports;
use App\Models\Leagues;
use App\Models\Teams;
use App\Models\Schedules;
use App\Models\ScheduledServers;
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
            $leaguesList = Leagues::where('sports_id',$sports_id)->get();
            $teamsList = Teams::where('sports_id',$sports_id)->get();

            return view('schedules')
                ->with('sports_id',$sports_id)
                ->with('sportData',$sportData)
                ->with('leaguesList',$leaguesList)
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


        $input = array();
        $input['label'] = $request->label;
        $input['leagues_id'] = $request->leagues_id;
        $input['home_team_id'] = $request->home_team_id;
        $input['away_team_id'] = $request->away_team_id;
        $input['start_time'] = $date_string = $request->start_time;
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
        ScheduledServers::where('schedule_id',$request->id)->delete();
        Schedules::where('id',$request->id)->delete();
        return response()->json(['success' => true]);
    }

    public function fetchschedulesdata(Request $request , $sports_id)
    {
        if(request()->ajax()) {

            $response = array();
            $Filterdata = Schedules::select('schedules.*','leagues.name as league_name','homeTeam.name as home_team_name','homeTeam.points as home_points','awayTeam.name as away_team_name','awayTeam.points as away_points')
                ->where('schedules.sports_id',$sports_id);


            if(isset($request->filter_league) && !empty($request->filter_league)){
                $Filterdata = $Filterdata->where('schedules.leagues_id',$request->filter_league);
            }

            $Filterdata = $Filterdata->join('teams as homeTeam', function ($join) {
                $join->on('schedules.home_team_id', '=', 'homeTeam.id');
                })
                ->join('leagues', function ($join) {
                    $join->on('leagues.id', '=', 'schedules.leagues_id');
                })
                ->join('teams as awayTeam', function ($join) {
                    $join->on('schedules.away_team_id', '=', 'awayTeam.id');
                })->orderBy('schedules.start_time','ASC')->get();


            if(!empty($Filterdata))
            {
                $i = 0;
                foreach($Filterdata as $index => $obj)
                {
                    $iteration = $i+1;

                    $linkedServer  = ScheduledServers::where('schedule_id',$obj->id);
                    if($linkedServer->exists()){
                        $serverLink =  '<a target="_blank" href="'.url("admin/servers/".$obj->id).'" class=""> <i class="fa fa-server text-md text-success"></i> <span class="text-dark text-bold">'.$iteration.'</span>  </a>';
                    }
                    else{
                        $serverLink = '<a target="_blank" href="'.url("admin/servers/".$obj->id).'" class=""> <i class="fa fa-server text-md text-danger"></i> <span class="text-dark text-bold">'.$iteration.'</span>  </a>';
                    }

                    $response[$i]['srno'] = $serverLink;
                    $response[$i]['label'] = $obj->label;
                    $response[$i]['league'] = $obj->league_name;
                    $response[$i]['home_team_id'] = $obj->home_team_name;
                    $response[$i]['away_team_id'] = $obj->away_team_name;
                    $response[$i]['score'] = $obj->home_points . " - " . $obj->away_points;
                    $response[$i]['start_time'] = $obj->start_time;
                    $response[$i]['is_live'] = getBooleanStr($obj->is_live,true);
                    if(auth()->user()->hasRole('super-admin') || auth()->user()->can('manage_schedules'))
                    {
                        $liveSwitch = ($obj->is_live) ? 'checked' : '';
                        $response[$i]['action'] = '
                            <input type="checkbox" class="isLiveStatusSwitch" data-id="is_live_status-'.$obj->id.'" data-schedule-id="'.$obj->id.'" '.$liveSwitch.' data-bootstrap-switch data-off-color="danger" data-on-color="info">
                            <a href="javascript:void(0)" class="btn edit" data-id="'. $obj->id .'"><i class="fa fa-edit  text-info"></i></a>
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

    public function updateScheduleLiveStatus(Request $request){

        $input['is_live'] = $request->is_live;
        Schedules::updateOrCreate(
        [
            'id' => $request->schedule_id
        ],
        $input);

        return response()->json(['success' => true]);

    }



}
