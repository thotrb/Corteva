<template>
    <div class="d-flex" id="component">

        <!--


                -->
        <div class="col">

            <div class="d-flex">

                <form>
                    <label class="" for="site">{{$t("site")}} : </label>
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

            <template v-if="show===1 && allEvents['PP'] !== null">


                <div class="row">

                    <div class="col">
                        <p>
                            {{$t("plantOperatingTime")}} :<!-- {{ allEvents['POInfo'][0].plantOperatingTime}}--> mn <br/>
                            {{$t("plannedProductionTime")}} : <!--{{ allEvents['POInfo'][0].plannedProductionTime}} -->mn <br/>
                            {{$t("loadFactor")}} :<!-- {{ allEvents['POInfo'][0].loadFactor}}--> % <br/>
                        </p>
                    </div>

                    <div class="col">

                        <p>
                            {{$t("volumePacked")}} : {{littersProduced}} L <br/>
                            {{$t("numberOfProductionOrder")}} : {{ allEvents['SITE'].length}} <br/>
                            {{$t("numberOfItemsProduced")}} : {{ qtyProduced }}  {{$t("bottles")}} <br/>

                        </p>

                    </div>
                </div>


                <div class="">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col"> {{$t("plannedDowntime")}}  ({{$t("prioritizeList")}})</th>
                            <th scope="col">{{$t("duration(Minutes)")}} </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row">1. {{$t("noProductionPlanned")}} (PP)</th>
                            <template v-if="allEvents['PP'][0].Duration === null">
                                <td>0 mn</td>
                            </template>
                            <template v-else>
                                <td>{{allEvents['PP'][0].Duration}} mn</td>
                            </template>

                        </tr>
                        <tr>
                            <th scope="row">2. {{$t("plannedMaintenanceActivites")}} (PM)</th>
                            <template v-if="allEvents['PM'][0].Duration === null">
                                <td>0 mn</td>
                            </template>
                            <template v-else>
                                <td>{{allEvents['PM'][0].Duration}} mn</td>
                            </template>
                        </tr>
                        <tr>
                            <th scope="row">3. {{$t("capitalProjectImplementation")}} (CP)</th>
                            <template v-if="allEvents['CP'][0].Duration === null">
                                <td>0 mn</td>
                            </template>
                            <template v-else>
                                <td>{{allEvents['CP'][0].Duration}} mn</td>
                            </template>
                        </tr>
                        <tr>
                            <th scope="row">4. {{$t("breaksMeetingShiftChange")}} (BM)</th>
                            <template v-if="allEvents['BM'][0].Duration === null">
                                <td>0 mn</td>
                            </template>
                            <template v-else>
                                <td>{{allEvents['BM'][0].Duration}} mn</td>
                            </template>
                        </tr>

                        </tbody>
                    </table>
                </div>


                <div class="">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">{{$t("unplannedDowntime")}}</th>
                            <th scope="col">{{$t("duration(Minutes)")}}</th>
                            <th scope="col">{{$t("numberOfEvents")}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row">1. {{$t("cleaningInPlace")}} (CIP)</th>
                            <template v-if="allEvents['CIP'][0].Duration === null">
                                <td>0 mn</td>
                                <td>0</td>

                            </template>
                            <template v-else>
                                <td>{{allEvents['CIP'][0].Duration}} mn</td>
                                <td>{{allEvents['CIP'][0].nbEvents}}</td>
                            </template>


                        </tr>
                        <tr>
                            <th scope="row">2. {{$t("changeoOver")}} (COV)</th>
                            <template v-if="allEvents['COV'][0].Duration === null">
                                <td>0 mn</td>
                                <td>0</td>

                            </template>
                            <template v-else>
                                <td>{{allEvents['COV'][0].Duration}} mn</td>
                                <td>{{allEvents['COV'][0].nbEvents}}</td>
                            </template>


                        </tr>
                        <tr>
                            <th scope="row">3. {{$t("batchNumberChange")}} (BNC)</th>
                            <template v-if="allEvents['BNC'][0].Duration === null">
                                <td>0 mn</td>
                                <td>0</td>

                            </template>
                            <template v-else>
                                <td>{{allEvents['BNC'][0].Duration}} mn</td>
                                <td>{{allEvents['BNC'][0].nbEvents}}</td>
                            </template>


                        </tr>
                        <tr>
                            <th scope="row">4. {{$t("unplannedExternalEvents")}} (UEE)</th>
                            <template v-if="allEvents['UEE'][0].Duration === null">
                                <td>0 mn</td>
                                <td>0</td>

                            </template>
                            <template v-else>
                                <td>{{allEvents['UEE'][0].Duration}} mn</td>
                                <td>{{allEvents['UEE'][0].nbEvents}}</td>
                            </template>


                        </tr>
                        <tr>
                            <th scope="row">5. {{$t("unplannedShutdownOfMachine")}} (USM)</th>
                            <template v-if="allEvents['USM'][0].Duration === null">
                                <td>0 mn</td>
                                <td>0</td>
                            </template>
                            <template v-else>
                                <td>{{allEvents['USM'][0].Duration}} mn</td>
                                <td>{{allEvents['USM'][0].nbEvents}}</td>
                            </template>


                        </tr>
                        <tr>
                            <th scope="row">6. {{$t("fillerUnplannedShutdown")}} (UEE)</th>
                            <template v-if="allEvents['FUS'][0].Duration === null">
                                <td>0 mn</td>
                                <td>0</td>

                            </template>
                            <template v-else>
                                <td>{{allEvents['FUS'][0].Duration}} mn</td>
                                <td>{{allEvents['FUS'][0].nbEvents}}</td>
                            </template>

                        </tr>

                        </tbody>
                    </table>
                </div>

                <br/>

                <div class="">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">{{$t("speedl    osses")}}</th>
                            <th scope="col">{{$t("duration(Minutes)")}}</th>
                            <th scope="col">{{$t("numberOfEvents")}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row">1. {{$t("reducedRateAtFiller")}} (RRF)</th>
                            <template v-if="allEvents['RRF'][0].Duration === null">
                                <td>0 mn</td>
                                <td>0</td>

                            </template>
                            <template v-else>
                                <td>{{allEvents['RRF'][0].Duration}} mn</td>
                                <td>{{allEvents['RRF'][0].nbEvents}}</td>
                            </template>


                        </tr>
                        <tr>
                            <th scope="row">2. {{$t("reducedRateAtAnOtherMachine")}} (RRM)</th>
                            <template v-if="allEvents['RRM'][0].Duration === null">
                                <td>0 mn</td>
                                <td>0</td>

                            </template>
                            <template v-else>
                                <td>{{allEvents['RRM'][0].Duration}} mn</td>
                                <td>{{allEvents['RRM'][0].nbEvents}}</td>
                            </template>


                        </tr>
                        <tr>
                            <th scope="row">3. {{$t("fillerOwnStoppage")}} (FOS)</th>
                            <template v-if="allEvents['FOS'][0].Duration === null">
                                <td>0 mn</td>
                                <td>0</td>
                            </template>
                            <template v-else>
                                <td>{{allEvents['FOS'][0].Duration}} mn</td>
                                <td>{{allEvents['FOS'][0].nbEvents}}</td>
                            </template>

                        </tr>
                        <tr>
                            <th scope="row">4. {{$t("fillerOwnStoppageByAnOtherMachine")}}(FSM)</th>
                            <template v-if="allEvents['FSM'][0].Duration === null">
                                <td>0 mn</td>
                                <td>0</td>

                            </template>
                            <template v-else>
                                <td>{{allEvents['FSM'][0].Duration}} mn</td>
                                <td>{{allEvents['FSM'][0].nbEvents}}</td>
                            </template>

                        </tr>
                        </tbody>
                    </table>
                </div>
            </template>


        </div>

        <div class="col">
            <h1>
                {{$t("productionShift")}}

            </h1>
            <br/>
            <div class="d-flex">
                <label class="" for="startingPO">{{$t("from")}}</label>
                <input type="date" id="startingPO" class=" " required v-model="beginningDate">


                <label class="" for="endingPO">{{$t("to")}}</label>
                <input type="date" id="endingPO" class=""
                       required v-model="endingDate">
            </div>

            <br/>

            <template v-if="show===1">

                <div id="squareZone">

                    <div class="row">
                        <div class="col">
                            <canvas id="can" width="200" height="200"/>
                            <h5>{{$t("formVolumeSplit")}}</h5>
                        </div>

                        <div class="col">
                            <canvas id="can2" width="200" height="200"/>
                            <h5>{{$t("packSizeSplit")}}</h5>

                        </div>

                    </div>

                    <br/>


                    <h5>{{$t("plantOperatingTimeOverview")}} </h5>
                    <div class="row rect" id="rect1">
                        <p class="blueBack">
                            {{$t("plannedProductionTime")}} (PPT)
                        </p>
                        <p class="greenBack">
                            {{$t("plannedDowntime")}} (PD)
                        </p>
                        <p>

                            {{(plannedDowntimes / plannedProductionTime * 100).toFixed(2)}}%
                        </p>
                    </div>


                    <div class="row rect" id="rect2">
                        <p class="blueBack">
                            {{$t("operatingTime")}} (OT)
                        </p>
                        <p class="redBack">
                            {{$t("unplannedDowntime")}} (UD)
                        </p>
                        <p>

                            {{(unplannedDowntimes / operatingTime * 100).toFixed(2)}}%
                        </p>
                    </div>

                    <div class="row rect" id="rect3">
                        <p class="blueBack">
                            {{$t("netOperatingTime")}} (NOT)
                        </p>
                        <p class="redBack">
                           {{$t("speedLosses")}} (SL)
                        </p>
                        <p>
                            {{(performance* 100).toFixed(2)}}%
                        </p>
                    </div>

                    <div class="row rect" id="rect4">
                        <p class="blueBack">
                            {{$t("valuableOperatingTime")}} (VOT)
                        </p>
                        <p class="redBack">
                            {{$t("qualityLosses")}} (QL)
                        </p>
                        <p>
                            {{(quality* 100).toFixed(2)}}%
                        </p>
                    </div>


                </div>
            </template>

            <br/>


            <div class="d-flex zoneDessin">

                <div class="container">
                    <canvas id="Availability">
                    </canvas>
                    <template v-if="show===1">
                        <h5>{{$t("availability")}}</h5>
                    </template>
                </div>

                <div class="container">
                    <canvas id="Performance">
                    </canvas>
                    <template v-if="show===1">
                        <h5>{{$t("performance")}}</h5>
                    </template>
                </div>


            </div>
            <div class="d-flex zoneDessin">
                <br/>

                <div class="container">
                    <canvas id="Quality">
                    </canvas>
                    <template v-if="show===1">
                        <h5>{{$t("quality")}}</h5>
                    </template>
                </div>

                <div class="container">
                    <canvas id="OLE">
                    </canvas>
                    <template v-if="show===1">
                        <h5>OLE</h5>
                    </template>
                </div>


            </div>

            <br/>

        </div>


    </div>
</template>

<script>

    import {mapGetters} from "vuex";

    export default {
        name: "downtimesReport",


        data() {
            return {
                lo : this.$t("load"),
                site: '',
                productionline: '',
                product: '',
                formulationType: '',
                reporting: '',
                tool: '',
                beginningDate: '',
                endingDate: '',
                username: sessionStorage.getItem("username"),
                index: -1,
                show: 0,
                qtyProduced: 0,


                productsName: [],
                quantityArray: [],
                formatArray: [],
                quantityPerArray: [],
                formulationArray: [],

                littersProduced: 0,

                plannedDowntimes: 0,
                unplannedDowntimes: 0,
                plannedProductionTime: 0,
                operatingTime: 0,
                netOperatingTime: 0,
                performance: 0,
                availability: 0,
                quality: 0,
                OLE: 0,
            }
        },

        methods: {
            load: async function () {
                var index = 0;
                for (let i = 0; i < this.sites[1].length; i++) {

                    if (this.sites[1][i].productionline_name === this.productionline) {
                        index = this.sites[1][i].id;
                    }
                }


                if (this.productionline !== '' && this.beginningDate !== '' && this.endingDate !== '') {
                    var tab = [];
                    tab.push(this.site);
                    tab.push(this.productionline);
                    tab.push(this.beginningDate);
                    tab.push(this.endingDate);

                    this.$store.dispatch('fetchAllEvents', tab);
                    await this.resolveAfter15Second();
                    this.show = 1;
                    await this.resolveAfter15Second();

                    this.loadArray();

                    this.loadProductionTime();


                    this.circle();
                    this.pieCharts();


                }

                console.log(this.productionline);
                console.log(this.beginningDate);
                console.log(this.endingDate);

            },

            loadArray: function () {
                for (let i = 0; i < this.allEvents['SITE'].length; i++) {
                    this.quantityArray[i] = 0;
                    this.quantityPerArray[i] = 0;
                }

                for (let i = 0; i < this.allEvents['SITE'].length; i++) {

                    let nbBottles = this.allEvents['SITE'][i].qtyProduced * this.allEvents['SITE'][i].bottlesPerCase;
                    this.qtyProduced += nbBottles * 1;

                    if(!this.formulationArray.includes(this.allEvents['SITE'][i].formulationType)){
                        this.formulationArray.push(this.allEvents['SITE'][i].formulationType);
                    }

                    let indexFormulation = this.formulationArray.indexOf(this.allEvents['SITE'][i].formulationType);

                    this.quantityPerArray[indexFormulation] += this.allEvents['SITE'][i].qtyProduced * this.allEvents['SITE'][i].bottlesPerCase
                        * this.allEvents['SITE'][i].size;


                    if(!this.formatArray.includes(this.allEvents['SITE'][i].size)){
                        this.formatArray.push(this.allEvents['SITE'][i].size);
                    }

                    let indexSize = this.formatArray.indexOf(this.allEvents['SITE'][i].size);
                    this.quantityArray[indexSize] += this.allEvents['SITE'][i].qtyProduced * this.allEvents['SITE'][i].bottlesPerCase
                        * this.allEvents['SITE'][i].size;



                    if (!this.productsName.includes(this.allEvents['SITE'][i].product)) {
                        this.productsName.push(this.allEvents['SITE'][i].product);
                    }

                    let index = this.productsName.indexOf(this.allEvents['SITE'][i].product);

                    this.quantityArray[index] += this.allEvents['SITE'][i].qtyProduced * this.allEvents['SITE'][i].bottlesPerCase
                        * this.allEvents['SITE'][i].size;

                    this.littersProduced += this.allEvents['SITE'][i].qtyProduced * this.allEvents['SITE'][i].bottlesPerCase
                        * this.allEvents['SITE'][i].size;
                    this.formatArray[index] = this.allEvents['SITE'][i].size;
                }

                console.log(this.productsName);
                console.log(this.quantityArray);
                console.log(this.formatArray);
                console.log(this.quantityPerArray);
                console.log(this.formulationArray);


            },

            resolveAfter15Second: function () {
                return new Promise(resolve => {
                    setTimeout(() => {
                        resolve('resolved');
                    }, 1500);
                });
            },

            loadProductionTime: function () {

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

                this.availability = this.operatingTime / this.plannedProductionTime;
                this.performance = this.netOperatingTime / this.operatingTime;

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
                    this.quality = (sommeQtyProduced) / (sommeQtyProduced + sommeRejection + s);

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

                console.log('Planned Downtime : ' + sommePlannedEvents);
                console.log('Unplanned Downtime : ' + sommeUnplannedEvents);
                console.log('Planned Production Time : ' + this.plannedProductionTime);
                console.log('Operating Time : ' + this.operatingTime);

                console.log('NOT : ' + sommePlannedEvents);
                console.log('Availability : ' + this.availability);
                console.log('Performance : ' + this.performance);
                console.log('Operating Time : ' + this.operatingTime);


            }
            ,

            pieCharts: function () {

                let obj;
                var data = [];
                for(let j= 0; j<this.formulationArray.length; j++){
                    obj = {
                        name: this.formulationArray[j],
                        nbr: this.quantityPerArray[j]
                    };
                    data.push(obj);
                }
                console.log('DATA');

                console.log(data);


                const randomHexColorCode = () => {
                    return "#" + Math.random().toString(16).slice(2, 8)
                };

                var canvas = document.getElementById("can");
                var ctx = canvas.getContext("2d");
                canvas.width = 400;
                canvas.height = 300;
                let total = data.reduce( (ttl, house) => {
                    return ttl + house.nbr
                }, 0);
                let startAngle = 0;
                let radius = 100;
                let cx = canvas.width/2;
                let cy = canvas.height/2;

                for(let j= 0; j<data.length; j++){

                    let item = data[j];
                    //set the styles before beginPath
                    ctx.fillStyle = randomHexColorCode();
                    ctx.lineWidth = 1;
                    ctx.strokeStyle = '#333';
                    ctx.beginPath();
                    //console.log(total, house.troops, house.troops/total);
                    // draw the pie wedges
                    let endAngle = ((item.nbr / total) * Math.PI * 2) + startAngle;
                    ctx.moveTo(cx, cy);
                    ctx.arc(cx, cy, radius, startAngle, endAngle, false);
                    ctx.lineTo(cx, cy);
                    ctx.fill();
                    ctx.stroke();
                    ctx.closePath();

                    // add the labels
                    ctx.beginPath();
                    ctx.font = '20px Helvetica, Calibri';
                    ctx.textAlign = 'center';
                    ctx.fillStyle = 'rebeccapurple';
                    // midpoint between the two angles
                    // 1.5 * radius is the length of the Hypotenuse
                    let theta = (startAngle + endAngle) / 2;
                    let deltaY = Math.sin(theta) * 1.5 * radius;
                    let deltaX = Math.cos(theta) * 1.5 * radius;
                    /***
                     SOH  - sin(angle) = opposite / hypotenuse
                     = opposite / 1px
                     CAH  - cos(angle) = adjacent / hypotenuse
                     = adjacent / 1px
                     TOA

                     ***/
                    var txt = item.name + '\n';
                    ctx.fillText(txt, deltaX+cx, deltaY+cy);
                    ctx.closePath();

                    startAngle = endAngle;
                }

                data = [];
                for(let j= 0; j<this.formatArray.length; j++){
                    obj = {
                        name: this.formatArray[j],
                        nbr: this.quantityArray[j],
                    };
                    data.push(obj);
                }
                console.log('DATA');

                console.log(data);



                canvas = document.getElementById("can2");
                ctx = canvas.getContext("2d");
                canvas.width = 400;
                canvas.height = 300;
                total = data.reduce( (ttl, house) => {
                    return ttl + house.nbr
                }, 0);
                startAngle = 0;
                radius = 100;
                cx = canvas.width/2;
                cy = canvas.height/2;

                for(let j= 0; j<data.length; j++){

                    let item = data[j];
                    //set the styles before beginPath
                    ctx.fillStyle = randomHexColorCode();
                    ctx.lineWidth = 1;
                    ctx.strokeStyle = '#333';
                    ctx.beginPath();
                    //console.log(total, house.troops, house.troops/total);
                    // draw the pie wedges
                    let endAngle = ((item.nbr / total) * Math.PI * 2) + startAngle;
                    ctx.moveTo(cx, cy);
                    ctx.arc(cx, cy, radius, startAngle, endAngle, false);
                    ctx.lineTo(cx, cy);
                    ctx.fill();
                    ctx.stroke();
                    ctx.closePath();

                    // add the labels
                    ctx.beginPath();
                    ctx.font = '20px Helvetica, Calibri';
                    ctx.textAlign = 'center';
                    ctx.fillStyle = 'rebeccapurple';
                    // midpoint between the two angles
                    // 1.5 * radius is the length of the Hypotenuse
                    let theta = (startAngle + endAngle) / 2;
                    let deltaY = Math.sin(theta) * 1.5 * radius;
                    let deltaX = Math.cos(theta) * 1.5 * radius;
                    /***
                     SOH  - sin(angle) = opposite / hypotenuse
                     = opposite / 1px
                     CAH  - cos(angle) = adjacent / hypotenuse
                     = adjacent / 1px
                     TOA

                     ***/
                    txt = item.name + '\n';
                    txt = txt + ' L';
                    ctx.fillText(txt, deltaX+cx, deltaY+cy);
                    ctx.closePath();

                    startAngle = endAngle;
                }

                /**
                 var data = [];
                 for(let i=0; i<this.productsName.length; i++){
                    let item = {
                      y:  this.quantityArray[i],
                        label: this.productsName[i]
                    };
                    data.push(item);
                }



                 var chart = new CanvasJS.Chart("can", {
                    animationEnabled: true,
                    title: {
                        text: "Desktop Search Engine Market Share - 2016"
                    },
                    data: [{
                        type: "pie",
                        startAngle: 240,
                        yValueFormatString: "##0.00\"%\"",
                        indexLabel: "{label} {y}",
                        dataPoints: data,
                    }]
                });

                 chart.render();
                 **/

/**
                data = [];
                for (let i = 0; i < this.productsName.length; i++) {
                    data.push(this.quantityArray[i]);
                }
                var canvas = document.getElementById("can");
                var ctx = canvas.getContext("2d");
                var lastend = 0;


                var myTotal = 0; // Automatically calculated so don't touch
                var myColor = ['red', 'green', 'blue', 'yellow', 'gray', 'black', 'pink', 'purple']; // Colors of each slice

                for (var e = 0; e < data.length; e++) {
                    myTotal += data[e];
                }

                for (var i = 0; i < data.length; i++) {
                    ctx.fillStyle = myColor[i];
                    ctx.beginPath();
                    ctx.moveTo(canvas.width / 2, canvas.height / 2);
                    // Arc Parameters: x, y, radius, startingAngle (radians), endingAngle (radians), antiClockwise (boolean)
                    ctx.arc(canvas.width / 2, canvas.height / 2, canvas.height / 2, lastend, lastend + (Math.PI * 2 * (data[i] / myTotal)), false);
                    ctx.lineTo(canvas.width / 2, canvas.height / 2);
                    ctx.fill();
                    lastend += Math.PI * 2 * (data[i] / myTotal);
                }


                canvas = document.getElementById("can2");
                ctx = canvas.getContext("2d");
                lastend = 0;
                data = [60, 60, 20, 50]; // If you add more data values make sure you add more colors
                myTotal = 0; // Automatically calculated so don't touch
                myColor = ['pink', 'green', 'blue', 'purple']; // Colors of each slice

                for (e = 0; e < data.length; e++) {
                    myTotal += data[e];
                }

                for (i = 0; i < data.length; i++) {
                    ctx.fillStyle = myColor[i];
                    ctx.beginPath();
                    ctx.moveTo(canvas.width / 2, canvas.height / 2);
                    // Arc Parameters: x, y, radius, startingAngle (radians), endingAngle (radians), antiClockwise (boolean)
                    ctx.arc(canvas.width / 2, canvas.height / 2, canvas.height / 2, lastend, lastend + (Math.PI * 2 * (data[i] / myTotal)), false);
                    ctx.lineTo(canvas.width / 2, canvas.height / 2);
                    ctx.fill();
                    lastend += Math.PI * 2 * (data[i] / myTotal);
                }
**/

            }
            ,

            resolveAfter05Second: function () {
                return new Promise(resolve => {
                    setTimeout(() => {
                        resolve('resolved');
                    }, 500);
                });
            }
            ,

            circle: function () {

                var canvas = document.getElementById("Availability");
                var context = canvas.getContext("2d");
                context.lineWidth = "2";
                context.fillStyle = "#FF0000";
                if (this.availability >= 0.70 && this.availability < 0.95) {
                    context.fillStyle = "#FF8700";
                } else if (this.availability >= 0.95) {
                    context.fillStyle = "#71FA23";
                }
                context.arc(80, 80, 62, 0, 2 * Math.PI);
                context.stroke();
                context.fill();
                context.fillStyle = "#FFF";
                context.font = '20px serif';
                let ava = this.availability * 100;
                context.fillText(ava.toFixed(2), 70, 90);


                canvas = document.getElementById("Performance");
                context = canvas.getContext("2d");
                context.lineWidth = "2";
                context.fillStyle = "#FF0000";
                if (this.performance >= 0.70 && this.performance < 0.95) {
                    context.fillStyle = "#FF8700";
                } else if (this.performance >= 0.95) {
                    context.fillStyle = "#71FA23";
                }
                context.arc(80, 80, 62, 0, 2 * Math.PI);
                context.stroke();
                context.fill();
                context.fillStyle = "#FFF";
                context.font = '20px serif';
                let perf = this.performance * 100;
                context.fillText(perf.toFixed(2), 70, 90);

                canvas = document.getElementById("Quality");
                context = canvas.getContext("2d");
                context.lineWidth = "2";
                context.fillStyle = "#FF0000";
                if (this.quality >= 0.7 && this.quality < 0.95) {
                    context.fillStyle = "#FF8700";
                } else if (this.quality >= 0.95) {
                    context.fillStyle = "#71FA23";
                }
                context.arc(80, 80, 62, 0, 2 * Math.PI);
                context.stroke();
                context.fill();
                context.fillStyle = "#FFF";
                context.font = '20px serif';
                let qua = this.quality * 100;
                context.fillText(qua.toFixed(2), 70, 90);

                canvas = document.getElementById("OLE");
                context = canvas.getContext("2d");
                context.lineWidth = "2";
                context.fillStyle = "#FF0000";
                if (this.OLE >= 0.70 && this.OLE < 0.95) {
                    context.fillStyle = "#FF8700";
                } else if (this.OLE >= 95) {
                    context.fillStyle = "#71FA23";
                }
                context.arc(80, 80, 62, 0, 2 * Math.PI);
                context.stroke();
                context.fill();
                context.fillStyle = "#FFF";
                context.font = '20px serif';
                let o = this.OLE * 100;
                context.fillText(o.toFixed(2), 70, 90);


            }
            ,

        }
        ,


        mounted() {
            if(sessionStorage.getItem("language") !== null){
                this.$i18n.locale = sessionStorage.getItem("language");
            }
            this.$store.dispatch('fetchSites');
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1; //January is 0!
            var yyyy = today.getFullYear();

            if (dd < 10) {
                dd = '0' + dd;
            }

            if (mm < 10) {
                mm = '0' + mm;
            }

            today = yyyy + '-' + mm + '-' + dd;
            document.getElementById("startingPO").setAttribute("max", today);
            document.getElementById("endingPO").setAttribute("max", today);


            let externalScript = document.createElement('script');
            externalScript.setAttribute('src', 'https://canvasjs.com/assets/script/canvasjs.min.js');
            document.head.appendChild(externalScript)
        }
        ,

        computed: {
            ...
                mapGetters([
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


    .rcorners2 {
        border: 2px solid lightgray;
        padding: 20px;
    }

    .blueBack {
        font-size: 20px;
        color: white;
        background: #0056ff;
        padding: 10px;
        width: 60%;
    }

    .greenBack {
        font-size: 20px;
        color: white;
        background: #71FA23;
        padding: 10px;
        width: 30%;
    }

    .redBack {
        font-size: 20px;
        color: white;
        background: red;
        padding: 10px;
        width: 30%;
    }

    #rect1 {
        margin-right: 10px;
    }

    #rect2 {
        margin-right: 40px;
    }

    #rect3 {
        margin-right: 70px;
    }

    #rect4 {
        margin-right: 100px;
    }

    .rect {
        margin-bottom: -40px;
    }


</style>
