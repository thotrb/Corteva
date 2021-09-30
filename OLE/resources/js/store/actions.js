let actions = {


    retrieveToken(context, credentials) {
        axios.post(`/api/log/${credentials[0]}/${credentials[1]}`)
            .then(res => {
                console.log(res);
                var token;
                if (res.data.length > 0) {
                    token = 'OK';
                    localStorage.setItem('access_token', token);
                    context.commit('RETREIVETOKEN', token);
                }

            }).catch(err => {
            console.log(err)
        })

    },



    fetchUsers({commit}, parameters) {

        axios.get(`/api/users/${parameters[0]}`)
            .then(res => {
                console.log(res.data);
                commit('FETCH_USER', res.data);
            }).catch(err => {
            console.log(err)
        })
    },

    fetchSites({commit}, parameters) {

        axios.get(`/api/sites`)
            .then(res => {
                console.log(res.data);
                commit('FETCH_SITES', res.data);
            }).catch(err => {
            console.log(err)
        })
    },

    fetchMachines({commit}, productionlineID) {

        axios.get(`/api/machines/${productionlineID}`)
            .then(res => {
                commit('FETCH_MACHINES', res.data);
            }).catch(err => {
            console.log(err)
        })
    },

    fetchEvents({commit}, parameters) {

        axios.get(`/api/events/${parameters[1]}/${parameters[2]}`)
            .then(res => {
                //console.log(res.data);
                if (parameters[0] === 1) {
                    commit('FETCH_EVENTS1', res.data);
                } else {
                    commit('FETCH_EVENTS2', res.data);
                }
            }).catch(err => {
            console.log(err)
        })
    },

    fetchPO({commit}, parameters) {
        axios.get(`/api/pos/${parameters[0]}/${parameters[1]}`)
            .then(res => {
                //console.log(res.data);
                commit('FETCH_PO', res.data);
            }).catch(err => {
            console.log(err)
        })
    },


    fetchSpeedLosses({commit}, parameters) {

        axios.get(`/api/speedLosses/${parameters[1]}/${parameters[0]}`)
            .then(res => {
                commit('FETCH_SPEEDLOSSES', res.data);
            }).catch(err => {
            console.log(err)
        })
    },


    fetchDowntimeReason({commit}, parameters) {
        axios.get(`/api/summary/${parameters[0]}/${parameters[1]}`)
            .then(res => {
                console.log(res.data);
                commit('FETCH_DOWNTIME_REASONS', res.data)
            }).catch(err => {
            console.log(err)
        })
    },

    fetchDowntimeReason_2({commit}, parameters) {
        axios.get(`/api/${parameters[0]}/${parameters[1]}/unplannedDowntime`)
            .then(res => {
                console.log('Je passe ici');
                console.log(res.data);
                commit('FETCH_DOWNTIME_REASONS_2', res.data)
            }).catch(err => {
            console.log(err)
        })
    },

    fetchDowntimeReason_Machine_Issue({commit}, machineName) {
        axios.get(`/api/unplannedDowntime/unplannedDowntime/${machineName}`)
            .then(res => {
                console.log(res.data);
                commit('FETCH_DOWNTIME_REASONS_MACHINE_ISSUE', res.data)
            }).catch(err => {
            console.log(err)
        })
    },

    getWorksiteID({commit}, worksite) {
        axios.get(`/api/worksiteID/${worksite}`)
            .then(res => {
                commit('FETCH_WORKSITEID', res.data)
            }).catch(err => {
            console.log(err)
        })
    },

    getProductionlineID({commit}, productionline) {
        axios.get(`/api/productionlineID/${productionline}`)
            .then(res => {
                commit('FETCH_PRODUCTIONLINEID', res.data)
            }).catch(err => {
            console.log(err)
        })
    },

    checkAssignation({commit}, assignation) {

        axios.get(`/api/assignation/${assignation.username}/${assignation.po}/${assignation.productionline}`)
            .then(res => {
                commit('CREATE_ASSIGNATION', res.data)
            }).catch(err => {
            console.log(err)
        })
    },

    create_PO({commit}, potab) {

        for (let i = 0; i < potab.length; i++) {
            axios.post(`/api/PO`, potab[i])
                .then(res => {
                    commit('CREATE_PO', res.data)
                }).catch(err => {
                console.log(err)
            })

        }

    },

    storeAssignation({commit}, assignation) {

        axios.post(`/api/assignation`, assignation)
            .then(res => {
                commit('CREATE_ASSIGNATION', res.data)
            }).catch(err => {
            console.log(err)
        })
    },





    create_UnplannedEvent_Changingformat({commit}, event) {
        axios.post(`/api/unplannedEvent/changingFormat`, event)
            .then(res => {
                commit('CREATE_UNPLANNEDEVENT_CHANGINGFORMAT', res.data)
            }).catch(err => {
            console.log(err)
        })

    },


    create_UnplannedEvent_Clientchanging({commit}, event) {
        axios.post(`/api/unplannedEvent/clientChanging`, event)
            .then(res => {
                commit('CREATE_UNPLANNEDEVENT_CLIENTCHANGING', res.data)
            }).catch(err => {
            console.log(err)
        })

    },


    create_UnplannedEvent_CIP({commit}, event) {
        axios.post(`/api/unplannedEvent/CIP`, event)
            .then(res => {
                commit('CREATE_UNPLANNEDEVENT_CIP', res.data)
            }).catch(err => {
            console.log(err)
        })

    },

    create_UnplannedEvent_UnplannedDowntime({commit}, event) {
        axios.post(`/api/unplannedEvent/unplannedDowntime`, event)
            .then(res => {
                commit('CREATE_UNPLANNEDEVENT_UNPLANNEDDOWNTIME', res.data)
            }).catch(err => {
            console.log(err)
        })
    },

    create_plannedEvent({commit}, event) {
        axios.post(`/api/plannedEvent`, event)
            .then(res => {
                commit('CREATE_PLANNEDEVENT', res.data)
            }).catch(err => {
            console.log(err)
        })

    },

    stop_PO({commit}, PONumber) {
        axios.post(`/api/stopPO/${PONumber}`, PONumber)
            .then(res => {
                commit('STOP_PO', res.data)
            }).catch(err => {
            console.log(err)
        })

    },

    create_SpeedLoss({commit}, event) {
        axios.post(`/api/speedLoss`, event)
            .then(res => {
                commit('CREATE_SPEEDLOSS', res.data)
            }).catch(err => {
            console.log(err)
        })

    },


};

export default actions
