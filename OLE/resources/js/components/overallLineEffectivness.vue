<template>
    <div>
        <!--
        {{allEvents['EVENTS']}}
-->
        <div class="d-flex">
            <div class="col">

                <div class="d-flex">

                    <form>
                        <label class="" for="site">Site : </label>
                        <select name="site" id="site" class="form-select" v-model="site">
                            <template v-for="site in sites[0]">
                                <option v-bind:value="site.name">
                                    {{site.name}}
                                </option>
                            </template>
                        </select>
                    </form>


                </div>


                <div class="d-flex">
                    <form>
                        <label class="" for="productionline">Ligne de production : </label>
                        <select name="productionline" id="productionline" class="form-select" v-model="productionline">
                            <template v-for="productionline in sites[1]">
                                <template v-if="productionline.name === site">
                                    <option v-bind:value="productionline.productionline_name">
                                        {{productionline.productionline_name}}
                                    </option>
                                </template>

                            </template>

                        </select>
                    </form>
                </div>

                <br/>

                <input v-on:click="load()" type="button" class="btn btn-outline-info" value="Charger">

                <br/>
                <br/>
                <br/>
            </div>

            <div class="col">
                <h1>
                    Fenetre de production

                </h1>
                <br/>

                <div class="d-flex">
                    <label class="" for="startYear">Year</label>

                    <select id="startYear" v-model="year">
                        <template v-for="year of years">
                            <option v-bind:key="year" v-bind:value="year">{{year}}</option>
                        </template>
                    </select>


                </div>
            </div>


        </div>
        <div align="center">
            <template v-if="show===1">

                <h1>
                    Overall Line Effectivness
                </h1>

            </template>
            <div class="d-flex">
                <div class="col-8">

                    <div class="wrapper">
                        <canvas id="myChart5"></canvas>
                    </div>
                </div>


                <div class="col">
                    <template v-if="show===1">

                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Peak Season</th>
                                <th scope="col">All Year</th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row">Availability</th>
                                <td>{{moyenneAvailability * 100}} % </td>
                                <td>{{moyenneAvailability * 100}} %</td>
                            </tr>
                            <tr>
                                <th scope="row">Performance</th>
                                <td>{{moyennePerformance * 100}} %</td>
                                <td>{{moyennePerformance * 100}} %</td>
                            </tr>
                            <tr>
                                <th scope="row">Quality</th>
                                <td>{{moyenneQuality * 100}} % </td>
                                <td>{{moyenneQuality * 100}} % </td>
                            </tr>
                            <tr>
                                <th scope="row">OLE</th>
                                <td>{{moyenneOLE * 100}} % </td>
                                <td>{{moyenneOLE * 100}} % </td>
                            </tr>
                            </tbody>
                        </table>

                        <br/>

                        <h3>
                            Trend versus previous year
                        </h3>

                        <br/>

                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col"></th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row">Availability</th>
                                <td>{{((moyenneAvailability - moyenneAvailability2)*100).toFixed(2) }} % </td>

                            </tr>
                            <tr>
                                <th scope="row">Performance</th>
                                <td>{{((moyennePerformance - moyennePerformance2)*100).toFixed(2) }} % </td>


                            </tr>
                            <tr>
                                <th scope="row">Quality</th>
                                <td>{{((moyenneQuality - moyenneQuality2)*100).toFixed(2) }} %</td>
                            </tr>
                            <tr>
                                <th scope="row">OLE</th>
                                <td>{{((moyenneOLE - moyenneOLE2)*100).toFixed(2) }} % </td>
                            </tr>
                            </tbody>
                        </table>


                    </template>
                </div>




            </div>

        </div>

    </div>

</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "overallLineEffectivness",


        data() {
            return {
                months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                years: [],
                year: 0,
                yearsAfterFrom: [],
                currentYear: (new Date()).getFullYear(),
                startYear: 2015,
                site: '',
                productionline: '',
                show: 0,


                OLEPerMonth: [],
                AvailabilityPerMonth: [],
                PerformancePerMonth: [],
                QualityPerMonth: [],
                netOperatingTimePerMonth: [],
                plannedDowntimesPerMonth: [],
                unplannedDowntimesPerMonth: [],
                plannedProductionTimePerMonth: [],
                operatingTimePerMonth: [],

                OLEPerMonth2: [],
                AvailabilityPerMonth2: [],
                PerformancePerMonth2: [],
                QualityPerMonth2: [],
                netOperatingTimePerMonth2: [],
                plannedDowntimesPerMonth2: [],
                unplannedDowntimesPerMonth2: [],
                plannedProductionTimePerMonth2: [],
                operatingTimePerMonth2: [],

                moyenneOLE: 0,
                moyenneQuality: 0,
                moyennePerformance:0,
                moyenneAvailability:0,


                moyenneOLE2: 0,
                moyenneQuality2: 0,
                moyennePerformance2:0,
                moyenneAvailability2:0,
            }
        },

        methods: {

            load: async function () {
                if (this.productionline !== '' && this.year !== '') {
                    var firstDayYear = this.year + '-01-01';
                    var lastDayYear = this.year + '-12-31';

                    var tab = [];
                    tab.push(this.site);
                    tab.push(this.productionline);
                    tab.push(firstDayYear);
                    tab.push(lastDayYear);
                    this.$store.dispatch('fetchAllEvents', tab);
                    await this.resolveAfter15Second();
                    this.loadProductionTime();

                    firstDayYear = this.year-1 + '-01-01';
                    lastDayYear = this.year-1 + '-12-31';
                    tab = [];
                    tab.push(this.site);
                    tab.push(this.productionline);
                    tab.push(firstDayYear);
                    tab.push(lastDayYear);
                    this.$store.dispatch('fetchAllEvents', tab);
                    await this.resolveAfter15Second();
                    this.loadProductionTime2();

                    this.show = 1;
                    this.graph2();
                }


            },

            resolveAfter15Second: function () {
                return new Promise(resolve => {
                    setTimeout(() => {
                        resolve('resolved');
                    }, 1500);
                });
            },

            loadProductionTime: function () {



                var sommeWorkingTimePerMonth = [];
                var sommeQtyProducedPerMonth = [];
                var sommeRejectionPerMonth = [];
                var fillerCounterPerMonth = [];
                var caperCounterPerMonth = [];
                var labelerCounterPerMonth = [];
                var weightBoxCounterPerMonth = [];
                var sumPlannedEventsPerMonth = [];
                var sumUnplannedEventsPerMonth = [];


                for (let i = 0; i < 12; i++) {
                    sommeWorkingTimePerMonth[i] = 0;
                    sommeQtyProducedPerMonth[i] = 0;
                    sommeRejectionPerMonth[i] = 0;
                    fillerCounterPerMonth[i] = 0;
                    caperCounterPerMonth[i] = 0;
                    labelerCounterPerMonth[i] = 0;
                    weightBoxCounterPerMonth[i] = 0;
                    sumPlannedEventsPerMonth[i] = 0;
                    sumUnplannedEventsPerMonth[i] = 0;
                    this.OLEPerMonth[i] = 0;
                    this.AvailabilityPerMonth[i] = 0;
                    this.PerformancePerMonth[i] = 0;
                    this.QualityPerMonth[i] = 0;
                    this.netOperatingTimePerMonth[i] = 0;
                    this.plannedDowntimesPerMonth [i] = 0;
                    this.unplannedDowntimesPerMonth[i] = 0;
                    this.plannedProductionTimePerMonth[i] = 0;
                    this.operatingTimePerMonth[i] = 0;


                }

                var month = 0;


                for (let i = 0; i < this.allEvents['SITE'].length; i++) {

                    month = this.allEvents['SITE'][i].created_at.split('-')[1] - 1;
                    sommeWorkingTimePerMonth[month] += this.allEvents['SITE'][i].workingDuration * 1;


                    let PO = this.allEvents['SITE'][i];

                    sommeQtyProducedPerMonth[month] += this.allEvents['SITE'][i].qtyProduced * this.allEvents['SITE'][i].bottlesPerCase * 1;

                    sommeRejectionPerMonth[month] += PO.fillerRejection * 1 + PO.caperRejection * 1 + PO.labelerRejection * 1 + PO.weightBoxRejection * 1;


                    fillerCounterPerMonth[month] += PO.fillerCounter * 1;
                    caperCounterPerMonth[month] += PO.caperCounter * 1;
                    labelerCounterPerMonth[month] += PO.labelerCounter * 1;
                    weightBoxCounterPerMonth[month] += PO.weightBoxCounter * 1;

                    this.netOperatingTimePerMonth[month] += (this.allEvents['SITE'][i].qtyProduced * this.allEvents['SITE'][i].bottlesPerCase * 1) / this.allEvents['SITE'][i].idealRate * 1;

                    for (let j = 0; j < this.allEvents['EVENTS'].length; j++) {

                        if (this.allEvents['EVENTS'][j].OLE === PO.number) {
                            sumUnplannedEventsPerMonth[month] += this.allEvents['EVENTS'][j].total_duration * 1;

                        }

                    }

                    for (let k = 0; k < this.allEvents['PLANNEDEVENTS'].length; k++) {
                        if (this.allEvents['PLANNEDEVENTS'][k].OLE === PO.number) {
                            sumPlannedEventsPerMonth[month] += this.allEvents['PLANNEDEVENTS'][k].duration * 1;
                        }
                    }
                }

                this.plannedDowntimesPerMonth = sumPlannedEventsPerMonth;
                this.unplannedDowntimesPerMonth = sumUnplannedEventsPerMonth;

                for (let i = 0; i < this.plannedProductionTimePerMonth.length; i++) {
                    this.plannedProductionTimePerMonth[i] = sommeWorkingTimePerMonth[i] - sumPlannedEventsPerMonth[i];
                }

                for (let i = 0; i < this.operatingTimePerMonth.length; i++) {
                    this.operatingTimePerMonth[i] = sommeWorkingTimePerMonth[i] - sumPlannedEventsPerMonth[i] - sumUnplannedEventsPerMonth[i];
                }
                /**
                 console.log('indice :');

                 console.log(sommeWorkingTimePerMonth);
                 console.log(sumPlannedEventsPerMonth);
                 console.log(sumUnplannedEventsPerMonth);
                 **/
                for (let i = 0; i < this.AvailabilityPerMonth.length; i++) {
                    if (this.operatingTimePerMonth[i] === 0) {
                        this.AvailabilityPerMonth[i] = 0;

                    } else {
                        this.AvailabilityPerMonth[i] = this.operatingTimePerMonth[i] / this.plannedProductionTimePerMonth[i];
                    }
                }

                for (let i = 0; i < this.PerformancePerMonth.length; i++) {
                    if (this.netOperatingTimePerMonth[i] === 0) {
                        this.PerformancePerMonth[i] = 0;
                    } else {
                        this.PerformancePerMonth[i] = this.netOperatingTimePerMonth[i] / this.operatingTimePerMonth[i];
                    }
                }


                for (let i = 0; i < this.QualityPerMonth.length; i++) {
                    if (sommeRejectionPerMonth[i] === 0 && fillerCounterPerMonth[i] === 0 && caperCounterPerMonth[i] === 0
                        && labelerCounterPerMonth[i] === 0 && weightBoxCounterPerMonth[i] === 0) {
                        this.QualityPerMonth[i] = 1;
                    } else {
                        var s = (fillerCounterPerMonth[i] - sommeQtyProducedPerMonth[i]) + (caperCounterPerMonth[i] - sommeQtyProducedPerMonth[i])
                            + (labelerCounterPerMonth[i] - sommeQtyProducedPerMonth[i]) + (weightBoxCounterPerMonth[i] - sommeQtyProducedPerMonth[i]);
                        this.QualityPerMonth[i] = (sommeQtyProducedPerMonth[i]) / (sommeQtyProducedPerMonth[i] + sommeRejectionPerMonth[i] + s);

                    }
                }

                for (let i = 0; i < this.OLEPerMonth.length; i++) {
                    this.OLEPerMonth[i] = this.AvailabilityPerMonth[i] * this.PerformancePerMonth[i] * this.QualityPerMonth[i];
                }

                console.log(sommeQtyProducedPerMonth);
                console.log(this.PerformancePerMonth);
                console.log(this.QualityPerMonth);
                console.log(this.AvailabilityPerMonth);




                for (let i = 0; i < this.OLEPerMonth.length; i++) {
                    this.moyenneOLE += this.OLEPerMonth[i];
                    this.moyenneAvailability += this.AvailabilityPerMonth[i];
                    this.moyennePerformance += this.PerformancePerMonth[i];
                    this.moyenneQuality += this.QualityPerMonth[i];
                }

                this.moyenneOLE = (this.moyenneOLE/12).toFixed(2);
                this.moyenneAvailability = (this.moyenneAvailability/12).toFixed(2);
                this.moyennePerformance = (this.moyennePerformance/12).toFixed(2);
                this.moyenneQuality = (this.moyenneQuality/12).toFixed(2);




            },

            loadProductionTime2 : function (){

                var sommeWorkingTimePerMonth = [];
                var sommeQtyProducedPerMonth = [];
                var sommeRejectionPerMonth = [];
                var fillerCounterPerMonth = [];
                var caperCounterPerMonth = [];
                var labelerCounterPerMonth = [];
                var weightBoxCounterPerMonth = [];
                var sumPlannedEventsPerMonth = [];
                var sumUnplannedEventsPerMonth = [];


                for (let i = 0; i < 12; i++) {
                    sommeWorkingTimePerMonth[i] = 0;
                    sommeQtyProducedPerMonth[i] = 0;
                    sommeRejectionPerMonth[i] = 0;
                    fillerCounterPerMonth[i] = 0;
                    caperCounterPerMonth[i] = 0;
                    labelerCounterPerMonth[i] = 0;
                    weightBoxCounterPerMonth[i] = 0;
                    sumPlannedEventsPerMonth[i] = 0;
                    sumUnplannedEventsPerMonth[i] = 0;
                    this.OLEPerMonth2[i] = 0;
                    this.AvailabilityPerMonth2[i] = 0;
                    this.PerformancePerMonth2[i] = 0;
                    this.QualityPerMonth2[i] = 0;
                    this.netOperatingTimePerMonth2[i] = 0;
                    this.plannedDowntimesPerMonth2[i] = 0;
                    this.unplannedDowntimesPerMonth2[i] = 0;
                    this.plannedProductionTimePerMonth2[i] = 0;
                    this.operatingTimePerMonth2[i] = 0;


                }

                var month = 0;


                for (let i = 0; i < this.allEvents['SITE'].length; i++) {

                    month = this.allEvents['SITE'][i].created_at.split('-')[1] - 1;
                    sommeWorkingTimePerMonth[month] += this.allEvents['SITE'][i].workingDuration * 1;


                    let PO = this.allEvents['SITE'][i];

                    sommeQtyProducedPerMonth[month] += this.allEvents['SITE'][i].qtyProduced * this.allEvents['SITE'][i].bottlesPerCase * 1;

                    sommeRejectionPerMonth[month] += PO.fillerRejection * 1 + PO.caperRejection * 1 + PO.labelerRejection * 1 + PO.weightBoxRejection * 1;


                    fillerCounterPerMonth[month] += PO.fillerCounter * 1;
                    caperCounterPerMonth[month] += PO.caperCounter * 1;
                    labelerCounterPerMonth[month] += PO.labelerCounter * 1;
                    weightBoxCounterPerMonth[month] += PO.weightBoxCounter * 1;

                    this.netOperatingTimePerMonth2[month] += (this.allEvents['SITE'][i].qtyProduced * this.allEvents['SITE'][i].bottlesPerCase * 1) / this.allEvents['SITE'][i].idealRate * 1;

                    for (let j = 0; j < this.allEvents['EVENTS'].length; j++) {

                        if (this.allEvents['EVENTS'][j].OLE === PO.number) {
                            sumUnplannedEventsPerMonth[month] += this.allEvents['EVENTS'][j].total_duration * 1;

                        }

                    }

                    for (let k = 0; k < this.allEvents['PLANNEDEVENTS'].length; k++) {
                        if (this.allEvents['PLANNEDEVENTS'][k].OLE === PO.number) {
                            sumPlannedEventsPerMonth[month] += this.allEvents['PLANNEDEVENTS'][k].duration * 1;
                        }
                    }
                }

                this.plannedDowntimesPerMonth2 = sumPlannedEventsPerMonth;
                this.unplannedDowntimesPerMonth2 = sumUnplannedEventsPerMonth;

                for (let i = 0; i < this.plannedProductionTimePerMonth2.length; i++) {
                    this.plannedProductionTimePerMonth2[i] = sommeWorkingTimePerMonth[i] - sumPlannedEventsPerMonth[i];
                }

                for (let i = 0; i < this.operatingTimePerMonth2.length; i++) {
                    this.operatingTimePerMonth2[i] = sommeWorkingTimePerMonth[i] - sumPlannedEventsPerMonth[i] - sumUnplannedEventsPerMonth[i];
                }
                /**
                 console.log('indice :');

                 console.log(sommeWorkingTimePerMonth);
                 console.log(sumPlannedEventsPerMonth);
                 console.log(sumUnplannedEventsPerMonth);
                 **/
                for (let i = 0; i < this.AvailabilityPerMonth2.length; i++) {
                    if (this.operatingTimePerMonth2[i] === 0) {
                        this.AvailabilityPerMonth2[i] = 0;

                    } else {
                        this.AvailabilityPerMonth2[i] = this.operatingTimePerMonth2[i] / this.plannedProductionTimePerMonth2[i];
                    }
                }

                for (let i = 0; i < this.PerformancePerMonth2.length; i++) {
                    if (this.netOperatingTimePerMonth2[i] === 0) {
                        this.PerformancePerMonth2[i] = 0;
                    } else {
                        this.PerformancePerMonth2[i] = this.netOperatingTimePerMonth2[i] / this.operatingTimePerMonth2[i];
                    }
                }


                for (let i = 0; i < this.QualityPerMonth2.length; i++) {
                    if (sommeRejectionPerMonth[i] === 0 && fillerCounterPerMonth[i] === 0 && caperCounterPerMonth[i] === 0
                        && labelerCounterPerMonth[i] === 0 && weightBoxCounterPerMonth[i] === 0) {
                        this.QualityPerMonth2[i] = 1;
                    } else {
                        var s = (fillerCounterPerMonth[i] - sommeQtyProducedPerMonth[i]) + (caperCounterPerMonth[i] - sommeQtyProducedPerMonth[i])
                            + (labelerCounterPerMonth[i] - sommeQtyProducedPerMonth[i]) + (weightBoxCounterPerMonth[i] - sommeQtyProducedPerMonth[i]);
                        this.QualityPerMonth2[i] = (sommeQtyProducedPerMonth[i]) / (sommeQtyProducedPerMonth[i] + sommeRejectionPerMonth[i] + s);

                    }
                }

                for (let i = 0; i < this.OLEPerMonth2.length; i++) {
                    this.OLEPerMonth2[i] = this.AvailabilityPerMonth2[i] * this.PerformancePerMonth2[i] * this.QualityPerMonth2[i];
                }




                for (let i = 0; i < this.OLEPerMonth2.length; i++) {
                    this.moyenneOLE2 += this.OLEPerMonth2[i];
                    this.moyenneAvailability2 += this.AvailabilityPerMonth2[i];
                    this.moyennePerformance2 += this.PerformancePerMonth2[i];
                    this.moyenneQuality2 += this.QualityPerMonth2[i];
                }

                this.moyenneOLE2 = (this.moyenneOLE2/12).toFixed(2);
                this.moyenneAvailability2 = (this.moyenneAvailability2/12).toFixed(2);
                this.moyennePerformance2 = (this.moyennePerformance2/12).toFixed(2);
                this.moyenneQuality2 = (this.moyenneQuality2/12).toFixed(2);




            },

            graph2: function () {

                var chart = new CanvasJS.Chart("chartContainer", {
                    animationEnabled: true,
                    theme: "light2",
                    title:{
                        text: "Site Traffic"
                    },
                    axisX:{
                        valueFormatString: "DD MMM",
                        crosshair: {
                            enabled: true,
                            snapToDataPoint: true
                        }
                    },
                    axisY: {
                        title: "Number of Visits",
                        includeZero: true,
                        crosshair: {
                            enabled: true
                        }
                    },
                    toolTip:{
                        shared:true
                    },
                    legend:{
                        cursor:"pointer",
                        verticalAlign: "bottom",
                        horizontalAlign: "left",
                        dockInsidePlotArea: true,
                        itemclick: toogleDataSeries
                    },
                    data: [{
                        type: "line",
                        showInLegend: true,
                        name: "Total Visit",
                        markerType: "square",
                        xValueFormatString: "DD MMM, YYYY",
                        color: "#F08080",
                        dataPoints: [
                            { x: new Date(2017, 0, 3), y: 650 },
                            { x: new Date(2017, 0, 4), y: 700 },
                            { x: new Date(2017, 0, 5), y: 710 },
                            { x: new Date(2017, 0, 6), y: 658 },
                            { x: new Date(2017, 0, 7), y: 734 },
                            { x: new Date(2017, 0, 8), y: 963 },
                            { x: new Date(2017, 0, 9), y: 847 },
                            { x: new Date(2017, 0, 10), y: 853 },
                            { x: new Date(2017, 0, 11), y: 869 },
                            { x: new Date(2017, 0, 12), y: 943 },
                            { x: new Date(2017, 0, 13), y: 970 },
                            { x: new Date(2017, 0, 14), y: 869 },
                            { x: new Date(2017, 0, 15), y: 890 },
                            { x: new Date(2017, 0, 16), y: 930 }
                        ]
                    },
                        {
                            type: "line",
                            showInLegend: true,
                            name: "Unique Visit",
                            lineDashType: "dash",
                            dataPoints: [
                                { x: new Date(2017, 0, 3), y: 510 },
                                { x: new Date(2017, 0, 4), y: 560 },
                                { x: new Date(2017, 0, 5), y: 540 },
                                { x: new Date(2017, 0, 6), y: 558 },
                                { x: new Date(2017, 0, 7), y: 544 },
                                { x: new Date(2017, 0, 8), y: 693 },
                                { x: new Date(2017, 0, 9), y: 657 },
                                { x: new Date(2017, 0, 10), y: 663 },
                                { x: new Date(2017, 0, 11), y: 639 },
                                { x: new Date(2017, 0, 12), y: 673 },
                                { x: new Date(2017, 0, 13), y: 660 },
                                { x: new Date(2017, 0, 14), y: 562 },
                                { x: new Date(2017, 0, 15), y: 643 },
                                { x: new Date(2017, 0, 16), y: 570 }
                            ]
                        }]
                });
                chart.render();




                var ctx = document.getElementById("myChart5").getContext('2d');
                var tab = [];
                var colors = [];
                colors.push("#008d93");

                var ar = [];
                for (let j = 0; j < this.OLEPerMonth.length; j++) {
                    ar.push((this.OLEPerMonth[j] * 100).toFixed(2));
                }
                var obj = {
                    label: 'Overall Line Effectivness',
                    backgroundColor: colors[0],
                    data: ar
                };

                tab.push(obj);


                console.log('tab');

                console.log(tab);


                var myChart = new Chart(ctx, {
                    type: 'bar',
                    animationEnabled: true,
                    theme: "light2",
                    title:{
                        text: "Overall Line Effectiveness"
                    },
                    data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                        datasets: tab,

                    },
                    options: {
                        tooltips: {
                            displayColors: true,
                            callbacks: {
                                mode: 'x',
                            },
                        },
                        scales: {
                            xAxes: [{
                                stacked: true,
                                gridLines: {
                                    display: false,
                                }
                            }],
                            yAxes: [{
                                stacked: true,
                                ticks: {
                                    beginAtZero: true,
                                },
                                type: 'linear',
                            }]
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {position: 'bottom'},
                    }
                });

            },



        },


        mounted() {
            this.$store.dispatch('fetchSites');
            let chartJs = document.createElement('script');
            chartJs.setAttribute('src', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js');
            document.head.appendChild(chartJs);

            for (let i = this.startYear; i <= this.currentYear; i++) this.years.push(i);

        },

        computed: {
            ...mapGetters([
                'sites',
                'allEvents',

            ])
        }


    }
</script>

<style scoped>
    h1 {
        font-size: 1.4em;
        color: #56baed;
    }

    label {
        font-size: 1.4em;
        color: #56baed;
    }

    p {
        font-size: 1.4em;
        color: #56baed;
    }

    h2 {
        font-size: 1.2em;
        color: #56baed;
    }

    h4 {
        color: red;
    }


    div {
        background-color: #fff;
        padding: 15px;
    }

    thead {
        color: white;
        background: #56baed;
    }

    .container {
        margin-left: 60px;
    }

    h5 {
        margin-left: 60px;
    }


    .table-info-data {
        overflow: scroll;
        max-height: 300px;
    }


    .wrapper {
        width: 60%;
        display: block;
        overflow: hidden;
        margin: 0 auto;
        padding: 60px 50px;
        background: #fff;
        border-radius: 4px;
    }

    canvas {
        background: #fff;
        height: 400px;
    }

</style>
