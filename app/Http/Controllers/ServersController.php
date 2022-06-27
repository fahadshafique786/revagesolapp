<?php

namespace App\Http\Controllers;

use App\Models\Servers;
use App\Models\Sports;
use App\Models\ScheduledServers;
use App\Models\Schedules;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
//use Illuminate\Contracts\Validation\Validator;


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

    public function store(Request $request,$schedule_id = null)
    {
        if($schedule_id){
            $scheduleSports = Schedules::where('id',$schedule_id)->select('sports_id')->first();
            $sports_id  = $scheduleSports->sports_id;
            $request->merge(['sports_id' => $sports_id]);
        }

        if(!empty($request->id))
        {
            $this->validate($request, [
                'name' => 'required|unique:servers,name,'.$request->id,
                'sports_id' => 'required',
            ]);
        }
        else
        {
            $this->validate($request, [
                'name' => 'required|unique:servers,name,'.$request->id,
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

        if($schedule_id){
            $data['schedule_id'] = $schedule_id;
            $data['server_id'] = $servers->id;

            $scheduledServers   =   ScheduledServers::create($data);
        }

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
        ScheduledServers::where('server_id',$request->id)->delete();
        $servers = Servers::where('id',$request->id)->delete();
        return response()->json(['success' => true]);
    }

    public function fetchserversdata($schedule_id = null)
    {
        if(request()->ajax()) {

            $response = array();

            if($schedule_id){
                $Filterdata = Servers::select('servers.*','sports.name as sport_name')
                    ->join('scheduled_servers', function ($join) {
                        $join->on('scheduled_servers.server_id', '=', 'servers.id');
                    })
                    ->join('sports', function ($join) {
                        $join->on('servers.sports_id', '=', 'sports.id');
                    })
                    ->where('scheduled_servers.schedule_id',$schedule_id)
                    ->orderBy('servers.id','asc')->get();
            }
            else{

                $Filterdata = Servers::select('servers.*','sports.name as sport_name')
                    ->join('sports', function ($join) {
                        $join->on('servers.sports_id', '=', 'sports.id');
                    })->orderBy('servers.id','asc')->get();
            }



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
											<a href="javascript:void(0)" class="btn delete hide " data-id="'. $obj->id .'"><i class="fa fa-trash-alt text-danger"></i></a>';
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

    public function fetchScheduleServersView($schedule_id)
    {
        $scheduleData = Schedules::select('schedules.id as schedule_id','homeTeam.name as home_team_name','awayTeam.name as away_team_name')
            ->where('schedules.id',$schedule_id)
            ->join('teams as homeTeam', function ($join) {
                $join->on('schedules.home_team_id', '=', 'homeTeam.id');
            })
            ->join('teams as awayTeam', function ($join) {
                $join->on('schedules.away_team_id', '=', 'awayTeam.id');
            })->orderBy('schedules.id','asc')->first();


        $scheduleSports = Schedules::where('id',$schedule_id)->select('sports_id')->first();
        $sports_id  = $scheduleSports->sports_id;

        $servers_list = Servers::where('sports_id',$sports_id)->get();

        if(!empty($scheduleData)){

            return view('schedule_servers')
                ->with('scheduleData',$scheduleData)
                ->with('servers_list',$servers_list)
                ->with('schedule_id',$schedule_id);
        }
        else{
            abort(404);
        }

    }

    public function attachServers(Request $request,$schedule_id){

        if($schedule_id){
            $request->merge(['schedule_id' => $schedule_id]);

             $checkExistance = ScheduledServers::where('schedule_id',$request->schedule_id)
                    ->where('server_id',$request->server_id);

             if($checkExistance->exists()){
                 return response()->json([
                     'errors' =>
                     [
                         'message'=> 'This server is already linked with the same schedule',
                         'status_code' => 400
                     ]
                 ], 400);

                 exit();
             }


            $data['schedule_id'] = $schedule_id;
            $data['server_id'] = $request->server_id;
            $scheduledServers   =   ScheduledServers::create($data);
        }

        return response()->json(['success' => true]);


    }





}
