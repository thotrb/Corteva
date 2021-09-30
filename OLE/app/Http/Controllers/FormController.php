<?php


namespace App\Http\Controllers;


use App\Models\ole_assignement_team_po;
use App\Models\ole_planned_events;
use App\Models\ole_pos;
use App\Models\ole_speedLoss;
use App\Models\ole_unplanned_event_changing_client;
use App\Models\ole_unplanned_event_changing_format;
use App\Models\ole_unplanned_event_CIP;
use App\Models\ole_unplannedEvent_unplannedDowntime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
    public function index()
    {
        return view('teamInfo');
    }

    public function getMachines($productionlineID)
    {
        $machines = DB::table('ole_machines')
            ->where('ole_machines.productionlineID', '=', $productionlineID)
            ->orderBy('ole_machines.ordre', 'ASC')
            ->get();

        $formats = DB::table('ole_formats')
            ->where('ole_formats.productionlineID', '=', $productionlineID)
            ->get();

        $products = DB::table('ole_products')
            ->where('ole_products.productionlineID', '=', $productionlineID)
            ->get();

        $formulations = DB::table('ole_formulations')
            ->where('ole_formulations.productionlineID', '=', $productionlineID)
            ->get();

        $tab = array(
            0 => $machines,
            1 => $formats,
            2 => $products,
            3 => $formulations,
        );




        return response()->json($tab);


    }


    public function indexAdmin()
    {
        return view('packagingLineID');
    }

    public function summary()
    {
        return view('summary');

    }

    public function log($username, $password)
    {

        $userInfo = DB::table('user')
            ->where('user.login', '=', $username)
            ->where('user.password', '=', $password)
            ->get();
        return response()->json($userInfo);


    }

    public function saveUnplannedEvent(Request $request)
    {

        $progression = ole_unplannedEvent_unplannedDowntime::create($request->all());
        return response()->json($progression);


    }

    public function getSites()
    {
        $sites = DB::table('worksite')
            ->get();

        $productionLines = DB::table('ole_productionline')
            ->join('worksite', 'worksite.id', '=', 'ole_productionline.worksiteID')
            ->select('ole_productionline.id', 'ole_productionline.productionline_name', 'ole_productionline.worksiteID', 'worksite.name')

            ->get();

        $tab = array(
            0 => $sites,
            1 => $productionLines,
        );


        return response()->json($tab);


    }


    public function get($username)
    {

        $userInfo = DB::table('user')
            ->join('worksite', 'user.assignement', '=', 'worksite.id')
            ->join('ole_productionline', 'worksite.id', '=', 'ole_productionline.worksiteID')
            ->where('user.login', '=', $username)
            ->get();


        $crewLeaders = DB::table('user')
            ->join('teamInfo', 'user.assignement', '=', 'teamInfo.id')
            ->join('worksite', 'teamInfo.worksite', '=', 'worksite.id')
            //->where('worksite.id', '=', $userInfo.worksite)
            ->where('user.status', '=', 1)
            ->select('user.lastname', 'user.firstname', 'worksite.id')
            ->get();


        $shifts = DB::table('teamInfo')
            ->join('worksite', 'worksite.id', '=', 'teamInfo.worksite')
            ->get();


        $productionLine = DB::table('ole_pos')
            ->join('ole_assignement_team_po', 'ole_assignement_team_po.po', '=', 'ole_pos.number')
            ->join('ole_productionline', 'ole_productionline.id', '=', 'ole_assignement_team_po.productionline')
            ->select('ole_productionline.productionline_name', 'ole_productionline.worksiteID' )
            ->get();


        $tab = array(
            0 => $userInfo,
            1 => $crewLeaders,
            2 => $shifts,
            3 => $productionLine,
        );


        return response()->json($tab);


    }

    public function choicePlannedUnplanned($productionName)
    {

        return view('incidentDeclaration');
    }

    public function getDowntimeReasons($productionName, $downTimeType)
    {

        $downTimeReasons = DB::table('ole_downtimeReason')
            ->where('ole_downtimeReason.downtimeType', '=', $downTimeType)
            ->get();


        return response()->json($downTimeReasons);

    }



    public function downtimeReason_1($downtimeType)
    {
        return view('unplannedDowntime_Screen1');

    }

    public function plannedDowntime_Event(){
        return view('plannedDowntime_declaration');

    }

    public function unplannedDowntime_2()
    {
        return view('unplannedDowntime_unplannedDowntime_Screen2');

    }

    public function get_unplannedDowntime_2()
    {
        $machines = DB::table('ole_machines')
            ->where('ole_machines.name', '!=', 'Remplisseuse')
            ->get();
        return response()->json($machines);

    }

    public function get_unplannedDowntime_Machine_Issue($machineName)
    {
        $issues = DB::table('machine_component')
            ->join('ole_machines', 'ole_machines.id', '=', 'machine_component.machineID')
            ->where('ole_machines.name', '=', $machineName)
            ->select('machine_component.name as component', 'ole_machines.name', 'other_machine')
            ->get();

        return response()->json($issues);
    }

    public function get_speedLosses($PO, $productionLine)
    {
        $speedLosses =  DB::table('ole_speed_losses')
            ->where('ole_speed_losses.productionline', '=', $productionLine)
            ->where('ole_speed_losses.OLE', '=', $PO)
            ->get();

        return response()->json($speedLosses);





    }

    public function getPOsFromShift($shift, $site){

        $pos =  DB::table('worksite')
            ->where('worksite.name', '=', $site)
            ->join('ole_assignement_team_po', 'ole_assignement_team_po.worksite', '=', 'worksite.id')
            ->join('ole_pos', 'ole_pos.number', '=', 'ole_assignement_team_po.po')
            ->where('ole_assignement_team_po.shift', '=', $shift)
            ->get();


        return response()->json($pos);

    }

    public function getWorksiteID($worksite){
        $worksiteID = $pos =  DB::table('worksite')
            ->where('worksite.name', '=', $worksite)
            ->select('worksite.id')
            ->get();

        return response()->json($worksiteID);
    }

    public function getProductionlineID($productionline){

        $productionlineID =  DB::table('ole_productionline')
            ->where('ole_productionline.productionline_name', '=', $productionline)
            ->select('ole_productionline.id')
            ->get();

        return response()->json($productionlineID);

    }

    public function createAssignement(Request $request){

        $PO = ole_assignement_team_po::create($request->all());
        printf(response()->json($PO));
        return response()->json($PO);



    }



    public function getEvents($PO, $productionLine){

        $plannedEvents =  DB::table('ole_planned_events')
            ->where('ole_planned_events.productionline', '=', $productionLine)
            ->where('ole_planned_events.OLE', '=', $PO)
            ->select('duration as total_duration', 'reason as type', 'comment', 'updated_at', 'OLE', 'productionline', 'kind');

        $changingClients =  DB::table('ole_unplanned_event_changing_clients')
            ->where('ole_unplanned_event_changing_clients.productionline', '=', $productionLine)
            ->where('ole_unplanned_event_changing_clients.OLE', '=', $PO)
            ->select('total_duration', 'type', 'comment', 'updated_at', 'OLE', 'productionline', 'kind');


        $CIP =  DB::table('ole_unplanned_event_cips')
            ->where('ole_unplanned_event_cips.productionline', '=', $productionLine)
            ->where('ole_unplanned_event_cips.OLE', '=', $PO)
            ->select('total_duration', 'type', 'comment', 'updated_at', 'OLE', 'productionline', 'kind');


        $unplanned =  DB::table('ole_unplanned_event_unplanned_downtimes')
            ->where('ole_unplanned_event_unplanned_downtimes.productionline', '=', $productionLine)
            ->where('ole_unplanned_event_unplanned_downtimes.OLE', '=', $PO)
            ->select('total_duration', 'type', 'comment', 'updated_at', 'OLE', 'productionline', 'kind');




        $changingFormats =  DB::table('ole_unplanned_event_changing_formats')
            ->where('ole_unplanned_event_changing_formats.productionline', '=', $productionLine)
            ->where('ole_unplanned_event_changing_formats.OLE', '=', $PO)
            ->select('total_duration', 'type', 'comment', 'updated_at', 'OLE', 'productionline', 'kind')
            ->union($changingClients)
            ->union($CIP)
            ->union($unplanned)
            ->union($plannedEvents)
            ->orderBy('updated_at', 'ASC')
            ->get();



        return response()->json($changingFormats);

    }

    public function unplannedDowntime_CIP()
    {
        return view('unplannedDowntime_CIP');

    }

    public function unplannedDowntime_changingFormat()
    {
        return view('unplannedDowntime_changingFormat');
    }

    public function unplannedDowntime_clientChanging()
    {
        return view('unplannedDowntime_clientChanging');
    }

    public function endPO(){
        return view('endPO_Declaration');

    }

    public function createPO(Request $request){

        $PO = ole_pos::create($request->all());
        printf(response()->json($PO));
        return response()->json($PO);

    }


    public function saveUnplannedEvent_Changingformat(Request $request){

        $event = ole_unplanned_event_changing_format::create($request->all());
        printf(response()->json($event));
        return response()->json($event);

    }


    public function saveUnplannedEvent_Clientchanging(Request $request){

        $event = ole_unplanned_event_changing_client::create($request->all());
        printf(response()->json($event));
        return response()->json($event);

    }

    public function saveUnplannedEvent_CIP(Request $request)
    {

        $event = ole_unplanned_event_cip::create($request->all());
        printf(response()->json($event));
        return response()->json($event);
    }

    public function savePlannedEvent(Request $request)
    {

        $event = ole_planned_events::create($request->all());
        printf(response()->json($event));
        return response()->json($event);
    }

    public function saveSpeedLoss(Request $request)
    {
        $event = ole_speedLoss::create($request->all());
        printf(response()->json($event));
        return response()->json($event);
    }

    public function stopPO($PONumber)
    {
        $update = DB::update(
            'update ole_pos set state = 0 where number = ?',
            [$PONumber]
        );
        return response()->json($update);


    }

}
