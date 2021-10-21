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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{

    public function unplannedDowntimeDashboard() {
        return view('unplannedDowntimeDashboard');
    }

    public function unplannedDowntimeShutdowns() {
        return view('unplannedDowntimeShutdowns');
    }

    public function index()
    {
        return view('teamInfo');
    }

    public function downtimesReport()
    {
        return view('downtimesReport');
    }


    public function getUnplannedDowntimeEvents($productionLine, $beginningYear, $endingYear) {

        $CIP = DB::table('ole_unplanned_event_cips')
            ->where('ole_unplanned_event_cips.productionline', '=', $productionLine)
            ->whereYear('ole_unplanned_event_cips.created_at', '>=', $beginningYear)
            ->whereYear('ole_unplanned_event_cips.created_at', '<=', $endingYear)
            ->get();

        $COV = DB::table('ole_unplanned_event_changing_clients')
            ->where('ole_unplanned_event_changing_clients.productionline', '=', $productionLine)
            ->whereYear('ole_unplanned_event_changing_clients.created_at', '>=', $beginningYear)
            ->whereYear('ole_unplanned_event_changing_clients.created_at', '<=', $endingYear)
            ->get();

        $BNC = DB::table('ole_unplanned_event_changing_formats')
            ->where('ole_unplanned_event_changing_formats.productionline', '=', $productionLine)
            ->whereYear('ole_unplanned_event_changing_formats.created_at', '>=', $beginningYear)
            ->whereYear('ole_unplanned_event_changing_formats.created_at', '<=', $endingYear)
            ->get();
        
        $machineShutdowns = DB::table('ole_unplanned_event_unplanned_downtimes')
            ->where('ole_unplanned_event_unplanned_downtimes.productionline', '=', $productionLine)
            ->whereYear('ole_unplanned_event_unplanned_downtimes.created_at', '>=', $beginningYear)
            ->whereYear('ole_unplanned_event_unplanned_downtimes.created_at', '<=', $endingYear)
            ->get();



        $tab = array([
            'CIP' => $CIP,
            'COV' => $COV,
            'BNC' => $BNC,
            'machines' => $machineShutdowns
        ]);

        return response()->json($tab);
    }

    public function getAllEventsPeriod($site, $productionLine, $beginningDate, $endingDate, $PONumber)
    {
        $site = DB::table('ole_productionline')
            ->join('worksite', 'worksite.id', '=', 'ole_productionline.worksiteID')
            ->join('ole_pos', 'ole_pos.productionline_name', '=', 'ole_productionline.productionline_name')
            ->join('ole_products', 'ole_pos.GMID-Code', '=', 'ole_products.GMID')
            ->join('ole_rejection_counters', 'ole_rejection_counters.po', '=', 'ole_pos.number')
            ->where('worksite.name', '=', $site)
            ->where('ole_productionline.productionline_name', '=', $productionLine)
            ->get();



        if(count($site) > 0){

            $date1 = explode("-", $beginningDate);
            $beginningYear = $date1[0];
            $beginningMonth = $date1[1];
            $beginningDay = $date1[2];

            $date2 = explode("-", $endingDate);
            $endingYear = $date2[0];
            $endingMonth = $date2[1];
            $endingDay = $date2[2];

            $startDate = Carbon::createFromFormat('Y-m-d', $beginningYear.'-'.$beginningMonth.'-'.$beginningDay)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $endingYear.'-'.$endingMonth.'-'.$endingDay)->startOfDay();





            $BM = DB::table('ole_planned_events')
                ->where(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_planned_events.productionline', '=', $site[0]->productionline_name)
                        ->where('ole_planned_events.reason', '=', 'Pause')
                        -> whereBetween(DB::raw('DATE(ole_planned_events.created_at)'), [$startDate, $endDate]);

                })
                ->orWhere(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_planned_events.productionline', '=', $site[0]->productionline_name)
                        ->where('ole_planned_events.reason', '=', 'Reunion')
                        -> whereBetween(DB::raw('DATE(ole_planned_events.created_at)'), [$startDate, $endDate]);

                })
                ->orWhere(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_planned_events.productionline', '=', $site[0]->productionline_name)
                        ->where('ole_planned_events.reason', '=', 'Pas de production prevue')
                        -> whereBetween(DB::raw('DATE(ole_planned_events.created_at)'), [$startDate, $endDate]);

                })
                ->select(DB::raw('SUM(duration) as Duration'), DB::raw('count(*) as nbEvents'))
                ->get();


            $CP = DB::table('ole_planned_events')
                ->where(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_planned_events.productionline', '=', $site[0]->productionline_name)
                        ->where('ole_planned_events.reason', '=', 'Implementation de projet')
                        -> whereBetween(DB::raw('DATE(ole_planned_events.created_at)'), [$startDate, $endDate]);

                })
                ->select(DB::raw('SUM(duration) as Duration'), DB::raw('count(*) as nbEvents'))
                ->get();



            $PM = DB::table('ole_planned_events')
                ->where(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_planned_events.productionline', '=', $site[0]->productionline_name)
                        ->where('ole_planned_events.reason', '=', 'Maintenance')
                        -> whereBetween(DB::raw('DATE(ole_planned_events.created_at)'), [$startDate, $endDate]);

                })
                ->select(DB::raw('SUM(duration) as Duration'), DB::raw('count(*) as nbEvents'))
                ->get();


            $PP = DB::table('ole_planned_events')
                ->where(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_planned_events.productionline', '=', $site[0]->productionline_name)
                        ->where('ole_planned_events.reason', '=', 'Pas de production prevue')
                        -> whereBetween(DB::raw('DATE(ole_planned_events.created_at)'), [$startDate, $endDate]);

                })
                ->select(DB::raw('SUM(duration) as Duration'), DB::raw('count(*) as nbEvents'))
                ->get();


            $CIP = DB::table('ole_unplanned_event_cips')
                ->where(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_unplanned_event_cips.productionline', '=', $site[0]->productionline_name)
                        -> whereBetween(DB::raw('DATE(ole_unplanned_event_cips.created_at)'), [$startDate, $endDate]);

                })
                ->select(DB::raw('SUM(total_duration) as Duration'), DB::raw('count(*) as nbEvents'))
                ->get();


            $COV = DB::table('ole_unplanned_event_changing_clients')
                ->where(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_unplanned_event_changing_clients.productionline', '=', $site[0]->productionline_name)
                        -> whereBetween(DB::raw('DATE(ole_unplanned_event_changing_clients.created_at)'), [$startDate, $endDate]);

                })
                ->select(DB::raw('SUM(total_duration) as Duration'), DB::raw('count(*) as nbEvents'))

                ->get();



            $BNC = DB::table('ole_unplanned_event_changing_formats')
                ->where(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_unplanned_event_changing_formats.productionline', '=', $site[0]->productionline_name)
                        -> whereBetween(DB::raw('DATE(ole_unplanned_event_changing_formats.created_at)'), [$startDate, $endDate]);

                })
                ->select(DB::raw('SUM(total_duration) as Duration'), DB::raw('count(*) as nbEvents'))

                ->get();



            $UEE = DB::table('ole_unplanned_event_unplanned_downtimes')
                ->where(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_unplanned_event_unplanned_downtimes.productionline', '=', $site[0]->productionline_name)
                        -> whereBetween(DB::raw('DATE(ole_unplanned_event_unplanned_downtimes.created_at)'), [$startDate, $endDate])
                        ->where('ole_unplanned_event_unplanned_downtimes.component', '=', 'Autres');
                })
                ->select(DB::raw('SUM(total_duration) as Duration'), DB::raw('count(*) as nbEvents'))

                ->get();



            $USM = DB::table('ole_unplanned_event_unplanned_downtimes')
                ->where(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_unplanned_event_unplanned_downtimes.productionline', '=', $site[0]->productionline_name)
                        ->where('ole_unplanned_event_unplanned_downtimes.component', '!=', 'Saturation aval')
                        -> whereBetween(DB::raw('DATE(ole_unplanned_event_unplanned_downtimes.created_at)'), [$startDate, $endDate]);

                })
                ->orWhere(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_unplanned_event_unplanned_downtimes.productionline', '=', $site[0]->productionline_name)
                        ->where('ole_unplanned_event_unplanned_downtimes.component', '!=', 'Manque bouteille')
                        -> whereBetween(DB::raw('DATE(ole_unplanned_event_unplanned_downtimes.created_at)'), [$startDate, $endDate]);

                })
                ->orWhere(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_unplanned_event_unplanned_downtimes.productionline', '=', $site[0]->productionline_name)
                        ->where('ole_unplanned_event_unplanned_downtimes.component', '!=', 'Autres')
                        -> whereBetween(DB::raw('DATE(ole_unplanned_event_unplanned_downtimes.created_at)'), [$startDate, $endDate]);

                })
                ->select(DB::raw('SUM(total_duration) as Duration'), DB::raw('count(*) as nbEvents'))

                ->get();




            $FUS = DB::table('ole_unplanned_event_unplanned_downtimes')
                ->where(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_unplanned_event_unplanned_downtimes.productionline', '=', $site[0]->productionline_name)
                        ->where('ole_unplanned_event_unplanned_downtimes.component', '=', 'Saturation aval')
                        -> whereBetween(DB::raw('DATE(ole_unplanned_event_unplanned_downtimes.created_at)'), [$startDate, $endDate]);

                })
                ->orWhere(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_unplanned_event_unplanned_downtimes.productionline', '=', $site[0]->productionline_name)
                        ->where('ole_unplanned_event_unplanned_downtimes.component', '=', 'Manque bouteille')
                        -> whereBetween(DB::raw('DATE(ole_unplanned_event_unplanned_downtimes.created_at)'), [$startDate, $endDate]);

                })
                ->select(DB::raw('SUM(total_duration) as Duration'), DB::raw('count(*) as nbEvents'))

                ->get();




            $RRF = DB::table('ole_speed_losses')
                ->where(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_speed_losses.productionline', '=', $site[0]->productionline_name)
                        ->where('ole_speed_losses.reason', '=', 'Reduced Rate At Filler')
                        -> whereBetween(DB::raw('DATE(ole_speed_losses.created_at)'), [$startDate, $endDate]);

                })
                ->select(DB::raw('SUM(duration) as Duration'), DB::raw('count(*) as nbEvents'))

                ->get();



            $RRM = DB::table('ole_speed_losses')
                ->where(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_speed_losses.productionline', '=', $site[0]->productionline_name)
                        ->where('ole_speed_losses.reason', '=', 'Reduce Rate At An Other Machine')
                        -> whereBetween(DB::raw('DATE(ole_speed_losses.created_at)'), [$startDate, $endDate]);

                })
                ->select(DB::raw('SUM(duration) as Duration'), DB::raw('count(*) as nbEvents'))

                ->get();



            $FOS = DB::table('ole_speed_losses')
                ->where(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_speed_losses.productionline', '=', $site[0]->productionline_name)
                        ->where('ole_speed_losses.reason', '=', 'Filler Own Stoppage')
                        -> whereBetween(DB::raw('DATE(ole_speed_losses.created_at)'), [$startDate, $endDate]);

                })
                ->select(DB::raw('SUM(duration) as Duration'), DB::raw('count(*) as nbEvents'))

                ->get();



            $FSM = DB::table('ole_speed_losses')
                ->where(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_speed_losses.productionline', '=', $site[0]->productionline_name)
                        ->where('ole_speed_losses.reason', '=', 'Filler Own Stoppage By An Other Machine')
                        -> whereBetween(DB::raw('DATE(ole_speed_losses.created_at)'), [$startDate, $endDate]);

                })
                ->select(DB::raw('SUM(duration) as Duration'), DB::raw('count(*) as nbEvents'))

                ->get();


            $plannedEvents = DB::table('ole_planned_events')
                ->where(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_planned_events.productionline', '=', $site[0]->productionline_name)
                        -> whereBetween(DB::raw('DATE(ole_planned_events.created_at)'), [$startDate, $endDate]);
                })
                ->get();

            $changingClients = DB::table('ole_unplanned_event_changing_clients')
                ->where(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_unplanned_event_changing_clients.productionline', '=', $site[0]->productionline_name)
                        -> whereBetween(DB::raw('DATE(ole_unplanned_event_changing_clients.created_at)'), [$startDate, $endDate]);
                })
               ->get();

            $CIPBis = DB::table('ole_unplanned_event_cips')
                ->where(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_unplanned_event_cips.productionline', '=', $site[0]->productionline_name)
                        -> whereBetween(DB::raw('DATE(ole_unplanned_event_cips.created_at)'), [$startDate, $endDate]);
                })
                ->get();



            $unplanned = DB::table('ole_unplanned_event_unplanned_downtimes')
                ->where(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_unplanned_event_unplanned_downtimes.productionline', '=', $site[0]->productionline_name)
                        -> whereBetween(DB::raw('DATE(ole_unplanned_event_unplanned_downtimes.created_at)'), [$startDate, $endDate]);
                })
                ->get();

            $changingFormats = DB::table('ole_unplanned_event_changing_formats')
                ->where(function($query) use ($endDate, $startDate, $site) {
                    $query->where('ole_unplanned_event_changing_formats.productionline', '=', $site[0]->productionline_name)
                        -> whereBetween(DB::raw('DATE(ole_unplanned_event_changing_formats.created_at)'), [$startDate, $endDate]);
                })
                ->get()
                ->union($changingClients)
                ->union($CIPBis)
                ->union($unplanned)
                ->union($plannedEvents);














            $tab = array(

                'BM' => $BM,
                'CP' => $CP,
                'PM' => $PM,
                'PP' => $PP,
                'CIP' => $CIP,
                'COV' => $COV,
                'BNC' => $BNC,
                'UEE' => $UEE,
                'USM' => $USM,
                'FUS' => $FUS,
                'RRF' => $RRF,
                'RRM' => $RRM,
                'FOS' => $FOS,
                'FSM' => $FSM,
                'SITE' => $site,
                'EVENTS' => $changingFormats

            );



        }else{
            $tab = array(

                'BM' => null,
                'CP' => null,
                'PM' => null,
                'PP' => null,
                'CIP' => null,
                'COV' => null,
                'BNC' => null,
                'UEE' => null,
                'USM' => null,
                'FUS' => null,
                'RRF' => null,
                'RRM' => null,
                'FOS' => null,
                'FSM' => null,
                'SITE' => null,
                'EVENTS' => null,


            );


        }
        return response()->json($tab);






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

        //$products = DB::table('ole_products')
          //  ->where('ole_products.productionlineID', '=', $productionlineID)
            //->get();

        $formulations = DB::table('ole_formulations')
            ->where('ole_formulations.productionlineID', '=', $productionlineID)
            ->get();

        $tab = array(

            0 => $machines,
            1 => $formats,
            //2 => $products,
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

    public function isAssignationPossible($username, $po, $productionline)
    {


        $assignation = DB::table('ole_assignement_team_pos')
            ->where('ole_assignement_team_pos.username', '=', $username)
            ->where('ole_assignement_team_pos.po', '=', $po)
            ->where('ole_assignement_team_pos.productionline', '=', $productionline)
            ->count();



        return response()->json($assignation);

    }

    public function monthlyLoadFactor(){
        return view('monthlyLoadFactor');
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
            ->join('ole_assignement_team_pos', 'ole_assignement_team_pos.po', '=', 'ole_pos.number')
            ->join('ole_productionline', 'ole_productionline.id', '=', 'ole_assignement_team_pos.productionline')
            ->select('ole_productionline.productionline_name', 'ole_productionline.worksiteID')
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

    public function plannedDowntime_Event()
    {
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
        $speedLosses = DB::table('ole_speed_losses')
            ->where('ole_speed_losses.productionline', '=', $productionLine)
            ->where('ole_speed_losses.OLE', '=', $PO)
            ->get();

        return response()->json($speedLosses);


    }

    public function getPOsFromShift($shift, $site)
    {

        $pos = DB::table('worksite')
            ->where('worksite.name', '=', $site)
            ->join('ole_assignement_team_po', 'ole_assignement_team_po.worksite', '=', 'worksite.id')
            ->join('ole_pos', 'ole_pos.number', '=', 'ole_assignement_team_po.po')
            ->where('ole_assignement_team_po.shift', '=', $shift)
            ->get();


        return response()->json($pos);

    }

    public function getWorksiteID($worksite)
    {
        $worksiteID = $pos = DB::table('worksite')
            ->where('worksite.name', '=', $worksite)
            ->select('worksite.id')
            ->get();

        return response()->json($worksiteID);
    }

    public function getProductionlineID($productionline)
    {

        $productionlineID = DB::table('ole_productionline')
            ->where('ole_productionline.productionline_name', '=', $productionline)
            ->select('ole_productionline.id')
            ->get();

        return response()->json($productionlineID);

    }

    public function createAssignement(Request $request)
    {

        $PO = ole_assignement_team_po::create($request->all());
        printf(response()->json($PO));
        return response()->json($PO);


    }

    public function getNetOP($GMID)
    {
        $netOp = DB::table('ole_products')
            ->where('ole_products.GMID', '=', $GMID)
            ->first();
        return response()->json($netOp);

    }
    //getEvents for the unplanned dashboard page
    public function getEventsUD($dateFrom, $dateTo) {

       

    }

    public function getEvents($PO, $productionLine)
    {

        $plannedEvents = DB::table('ole_planned_events')
            ->where('ole_planned_events.productionline', '=', $productionLine)
            ->where('ole_planned_events.OLE', '=', $PO)
            ->select('duration as total_duration', 'reason as type', 'comment', 'updated_at', 'OLE', 'productionline', 'kind');

        $changingClients = DB::table('ole_unplanned_event_changing_clients')
            ->where('ole_unplanned_event_changing_clients.productionline', '=', $productionLine)
            ->where('ole_unplanned_event_changing_clients.OLE', '=', $PO)
            ->select('total_duration', 'type', 'comment', 'updated_at', 'OLE', 'productionline', 'kind');


        $CIP = DB::table('ole_unplanned_event_cips')
            ->where('ole_unplanned_event_cips.productionline', '=', $productionLine)
            ->where('ole_unplanned_event_cips.OLE', '=', $PO)
            ->select('total_duration', 'type', 'comment', 'updated_at', 'OLE', 'productionline', 'kind');


        $unplanned = DB::table('ole_unplanned_event_unplanned_downtimes')
            ->where('ole_unplanned_event_unplanned_downtimes.productionline', '=', $productionLine)
            ->where('ole_unplanned_event_unplanned_downtimes.OLE', '=', $PO)
            ->select('total_duration', 'type', 'comment', 'updated_at', 'OLE', 'productionline', 'kind');


        $changingFormats = DB::table('ole_unplanned_event_changing_formats')
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

    public function endPO()
    {
        return view('endPO_Declaration');

    }

    public function createPO(Request $request)
    {

        $PO = ole_pos::create($request->all());
        printf(response()->json($PO));
        return response()->json($PO);

    }


    public function saveUnplannedEvent_Changingformat(Request $request)
    {

        $event = ole_unplanned_event_changing_format::create($request->all());
        printf(response()->json($event));
        return response()->json($event);

    }


    public function saveUnplannedEvent_Clientchanging(Request $request)
    {

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

    public function stopPO($PO, $availability, $performance, $quality, $OLE, $quantityProduced, $totalDuration)
    {
        $update = DB::update(
            'update ole_pos set state = 0, performance = ?, availability = ?, quality = ?, OLE = ?, qtyProduced = ?, workingDuration = ? where number = ?',
            [$performance, $availability, $quality, $OLE, $quantityProduced, $totalDuration, $PO]
        );
        return response()->json($update);


    }

}
