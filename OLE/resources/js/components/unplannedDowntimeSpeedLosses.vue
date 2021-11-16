<template>
    <div class="d-flex main-container">

        <!-- Interval, site and production line selection menu -->
        <div class="d-flex selection-menu">
            <!-- Site and production line selection-->
            <div class="d-flex site-pl-selection">
                    <label for="site-selection">Site: </label>
                    <select id="site-selection" v-model="site">
                        <option disabled selected value>-- Select --</option>
                        <template v-for="site of sites[0]">
                            <option v-bind:key="site.name" v-bind:value="site.name">{{site.name}}</option>
                        </template>
                    </select>

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

                    <button id="pl-selection-load" type="button" class="btn btn-primary" v-on:click="chargeData();">Load</button>
            </div>

            <div class="production-window-container">
                <production-window />
            </div>
        </div>


        <div class="content-panel">
            <span class="content-title">Speed Losses</span>
                <div class="table-panel no-bottom-border rounded-top-left">
                    <span class="content-subtitle">Reduce rate at filler</span>
                    <div class="table-container">
                        <table class="table table-responsive table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Production Order</th>
                                    <th scope="col">Format</th>
                                    <th scope="col">Design Rate (cpm)</th>
                                    <th scope="col">Reduce Rate (cpm)</th>
                                    <th scope="col">Wasted Time (mn)</th>
                                    <th scope="col">Comments / Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="slEvent of slEvents['Reduced Rate At Filler'].events">
                                        <tr :key="slEvent">
                                            <td>{{slEvent.id}}</td>
                                            <td>{{slEvent.size}} L</td>
                                            <td>{{slEvent.idealRate}}</td>
                                            <td>{{slEvent.reducedRate}}</td>
                                            <td>{{slEvent.duration}}</td>
                                            <td>{{slEvent.comment}}</td>
                                        </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <span class="content-subtitle">{{slEvents['Reduced Rate At Filler'].percentage.y || '--'}}% of Speed Losses</span>
                </div>
                <div class="chart-panel no-bottom-border rounded-top-right">
                    <span class="content-subtitle">Filler Own Stop</span>
                    <div class="chart-container">
                        <canvas class="chart" id="own-stop-sl-chart"></canvas>
                    </div>
                    <span class="content-subtitle">{{slEvents['Filler Own Stoppage'].percentage.y || '--'}}% of Speed Losses</span>
                </div>

                <div class="table-panel rounded-bottom-left">
                    <span class="content-subtitle">Reduce rate at filler due to other machine capacity</span>
                    <div class="table-container">
                        <table class="table table-responsive table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Production Order</th>
                                    <th scope="col">Format</th>
                                    <th scope="col">Design Rate (cpm)</th>
                                    <th scope="col">Reduce Rate (cpm)</th>
                                    <th scope="col">Wasted Time (mn)</th>
                                    <th scope="col">Comments / Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="slEvent of slEvents['Reduced Rate At An Other Machine'].events">
                                    <tr :key="slEvent">
                                        <td>{{slEvent.id}}</td>
                                        <td>{{slEvent.size}} L</td>
                                        <td>{{slEvent.idealRate}}</td>
                                        <td>{{slEvent.reducedRate}}</td>
                                        <td>{{slEvent.duration}}</td>
                                        <td>{{slEvent.comment}}</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <span class="content-subtitle">{{slEvents['Reduced Rate At An Other Machine'].percentage.y || '--'}}% of Speed Losses</span>
                </div>
                <div class="chart-panel rounded-bottom-right">
                    <span class="content-subtitle">Filler Stop by Other Machine</span>
                    <div class="chart-container">
                        <canvas class="chart" id="other-machine-sl-chart"></canvas>
                    </div>
                    <span class="content-subtitle">{{slEvents['Filler Stop By Other Machine'].percentage.y || '--'}}% of Speed Losses</span>
                </div>
            </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import ProductionWindow from './productionWindow.vue';

    export default {
        name: "unplannedDowntimeSpeedLosses",

        data() {

            var data = {

                currentYear: (new Date()).getFullYear(),
                site: '',
                productionLine: '',
                chartObjects: {
                    'own-stop': {chart: undefined, labels: ['Filler Own Stoppage', 'Reduced Rate At Filler']},
                    'other-machine': {chart: undefined, labels: ['Filler Stop by Other Machine', 'Reduced Rate At And Other Machine']},
                    created: false
                },
                slEvents: {
                    'Reduced Rate At Filler': {events: [], totalDuration: 0, percentage: 0},
                    'Reduced Rate At An Other Machine': {events: [], totalDuration: 0, percentage: 0},
                    'Filler Own Stoppage': {events: [], totalDuration: 0, percentage: 0},
                    'Filler Stop By Other Machine': {events: [], totalDuration: 0, percentage: 0}
                }
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
                if (document.getElementById("pl-selection").value) {

                }

            },

            createDowntimeObject: function() {

            },

            chargeData: function() {
                const site = document.getElementById('site-selection').value;
                const selectedPL = document.getElementById('pl-selection').value;
                const begDate = document.getElementById('select-date-from').value;
                const endDate = document.getElementById('select-date-to').value;

                if (site && selectedPL && begDate && endDate) {
                    const params = [site, selectedPL, begDate, endDate];
                    console.log(params);
                    this.$store.dispatch('fetchAllEvents', params).then(() => {
                        
                        this.resolveAfter(1000).then(() => {

                            if (!this.chartObjects.created) this.createCharts();

                            //Reinitialize slEvents as empty arrays
                            Object.keys(this.slEvents).forEach(key => this.slEvents[key].events = []);

                            let chartData = {
                                "Filler Own Stoppage": {duration: 0, freq: 0},
                                "Filler Stop By Other Machine": {duration: 0, freq: 0},
                                "Reduced Rate At Filler": {duration: 0, freq: 0},
                                "Reduced Rate At An Other Machine": {duration: 0, freq: 0}
                            };

                            //Add fetched events to the slEvents variable
                            //Creates charts' data
                            if (this.allEvents.SLEVENTS) {
                                this.slEvents = this.allEvents.SLEVENTS.reduce((acc, slEvent) => {
                                    if (acc[slEvent.reason]) {
                                        //If event is concerned by a chart, create its data
                                        if (chartData[slEvent.reason]) {
                                            chartData[slEvent.reason].duration += slEvent.duration;
                                            chartData[slEvent.reason].freq++;
                                        }

                                        acc[slEvent.reason].totalDuration += slEvent.duration;
                                        const reducedRate = Math.floor(slEvent.qtyProduced / slEvent.workingDuration);
                                        slEvent.reducedRate = reducedRate || 0;
                                        acc[slEvent.reason].events.push(slEvent);
                                    }
                                    return acc;
                                }, this.slEvents);
                            }
                            
                            //Total speed loss duration as the sum of all categorie's durations
                            const totalSpeedLossDuration = Object.values(this.slEvents).reduce((acc, slCat) => {
                                return acc + slCat.totalDuration;
                            }, 0);

                            //Calculate Speed Losses Percentage by Category
                            Object.values(this.slEvents).forEach(slCat => {
                                slCat.percentage = ((slCat.totalDuration / totalSpeedLossDuration)*100).toFixed(2);
                            });

                            const map = {
                                'Filler Own Stoppage': 'own-stop',
                                'Filler Stop By Other Machine': 'other-machine',
                                'Reduced Rate At Filler': 'own-stop',
                                'Reduced Rate At An Other Machine': 'other-machine'
                            }
                            
                            //Update charts' data
                            Object.keys(map).forEach(key => {
                                this.chartObjects[map[key]].chart.data.datasets[0].data = [];
                                this.chartObjects[map[key]].chart.data.datasets[1].data = [];
                            });
                            Object.keys(map).forEach(key => {
                                this.chartObjects[map[key]].chart.data.datasets[0].data.push(chartData[key].duration);
                                this.chartObjects[map[key]].chart.data.datasets[1].data.push(chartData[key].freq);
                                this.chartObjects[map[key]].chart.update();
                            });
                        });
                        
                    });
                }
            },

            createCharts: function() {
                this.chartObjects.created = true;
                for (let slCat of ['own-stop', 'other-machine']) {
                    this.chartObjects[slCat].chart = new Chart(slCat + '-sl-chart', {
                    type: 'bar',
                    data: {
                        labels: this.chartObjects[slCat].labels,
                        datasets: [{
                                label: 'Time in minutes',
                                backgroundColor: 'rgb(245, 194, 67)',
                                data: []
                            },
                            {
                                label: 'Number',
                                backgroundColor: 'rgb(90, 90, 90)',
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

            if(sessionStorage.getItem("language") !== null){
                this.$i18n.locale = sessionStorage.getItem("language");
            }
            this.$store.dispatch('fetchSites');

            //Load chart.js into vue component
            let chartJs = document.createElement('script');
            chartJs.setAttribute('src', 'https://cdn.jsdelivr.net/npm/chart.js');
            document.head.appendChild(chartJs);


        },

        computed: {
            ...mapGetters(['sites', 'speedLoss', 'allEvents'])
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
        justify-content: space-between;
    }

    div.site-pl-selection {
        align-items: center;
    }

    div.site-pl-selection > *:not(label){
        height: 35px;
    }

    div.site-pl-selection label {
        margin: 0px 10px;
    }

    button#pl-selection-load {
        margin-left: 10px;
        background: rgb(245, 194, 67);
        color: rgb(90,90,90);
        border: none;
    }

    div.title-container {
        margin-top: 10px;
        display: flex;
        justify-content: center;
    }

    span.content-title {
        font-size: 24px;
        font-weight: bold;
        color: black;
        width: 100%;
        padding: 10px;
        text-align: center;
    }

    span.content-subtitle {
        font-size: 17px;
        font-weight: bold;
        color: black;
        width: 100%;
    }

    div.content-panel {
        display: flex;
        flex-wrap: wrap;
        flex-direction: row;
    }

    div.table-panel,
    div.chart-panel {
        display: flex;
        flex-direction: column;
        width: 50%;
        padding: 10px;
        border: solid 1px;
    }

    div.chart-panel {
        border-left: none;
    }

    div.table-container {
        height: 300px;
        overflow-y: scroll;
        margin: 10px 0px;
    }

    div.table-container th {
        text-align: center;
        border: none;
    }

    div.table-container th:first-of-type{
        border-top-left-radius: 7px;
        border-bottom-left-radius: 7px;
    }

    div.table-container th:last-of-type{
        border-top-right-radius: 7px;
        border-bottom-right-radius: 7px;
    }

    div.table-container td {
        text-align: center;
    }

    div.chart-container {
        height: 300px;
        margin: 10px 0px;
    }

    div.no-bottom-border {
        border-bottom: none;
    }

    div.rounded-top-left {
        border-top-left-radius: 5px;
    }

    div.rounded-top-right {
        border-top-right-radius: 5px;
    }

    div.rounded-bottom-left {
        border-bottom-left-radius: 5px;
    }

    div.rounded-bottom-right {
        border-bottom-right-radius: 5px;
    }

    table.table {
        margin: 10px 0px;
    }

    thead {
        color: rgb(90,90,90);
        background: rgb(245, 194, 67);
    }

</style>
