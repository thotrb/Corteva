<template>
    <div class="d-flex main-container">
        
        <!-- Interval, site and production line selection menu -->
        <div class="d-flex selection-menu">
            <!-- Site and production line selection-->
            <div class="d-flex site-pl-selection">
                <div class="d-flex">
                    <label for="site-selection">Site: </label>
                    <select id="site-selection" v-model="site">
                        <option disabled selected value>-- Select --</option>
                        <template v-for="site of sites[0]">
                            <option v-bind:key="site.name" v-bind:value="site.name">{{site.name}}</option>
                        </template>
                    </select>
                </div>
                <div class="d-flex">
                    <label for="pl-selection">Production line: </label>
                    <select id="pl-selection" v-on:change="productionLineSelected();">
                        <option disabled selected value>-- Select --</option>
                        <template v-for="productionLine of sites[1]">
                            <template v-if="productionLine.name === site">
                                <option v-bind:key="productionLine.productionline_name" v-bind:value="productionLine.productionline_name">
                                    {{productionLine.productionline_name}}
                                </option>
                            </template>
                        </template>
                    </select>
                </div>
            </div>
        </div>


        <div class="d-flex content-panel">
            <div class="external-shutdown-panel">
                <div class="title-container">
                    <span class="content-title">Machines Shutdown</span>
                </div>
                <div class="chart-container">
                    <canvas class="chart" id="machines-shutdown-chart"></canvas>
                </div>
                <div class="d-flex table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Machine Shutdown</th>
                                <th scope="col">Total Downtime</th>
                                <th scope="col">Frequency</th>
                                <th scope="col">Average Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="downtimeCat of Object.keys(downtimes.machines)">
                                <tr :key="downtimeCat">
                                    <td scope="col">{{downtimeCat}}</td>
                                    <td scope="col">{{downtimes.machines[downtimeCat].totalDuration}}</td>
                                    <td scope="col">{{downtimes.machines[downtimeCat].totalNb}}</td>
                                    <td scope="col">{{downtimes.machines[downtimeCat].avgDuration}}</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex downtime-percent-container">
                    <p class="downtime-percent">{{downtimePercentages.machines || '--'}} % of Unplanned Downtime</p>
                </div>
            </div>
            <div class="machines-shutdown-panel">
                <div class="title-container">
                    <span class="content-title">External Shutdown</span>
                </div>
                <div class="chart-container">
                    <canvas class="chart" id="external-shutdown-chart"></canvas>
                </div>
                <div class="d-flex table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">External Shutdown</th>
                                <th scope="col">Total Downtime</th>
                                <th scope="col">Frequency</th>
                                <th scope="col">Average Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="downtimeCat of Object.keys(downtimes.external)">
                                <tr :key="downtimeCat">
                                    <td scope="col">{{downtimeCat}}</td>
                                    <td scope="col">{{downtimes.external[downtimeCat].totalDuration}}</td>
                                    <td scope="col">{{downtimes.external[downtimeCat].totalNb}}</td>
                                    <td scope="col">{{downtimes.external[downtimeCat].avgDuration}}</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex downtime-percent-container">
                    <p class="downtime-percent">{{downtimePercentages.external || '--'}} % of Unplanned Downtime</p>
                </div>
            </div>
        </div>
    </div>
</template>                                                                                           

<script>
    import {mapGetters} from "vuex";
    import ProductionWindow from './productionWindow.vue';

    export default {
        name: "unplannedDowntimeShutdowns",

        data() {

            var data = {
                chartObjects: {
                    external: undefined,
                    machines: undefined,
                    created: false
                },
                downtimes: {
                    external: {},
                    machines: {}
                },
                downtimePercentages: {
                    external: undefined,
                    machines: undefined
                },
                startYear: 2000,
                currentYear: (new Date()).getFullYear(),
                site: '',
                productionLine: ''
            }

            return data;
        },

        methods: {
            resolveAfter: function(milliseconds) {
                return new Promise(resolve => {
                    setTimeout(() => resolve(), milliseconds);
                });
            },
            
            productionLineSelected: function() {
                if (document.getElementById("pl-selection").value) this.chargeData();
            },

            createDowntimeObject: function() {
            
            }, 

            chargeData: function() {
                const selectedPL = document.getElementById('pl-selection').value;
                const dateFrom = this.currentYear + '-01-01';
                const dateTo = this.currentYear + '-12-31';
                const params = [selectedPL, dateFrom, dateTo];

                this.$store.dispatch('fetchDowntimeEvents', params).then(() => {
                    this.resolveAfter(1000).then(() => {
                        this.createDowntimeObject();
                        if (!this.chartObjects.created) this.createCharts();

                        //Structure data
                        let allShutdowns = {
                            external: this.unplannedDowntimeEvents[0].external,
                            machines: this.unplannedDowntimeEvents[0].machines
                        }
                    
                        this.downtimes = {
                            machines: {},
                            external: {}
                        }
                        
                        for (let shtdCat of ['external', 'machines']) {
                            for (let shtdEvent of allShutdowns[shtdCat]) {
                                //Shutdown type exists already
                                if (!this.downtimes[shtdCat][shtdEvent.type]) {
                                    this.downtimes[shtdCat][shtdEvent.type] = {
                                        events: [],
                                        totalDuration: 0,
                                        totalNb: 0,
                                        avgDuration: 0
                                    };
                                }

                                this.downtimes[shtdCat][shtdEvent.type].events.push(shtdEvent);
                                this.downtimes[shtdCat][shtdEvent.type].totalDuration += shtdEvent.total_duration;
                                this.downtimes[shtdCat][shtdEvent.type].totalNb++;
                            }

                            //Calculate average durations
                            for (let shtdEventType of Object.keys(this.downtimes[shtdCat])) {
                                const eventTypeAvgDuration = Math.round(this.downtimes[shtdCat][shtdEventType].totalDuration / this.downtimes[shtdCat][shtdEventType].totalNb);
                                this.downtimes[shtdCat][shtdEventType].avgDuration = eventTypeAvgDuration;
                            }

                            //Create charts' datasets
                            this.chartObjects[shtdCat].data.labels = Object.keys(this.downtimes[shtdCat]);
                            this.chartObjects[shtdCat].data.datasets[0].data = [];
                            this.chartObjects[shtdCat].data.datasets[1].data = [];
                            for (let shtdType of Object.keys(this.downtimes[shtdCat])) {
                                this.chartObjects[shtdCat].data.datasets[0].data.push(this.downtimes[shtdCat][shtdType].totalDuration);
                                this.chartObjects[shtdCat].data.datasets[1].data.push(this.downtimes[shtdCat][shtdType].totalNb);
                            }
                            this.chartObjects[shtdCat].update();
                        }

                        //Calculate unplanned downtime percentages
                        let categoryTotalDowntime = {
                            machines: 0,
                            external: 0,
                            total: 0
                        };
                        for (let shtdCat of ['external', 'machines']) {
                            for (let event of Object.values(this.downtimes[shtdCat])) {
                                categoryTotalDowntime[shtdCat] += event.totalDuration;
                                categoryTotalDowntime.total += event.totalDuration; 
                            }
                        }
                        for (let shtdCat of ['external', 'machines']) {
                            this.downtimePercentages[shtdCat] = Math.round((categoryTotalDowntime[shtdCat]/categoryTotalDowntime.total)*100)
                        }
                    });
                });
            },

            createCharts: function() {
                this.chartObjects.created = true;
                for (let ch of ['external', 'machines']) {
                    this.chartObjects[ch] = new Chart(ch + '-shutdown-chart', {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                                label: 'Time in minutes',
                                backgroundColor: 'rgb(112, 184, 232)',
                                data: []
                            },
                            {
                                label: 'Number',
                                backgroundColor: 'rgb(246, 184, 192)',
                                data: [],
                                yAxisID: "freq"
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            time: {
                                axis: 'y',
                                title: {
                                    display: true,
                                    text: 'Time in minutes'
                                },
                                position: 'left'
                            },
                            freq: {
                                axis: 'y',
                                title: {
                                    display: true,
                                    text: 'Number'
                                },
                                position: 'right'
                            }
                        },
                        plugins: {
                            legend: {
                                display: true
                            }
                        }
                    },
                });
                }
            }
        },

        mounted() {
            this.$store.dispatch('fetchSites');

            //Load chart.js into vue component
            let chartJs = document.createElement('script');
            chartJs.setAttribute('src', 'https://cdn.jsdelivr.net/npm/chart.js');
            document.head.appendChild(chartJs);
        },

        computed: {
            ...mapGetters(['sites', 'unplannedDowntimeEvents'])
        },

        components: {
            ProductionWindow
        }
    }
</script>
<style scoped>

    div.main-container {
        flex-direction: column;
        background-color: white;
        padding: 20px;
        min-width: 1000px;
        border-radius: 5px;
        margin: 20px 0px;
    }

    div.selection-menu {
        flex-direction: row;
        padding: 20px 0px;
        border-bottom: solid 1px;
    }

    div.site-pl-selection {
        flex-direction: column;
        justify-content: space-evenly;
        min-width: 200px;
    }

    div.site-pl-selection > div{
        align-items: center;
    }

    div.site-pl-selection select {
        width: 100%;
    }

    div.site-pl-selection label {
        margin: 0px 10px 0px 0px;
    }

    div.title-container {
        margin-top: 10px;
        display: flex;
        justify-content: center;
    }

    span.content-title {
        font-size: 20px;
        font-weight: bold;
    }

    div.content-panel > div {
        display: flex;
        flex-direction: column;
        width: 50%;
    }

    div.chart-container {
        padding: 15px;
        height: 300px;
    }

    div.table-container {
        justify-content: center;
        padding: 15px 30px;
    }

     


    div.downtime-percent-container {
        justify-content: center;
    }

    p.downtime-percent {
        font-size: 16px;
    }

    thead {
        color: white;
        background: #56baed;
    }
 
</style>