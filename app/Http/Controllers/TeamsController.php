<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leagues;
use App\Models\Sports;
use App\Models\Teams;

class TeamsController extends Controller
{
    public function __construct()
    {
        $this->sports_id = null;
        $this->middleware('auth');
        $this->middleware('role_or_permission:super-admin|view_teams', ['only' => ['index','fetchteamsdata']]);
        $this->middleware('role_or_permission:super-admin|manage_teams',['only' => ['edit','store','destroy']]);
    }


    public function index(Request $request, $sports_id)
    {
        $sportData = Sports::where('id',$sports_id)->first();
        if(!empty($sportData)){
            $leaguesList = Leagues::where('sports_id',$sports_id)->get();

            return view('teams')
                ->with('sports_id',$sports_id)
                ->with('sportData',$sportData)
                ->with('leagues_list',$leaguesList);
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
                'name' => 'required|unique:teams,name,'.$request->id,
                'leagues_id' => 'required',
            ]);
        }
        else
        {
            $this->validate($request, [
                'name' => 'required|unique:teams,name,'.$request->id,
                'leagues_id' => 'required',
            ]);
        }

        $input = array();
        $input['name'] = $request->name;
        $input['leagues_id'] = $request->leagues_id;
        $input['points'] = $request->points;
        $input['sports_id'] = $sports_id;


        if($request->hasFile('team_icon'))
        {
            $file_original_name  = '';
            $file_unique_name = '';

            $fileobj				= $request->file('team_icon');
            $file_original_name 	= $fileobj->getClientOriginalName('team_icon');
            $file_extension_name 	= $fileobj->getClientOriginalExtension('team_icon');
            $file_unique_name 		= strtolower($request->name).'-'.time().rand(1000,9999).'.'.$file_extension_name;
            $destinationPath		= public_path('/uploads/teams');
            $fileobj->move($destinationPath,$file_unique_name);

            $input['icon'] = $file_unique_name;
        }

        $teams   =   Teams::updateOrCreate(
            [
                'id' => $request->id
            ],
            $input);

        return response()->json(['success' => true]);
    }

    public function edit(Request $request)
    {
        $where = array('id' => $request->id);
        $teams  = Teams::where($where)->first();
        return response()->json($teams);
    }

    public function destroy(Request $request)
    {
        $teams = Teams::where('id',$request->id)->delete();
        return response()->json(['success' => true]);
    }

    public function fetchteamsdata(Request $request,$sports_id)
    {
        if(request()->ajax()) {

            $response = array();
            $Filterdata = Teams::select('teams.*','leagues.name as league_name')
                ->where('teams.sports_id',$sports_id);

            if(isset($request->filter_league) && !empty($request->filter_league)){
                $Filterdata = $Filterdata->where('teams.leagues_id',$request->filter_league);
            }

            $Filterdata = $Filterdata->join('leagues', function ($join) {
                $join->on('leagues.id', '=', 'teams.leagues_id');
            })->orderBy('teams.id','asc')->get();

            if(!empty($Filterdata))
            {
                $i = 0;
                foreach($Filterdata as $index => $team)
                {

                    $sport_logo =  (!empty($team->icon)) ? '<img class="dataTable-image" src="'.url("/uploads/teams/").'/'.$team->icon.'" />' : '<a href="javascript:void(0)" class="" ><i class="fa fa-image text-xl"></i></a>';

                    $response[$i]['srno'] = $i + 1;
                    $response[$i]['icon'] = $sport_logo;
                    $response[$i]['name'] = $team->name;
                    $response[$i]['league_name'] = $team->league_name;
                    $response[$i]['points'] = $team->points;
                    if(auth()->user()->hasRole('super-admin') || auth()->user()->can('manage_teams'))
                    {
                        $response[$i]['action'] = '<a href="javascript:void(0)" class="btn edit" data-id="'. $team->id .'"><i class="fa fa-edit  text-info"></i></a>
											<a href="javascript:void(0)" class="btn delete " data-id="'. $team->id .'"><i class="fa fa-trash-alt text-danger"></i></a>';
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


    public function getTeamsByLeagueId(Request $request)
    {
        $teamsList = Teams::where('leagues_id',$request->leagues_id)->get();
        //dd($teamsList);
        $options = '<option value="">Select Team </option>';
        if(!empty($teamsList)){
            foreach($teamsList as $obj){
                $options .= '<option value="'.$obj->id.'">   '  .   $obj->name    .   '    </option>';
            }
        }

        return $options;
    }


}
