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
                        <label class="" for="productionline">{{$t("productionLine")}} : </label>
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

                <input v-on:click="load()" type="button" class="btn btn-outline-info" v-bind:value="lo">

                <br/>
                <br/>
                <br/>
            </div>

            <div class="col">
                <h1>
                    {{$t("productionShift")}}
                </h1>
                <br/>

                <div class="d-flex">
                    <label class="" for="startYear">{{$t("year")}}</label>

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
                    <!--
                                        <div class="wrapper">
                                            <canvas id="myChart5"></canvas>
                                        </div>
                    -->
                    <div id="chartContainer" style="height: 300px; width: 100%;"></div>

                </div>


                <div class="col">
                    <template v-if="show===1">

                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">{{$t("peakSeason")}}</th>
                                <th scope="col">{{$t("allYear")}}</th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row">{{$t("availability")}}</th>
                                <td>{{(AvailabilityPerMonth[peakSeason]*100).toFixed(2)}} %</td>
                                <td>{{(availability * 100).toFixed(2)}} %</td>
                            </tr>
                            <tr>
                                <th scope="row">{{$t("performance")}}</th>
                                <td>{{(PerformancePerMonth[peakSeason]*100).toFixed(2)}} %</td>
                                <td>{{(performance * 100).toFixed(2)}} %</td>
                            </tr>
                            <tr>
                                <th scope="row">{{$t("quality")}}</th>
                                <td>{{(QualityPerMonth[peakSeason]*100).toFixed(2)}} %</td>
                                <td>{{(quality * 100).toFixed(2)}} %</td>
                            </tr>
                            <tr>
                                <th scope="row">OLE</th>
                                <td>{{(OLEPerMonth[peakSeason]*100).toFixed(2)}} %</td>
                                <td>{{(OLE * 100).toFixed(2)}} %</td>
                            </tr>
                            </tbody>
                        </table>

                        <br/>

                        <h3>
                            {{$t("trendVersusPreviousYear")}}
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
                                <th scope="row">{{$t("availability")}}</th>
                                <td>{{((availability - availability2)*100).toFixed(2) }} %</td>

                            </tr>
                            <tr>
                                <th scope="row">{{$t("performance")}}</th>
                                <td>{{((performance - performance2)*100).toFixed(2) }} %</td>


                            </tr>
                            <tr>
                                <th scope="row">{{$t("quality")}}</th>
                                <td>{{((quality - quality2)*100).toFixed(2) }} %</td>
                            </tr>
                            <tr>
                                <th scope="row">OLE</th>
                                <td>{{((OLE - OLE2)*100).toFixed(2) }} %</td>
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

                peakSeason: 0,

                plannedDowntimes: 0,
                unplannedDowntimes: 0,
                plannedProductionTime: 0,
                operatingTime: 0,
                netOperatingTime: 0,
                performance: 0,
                availability: 0,
                quality: 0,
                OLE: 0,

                plannedDowntimes2: 0,
                unplannedDowntimes2: 0,
                plannedProductionTime2: 0,
                operatingTime2: 0,
                netOperatingTime2: 0,
                performance2: 0,
                availability2: 0,
                quality2: 0,
                OLE2: 0,

                lo : this.$t("load"),
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
                    this.loadProductionTimeThisYear();


                    firstDayYear = this.year - 1 + '-01-01';
                    lastDayYear = this.year - 1 + '-12-31';
                    tab = [];
                    tab.push(this.site);
                    tab.push(this.productionline);
                    tab.push(firstDayYear);
                    tab.push(lastDayYear);
                    this.$store.dispatch('fetchAllEvents', tab);
                    await this.resolveAfter15Second();
                    this.loadProductionTime2();
                    this.loadProductionTimePreviousYear();


                    this.show = 1;
                    this.graph2();
                }


            },

            loadProductionTimePreviousYear : function(){
                var sommePlannedEvents = 0;
                var sommeUnplannedEvents = 0;

                var sommeQtyProduced = 0;
                var sommeRejection = 0;

                var fillerCounter = 0;
                var caperCounter = 0;
                var labelerCounter = 0;
                var wieghtBoxCounter = 0;

                this.netOperatingTime2 = 0;
                var sommeWorkingTime = 0;


                for (let i = 0; i < this.allEvents['SITE'].length; i++) {

                    sommeWorkingTime += this.allEvents['SITE'][i].workingDuration * 1;


                    let PO = this.allEvents['SITE'][i];
                    sommeQtyProduced += this.allEvents['SITE'][i].qtyProduced * this.allEvents['SITE'][i].bottlesPerCase * 1;
                    sommeRejection += PO.fillerRejection * 1 + PO.caperRejection * 1 + PO.labelerRejection * 1 + PO.weightBoxRejection * 1;
                    fillerCounter += PO.fillerCounter * 1;
                    caperCounter += PO.caperCounter * 1;
                    labelerCounter += PO.labelerCounter * 1;
                    wieghtBoxCounter += PO.weightBoxCounter * 1;
                    this.netOperatingTime2 += (this.allEvents['SITE'][i].qtyProduced * this.allEvents['SITE'][i].bottlesPerCase * 1) / this.allEvents['SITE'][i].idealRate * 1;
                    for (let j = 0; j < this.allEvents['EVENTS'].length; j++) {

                        if (this.allEvents['EVENTS'][j].OLE === PO.number) {
                            sommeUnplannedEvents += this.allEvents['EVENTS'][j].total_duration * 1;


                        }

                    }

                    for (let k = 0; k < this.allEvents['PLANNEDEVENTS'].length; k++) {
                        if (this.allEvents['PLANNEDEVENTS'][k].OLE === PO.number) {
                            sommePlannedEvents += this.allEvents['PLANNEDEVENTS'][k].duration * 1;
                        }
                    }
                }


                this.plannedDowntimes2 = sommePlannedEvents;
                this.unplannedDowntimes2 = sommeUnplannedEvents;


                this.plannedProductionTime2 = sommeWorkingTime - sommePlannedEvents;
                this.operatingTime2 = sommeWorkingTime - sommePlannedEvents - sommeUnplannedEvents;

                this.availability2 = (this.operatingTime2 / this.plannedProductionTime2);
                this.performance2 = (this.netOperatingTime2 / this.operatingTime2);

                console.log('net OP TIME : ');
                console.log(this.netOperatingTime2);
                console.log(' OP TIME : ');
                console.log(this.operatingTime2);

                if (sommeRejection === 0 && fillerCounter === 0 && caperCounter === 0
                    && labelerCounter === 0 && wieghtBoxCounter === 0) {
                    this.quality2 = 1;
                } else {
                    var s = (fillerCounter - sommeQtyProduced) + (caperCounter - sommeQtyProduced)
                        + (labelerCounter - sommeQtyProduced) + (wieghtBoxCounter - sommeQtyProduced);
                    this.quality2 = ((sommeQtyProduced) / (sommeQtyProduced + sommeRejection + s));

                }


                if (this.operatingTime2 === 0) {
                    this.availability2 = 0;

                }

                if (this.netOperatingTime2 === 0) {
                    this.performance2 = 0;
                    this.unplannedDowntimes2 = 1;
                }

                if (this.plannedProductionTime2 === 0) {
                    this.plannedDowntimes2 = 1;
                }

                this.OLE2 = (this.availability2 * this.performance2 * this.quality2);


            },

            loadProductionTimeThisYear : function(){


                var sommePlannedEvents = 0;
                var sommeUnplannedEvents = 0;

                var sommeQtyProduced = 0;
                var sommeRejection = 0;

                var fillerCounter = 0;
                var caperCounter = 0;
                var labelerCounter = 0;
                var wieghtBoxCounter = 0;

                this.netOperatingTime = 0;
                var sommeWorkingTime = 0;


                for (let i = 0; i < this.allEvents['SITE'].length; i++) {

                    sommeWorkingTime += this.allEvents['SITE'][i].workingDuration * 1;


                    let PO = this.allEvents['SITE'][i];
                    sommeQtyProduced += this.allEvents['SITE'][i].qtyProduced * this.allEvents['SITE'][i].bottlesPerCase * 1;
                    sommeRejection += PO.fillerRejection * 1 + PO.caperRejection * 1 + PO.labelerRejection * 1 + PO.weightBoxRejection * 1;
                    fillerCounter += PO.fillerCounter * 1;
                    caperCounter += PO.caperCounter * 1;
                    labelerCounter += PO.labelerCounter * 1;
                    wieghtBoxCounter += PO.weightBoxCounter * 1;
                    this.netOperatingTime += (this.allEvents['SITE'][i].qtyProduced * this.allEvents['SITE'][i].bottlesPerCase * 1) / this.allEvents['SITE'][i].idealRate * 1;
                    for (let j = 0; j < this.allEvents['EVENTS'].length; j++) {

                        if (this.allEvents['EVENTS'][j].OLE === PO.number) {
                            sommeUnplannedEvents += this.allEvents['EVENTS'][j].total_duration * 1;


                        }

                    }

                    for (let k = 0; k < this.allEvents['PLANNEDEVENTS'].length; k++) {
                        if (this.allEvents['PLANNEDEVENTS'][k].OLE === PO.number) {
                            sommePlannedEvents += this.allEvents['PLANNEDEVENTS'][k].duration * 1;
                        }
                    }
                }


                this.plannedDowntimes = sommePlannedEvents;
                this.unplannedDowntimes = sommeUnplannedEvents;


                this.plannedProductionTime = sommeWorkingTime - sommePlannedEvents;
                this.operatingTime = sommeWorkingTime - sommePlannedEvents - sommeUnplannedEvents;

                this.availability =( this.operatingTime / this.plannedProductionTime);
                this.performance = (this.netOperatingTime / this.operatingTime);

                console.log('net OP TIME : ');
                console.log(this.netOperatingTime);
                console.log(' OP TIME : ');
                console.log(this.operatingTime);

                if (sommeRejection === 0 && fillerCounter === 0 && caperCounter === 0
                    && labelerCounter === 0 && wieghtBoxCounter === 0) {
                    this.quality = 1;
                } else {
                    var s = (fillerCounter - sommeQtyProduced) + (caperCounter - sommeQtyProduced)
                        + (labelerCounter - sommeQtyProduced) + (wieghtBoxCounter - sommeQtyProduced);
                    this.quality = ((sommeQtyProduced) / (sommeQtyProduced + sommeRejection + s));

                }


                if (this.operatingTime === 0) {
                    this.availability = 0;

                }

                if (this.netOperatingTime === 0) {
                    this.performance = 0;
                    this.unplannedDowntimes = 1;
                }

                if (this.plannedProductionTime === 0) {
                    this.plannedDowntimes = 1;
                }

                this.OLE = this.availability * this.performance * this.quality;



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

                    var max = -1;
                    for(let i=0; i<sommeWorkingTimePerMonth.length; i++){

                        if(sommeWorkingTimePerMonth[i] > max){
                            max = sommeWorkingTimePerMonth[i];
                            this.peakSeason = i;
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



            },

            loadProductionTime2: function () {

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


            },

            graph2: function () {


                var chart = new CanvasJS.Chart("chartContainer", {
                    animationEnabled: true,
                    theme: "light2",
                    title: {
                        text: "Overall Line Effectiveness"
                    },
                    axisX: {
                        valueFormatString: "MMM"
                    },
                    axisY: {},
                    toolTip: {
                        shared: true
                    },
                    legend: {
                        cursor: "pointer",
                        //itemclick: this.toggleDataSeries
                    },
                    data: [
                        {
                            type: "column",
                            name: "OLE",
                            showInLegend: true,
                            xValueFormatString: "MMMM YYYY",
                            yValueFormatString: "#,##0",
                            dataPoints: [
                                {x: new Date(this.year, 0), y: this.OLEPerMonth[0]},
                                {x: new Date(this.year, 1), y: this.OLEPerMonth[1]},
                                {x: new Date(this.year, 2), y: this.OLEPerMonth[2]},
                                {x: new Date(this.year, 3), y: this.OLEPerMonth[3]},
                                {x: new Date(this.year, 4), y: this.OLEPerMonth[4]},
                                {x: new Date(this.year, 5), y: this.OLEPerMonth[5]},
                                {x: new Date(this.year, 6), y: this.OLEPerMonth[6]},
                                {x: new Date(this.year, 7), y: this.OLEPerMonth[7]},
                                {x: new Date(this.year, 8), y: this.OLEPerMonth[8]},
                                {x: new Date(this.year, 9), y: this.OLEPerMonth[9]},
                                {x: new Date(this.year, 10), y: this.OLEPerMonth[10]},
                                {x: new Date(this.year, 11), y: this.OLEPerMonth[11]}
                            ]
                        },
                        {
                            type: "line",
                            name: "Performance",
                            showInLegend: true,
                            yValueFormatString: "#,##0",
                            dataPoints: [
                                {x: new Date(this.year, 0), y: this.PerformancePerMonth[0]},
                                {x: new Date(this.year, 1), y: this.PerformancePerMonth[1]},
                                {x: new Date(this.year, 2), y: this.PerformancePerMonth[2]},
                                {x: new Date(this.year, 3), y: this.PerformancePerMonth[3]},
                                {x: new Date(this.year, 4), y: this.PerformancePerMonth[4]},
                                {x: new Date(this.year, 5), y: this.PerformancePerMonth[5]},
                                {x: new Date(this.year, 6), y: this.PerformancePerMonth[6]},
                                {x: new Date(this.year, 7), y: this.PerformancePerMonth[7]},
                                {x: new Date(this.year, 8), y: this.PerformancePerMonth[8]},
                                {x: new Date(this.year, 9), y: this.PerformancePerMonth[9]},
                                {x: new Date(this.year, 10), y: this.PerformancePerMonth[10]},
                                {x: new Date(this.year, 11), y: this.PerformancePerMonth[11]}
                            ]
                        },
                        {
                            type: "line",
                            name: "Availability",
                            showInLegend: true,
                            yValueFormatString: "#,##0",
                            dataPoints: [
                                {x: new Date(this.year, 0), y: this.AvailabilityPerMonth[0]},
                                {x: new Date(this.year, 1), y: this.AvailabilityPerMonth[1]},
                                {x: new Date(this.year, 2), y: this.AvailabilityPerMonth[2]},
                                {x: new Date(this.year, 3), y: this.AvailabilityPerMonth[3]},
                                {x: new Date(this.year, 4), y: this.AvailabilityPerMonth[4]},
                                {x: new Date(this.year, 5), y: this.AvailabilityPerMonth[5]},
                                {x: new Date(this.year, 6), y: this.AvailabilityPerMonth[6]},
                                {x: new Date(this.year, 7), y: this.AvailabilityPerMonth[7]},
                                {x: new Date(this.year, 8), y: this.AvailabilityPerMonth[8]},
                                {x: new Date(this.year, 9), y: this.AvailabilityPerMonth[9]},
                                {x: new Date(this.year, 10), y: this.AvailabilityPerMonth[10]},
                                {x: new Date(this.year, 11), y: this.AvailabilityPerMonth[11]}
                            ]
                        },
                        {
                            type: "line",
                            name: "Quality",
                            showInLegend: true,
                            yValueFormatString: "#,##0",
                            dataPoints: [
                                {x: new Date(this.year, 0), y: this.QualityPerMonth[0]},
                                {x: new Date(this.year, 1), y: this.QualityPerMonth[1]},
                                {x: new Date(this.year, 2), y: this.QualityPerMonth[2]},
                                {x: new Date(this.year, 3), y: this.QualityPerMonth[3]},
                                {x: new Date(this.year, 4), y: this.QualityPerMonth[4]},
                                {x: new Date(this.year, 5), y: this.QualityPerMonth[5]},
                                {x: new Date(this.year, 6), y: this.QualityPerMonth[6]},
                                {x: new Date(this.year, 7), y: this.QualityPerMonth[7]},
                                {x: new Date(this.year, 8), y: this.QualityPerMonth[8]},
                                {x: new Date(this.year, 9), y: this.QualityPerMonth[9]},
                                {x: new Date(this.year, 10), y: this.QualityPerMonth[10]},
                                {x: new Date(this.year, 11), y: this.QualityPerMonth[11]}
                            ]
                        }]
                });
                chart.render();

            },


        },


        mounted() {
            if(sessionStorage.getItem("language") !== null){
                this.$i18n.locale = sessionStorage.getItem("language");
            }
            this.$store.dispatch('fetchSites');
            let chartJs = document.createElement('script');
            chartJs.setAttribute('src', 'https://canvasjs.com/assets/script/canvasjs.min.js');
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
