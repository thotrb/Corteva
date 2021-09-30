let getters = {

    user: state => {

        return state.user
    },

    pos: state => {

        return state.pos
    },

    worksiteID: state => {

        return state.worksiteID
    },

    productionlineID : state =>{
        return state.productionlineID
    },

    sites : state =>{
      return state.sites;
    },


    machines : state => {
        return state.machines;
    },


    events1: state => {

        return state.events1
    },

    events2: state => {

        return state.events2
    },


    speedLoss: state => {

        return state.speedLoss
    },

    downtimeReasons: state => {

        return state.downtimeReasons
    },

    downtimeReasons_2: state => {

        return state.downtimeReasons_2
    },


    machineIssue: state => {

        return state.machineIssue
    },
    /**
    posts: state => {
        return state.posts
    },

    questions: state => {
        return state.questions
    },

    courses: state => {
        return state.courses
    },
     */
};

export default  getters
