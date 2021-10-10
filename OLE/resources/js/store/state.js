let state = {

    user: [],
    pos: [],
    sites: [],
    events1: [],
    events2: [],
    unplannedEvent_UnplannedDowntime :[],
    downtimeReasons: [],
    downtimeReasons_2 : [],
    machineIssue : [],
    unplannedEvent_ChangingFormat: [],
    unplannedEvent_ClientChanging: [],
    unplannedEvent_CIP: [],
    plannedEvent: [],
    speedLoss : [],
    assignation : [],
    PO : [],
    machines : [],
    allEvents : [],
    worksiteID: -1,
    productionlineID: -1,
    token: localStorage.getItem('access_token'),
    unplannedDowntimeEvents: []
};

export default state
