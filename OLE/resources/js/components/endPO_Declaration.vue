<template>


    <div>


        <template v-if="displayNumber === 0">
            <div align="center">
                <div align="center" class="col productionName rcorners2">
                    {{$t("performance")}}
                </div>

                <form>
                    <div class="form-group row blockInput">
                        <label class="col-sm-2 col-form-label rcorners1" for="startingPO">{{$t("POStartTime")}}</label>
                        <div class="col-sm-10">
                            <!--<input type="number" id="startingPO" class="form-control-plaintext rcorners2"
                            v-bind:value="startPO" disabled>-->

                            <input type="time" id="startingPO" class="form-control-plaintext rcorners2"
                                   min="00:00" max="24:00" required v-model="startPO">
                        </div>

                    </div>

                    <div class="form-group row blockInput">
                        <label for="endingPO" class="col-sm-2 rcorners1">{{$t("POEndTime")}}</label>
                        <div class="col-sm-10">
                            <!--<input type="number" id="endingPO" class="form-control-plaintext rcorners2"
                            v-bind:value="endPO">-->

                            <input type="time" id="endingPO" class="form-control-plaintext rcorners2"
                                   min="00:00" max="24:00" required v-model="endPO">
                        </div>
                    </div>

                    <div class="form-group row blockInput">
                        <label for="finalQuantityProduced" class="col-sm-2 rcorners1">{{$t("finalQuantityProduced(Cases)")}}<br> </label>
                        <div class="col-sm-10">
                            <input type="number" id="finalQuantityProduced" class="form-control-plaintext rcorners2"
                                   v-model="finalQuantityProduced">
                        </div>
                    </div>

                    <button class="btn btn-primary d-flex align-items-center btn-info" type="button"
                            @click.prevent="validateCalculation()">
                        {{$t("validate")}}
                    </button>


                </form>
                <br/>
                <br/>

                <template v-if="valider === 1">
                    <div align="center" class="rcorners2">

                        <div align="left">

                            {{$t("totalPOProductionTime")}} (min): {{totalProductionTime}}
                            <br/>
                            {{$t("totalPOOperatingTime")}} (min): {{totalOperatingTime}}
                            <br/>
                            {{$t("difference")}} (min): {{totalProductionTime - totalOperatingTime}}
                            <br/>

                            {{$t("totalPOPerformance")}} (%) : {{performance * 100}}
                        </div>

                        <br/>

                        <template v-if="speedLoss.length <= 0">
                            <h4>{{$t("noPerformanceRegistered")}}</h4>
                        </template>
                        <template v-else>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">{{$t("reason")}}</th>
                                    <th scope="col">{{$t("comments")}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-for="event in speedLoss">
                                    <tr>
                                        <th scope="row">{{event.reason}}</th>
                                        <td>{{event.comment}}</td>
                                    </tr>
                                </template>

                                </tbody>

                            </table>
                        </template>
                    </div>

                </template>


                <div align="right">
                    <button class="btn btn-primary border-danger align-items-right btn-danger" type="button"
                            @click.prevent="backPage()">
                        {{$t("cancel")}}
                    </button>
                </div>

                <template v-if="valider===1">

                    <div class="d-flex flex-row justify-content-between align-items-center bg-white">
                        <button class="btn btn-primary d-flex align-items-center btn-danger" type="button"
                                @click.prevent="backPage()">
                            {{$t("back")}}
                        </button>

                        <button class="btn btn-primary d-flex align-items-center btn-warning" id="addReasonButton"
                                type="button"
                                @click.prevent="addSpeedLoss()">
                            {{$t("speedLossJustification")}}
                        </button>


                        <button class="btn btn-primary border-success align-items-center btn-success" type="button"
                                @click.prevent="validateInformations()">
                            OK
                        </button>
                    </div>
                </template>


            </div>
        </template>

        <template v-if="displayNumber ===1">
            <div>
                <div align="center" class="productionName rcorners2">
                    {{$t("speedLoss")}} {{nbSpeedLosses + 1}}
                </div>

                <br/>


                <div class="row" align="center" style="margin-bottom: 30px;">

                    <div class="col-sm-4">
                        <label for="answer1" class="checkbox labelsAnswer">
                            <input type="radio"
                                   id="answer1" name="reponseQuestion"
                                   value="Reduced Rate At Filler" class="response">
                            <span>{{$t("reducedRateAtFiller")}}</span>
                        </label>

                    </div>

                    <div class="col-sm-4">
                        <label for="answer2" class="checkbox labelsAnswer">
                            <input type="radio"
                                   id="answer2" name="reponseQuestion"
                                   value="Reduce Rate At An Other Machine" class="response">
                            <span>{{$t("reduceRateAtAnOtherMachine")}}</span>
                        </label>


                    </div>

                    <div class="col-sm-4">

                        <label for="answer3" class="checkbox labelsAnswer">
                            <input type="radio"
                                   id="answer3" name="reponseQuestion"
                                   value="Filler Own Stoppage" class="response">
                            <span>{{$t("fillerOwnStoppage")}}</span>
                        </label>


                    </div>

                    <div class="col-sm-4">
                        <label for="answer4" class="checkbox labelsAnswer">
                            <input type="radio"
                                   id="answer4" name="reponseQuestion"
                                   value="Filler Own Stoppage By An Other Machine" class="response">
                            <span>{{$t("fillerOwnStoppageByAnOtherMachine")}}</span>
                        </label>

                    </div>

                </div>

                <br>

                <form>
                    <div class="form-group row">
                        <label for="comments" class="col-sm-2 rcorners1">{{$t("comments")}}</label>
                        <div class="col-sm-10">
                            <textarea id="comments" class="form-control-plaintext rcorners2"></textarea>
                        </div>
                    </div>
                </form>

                <br/>

                <div class="d-flex flex-row justify-content-between align-items-center bg-white">
                    <button class="btn btn-primary d-flex align-items-center btn-danger" type="button"
                            @click.prevent="resetPage()">
                        {{$t("back")}}
                    </button>


                    <button class="btn btn-primary border-success align-items-center btn-success" type="button"
                            @click.prevent="confirmSpeedloss()">
                        OK
                    </button>
                </div>
            </div>


        </template>

        <template v-if="displayNumber===2">

            <div align="center">
                <div align="center" class="col productionName rcorners2">
                    {{$t("quality")}}
                </div>


                <table class="table table-hover" align="center">
                    <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">{{$t("filler")}}</th>
                        <th scope="col">{{$t("caper")}}</th>
                        <th scope="col">{{$t("labeller")}}</th>
                        <th scope="col">{{$t("boxWeigher")}}</th>

                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">{{$t("counter")}}</th>
                        <td>
                            <input type="number" id="FillerCounter" class="rcorners2" v-model="FillerCounter">
                        </td>
                        <td>
                            <input type="number" id="CaperCounter" class="rcorners2" v-model="CaperCounter">
                        </td>
                        <td>
                            <input type="number" id="EtiqueteuseCounter" class="rcorners2"
                                   v-model="EtiqueteuseCounter">
                        </td>
                        <td>
                            <input type="number" id="WieghtBoxCounter" class="rcorners2" v-model="WieghtBoxCounter">
                        </td>

                    </tr>
                    <tr>
                        <th scope="row">{{$t("rejection")}}</th>
                        <td>
                            <input type="number" id="FillerRejection" class="rcorners2" v-model="FillerRejection">
                        </td>
                        <td>
                            <input type="number" id="CaperRejection" class="rcorners2" v-model="CaperRejection">
                        </td>
                        <td>
                            <input type="number" id="EtiqueteuseRejection" class="rcorners2"
                                   v-model="EtiqueteuseRejection">
                        </td>
                        <td>
                            <input type="number" id="WieghtBoxRejection" class="rcorners2"
                                   v-model="WieghtBoxRejection">
                        </td>

                    </tr>

                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-row justify-content-between align-items-center bg-white">
                <button class="btn btn-primary d-flex align-items-center btn-danger" type="button"
                        @click.prevent="resetPage()">
                    {{$t("back")}}
                </button>


                <button class="btn btn-primary border-success align-items-center btn-success" type="button"
                        @click.prevent="displayQualityIndicators()">
                    OK
                </button>
            </div>
            <!--<quality-declaration></quality-declaration>-->

        </template>

        <template v-if="displayNumber===3">

            <h2 align="center">{{$t("summary")}}</h2>

            <br>

            <span>

                {{$t("totalPOProductionTime")}} (min): {{totalProductionTime}}
                <br/>
                {{$t("totalPOOperatingTime")}} (min): {{totalOperatingTime}}
                <br/>
                {{$t("difference")}}(min): {{totalProductionTime - totalOperatingTime}}
                <br/>
            </span>

            <br/>


            <h5>{{$t("speedlosses")}}</h5>

            <template v-if="speedLoss.length <= 0">
                <h5>{{$t("noPerformanceRegistered")}}</h5>
            </template>


            <template v-else>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">{{$t("reason")}}</th>
                        <th scope="col">{{$t("comments")}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="event in speedLoss">
                        <tr>
                            <th scope="row">{{event.reason}}</th>
                            <td>{{event.comment}}</td>
                        </tr>
                    </template>

                    </tbody>

                </table>
            </template>


            <h5>{{$t("quality")}}</h5>

            <br/>


            <table class="table table-hover" align="center">
                <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col"> {{$t("filler")}}</th>
                    <th scope="col">{{$t("caper")}}</th>
                    <th scope="col">{{$t("labeller")}}</th>
                    <th scope="col">{{$t("boxWeigher")}}</th>

                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">{{$t("counter")}}</th>
                    <td>
                        {{FillerCounter}}
                    </td>
                    <td>
                        {{CaperCounter}}
                    </td>
                    <td>
                        {{EtiqueteuseCounter}}
                    </td>
                    <td>
                        {{WieghtBoxCounter}}
                    </td>

                </tr>
                <tr>
                    <th scope="row">{{$t("rejection")}}</th>
                    <td>
                        {{FillerRejection}}
                    </td>
                    <td>
                        {{CaperRejection}}
                    </td>
                    <td>
                        {{EtiqueteuseRejection}}
                    </td>
                    <td>
                        {{WieghtBoxRejection}}
                    </td>

                </tr>

                </tbody>
            </table>
            <br/>

            <h5>{{$t("indicators")}}</h5>

            <span>

                 {{$t("availability")}} (%) :  {{availability * 100}}
                <br/>
                 {{$t("performance")}} (%) :  {{performance * 100}}
                <br/>
                 {{$t("quality")}} (%) :  {{quality * 100}}
                <br/>
                 OLE (%) :  {{OLE * 100}}

            </span>

            <br/>

            <div class="d-flex flex-row justify-content-between align-items-center bg-white">
                <button class="btn btn-primary d-flex align-items-center btn-danger" type="button"
                        @click.prevent="validateInformations()">
                    {{$t("back")}}
                </button>


                <button class="btn btn-primary border-success align-items-center btn-success" type="button"
                        @click.prevent="saveEndPO()">
                    {{$t("validate")}}
                </button>
            </div>
        </template>

    </div>


</template>

<script>

    import {mapGetters} from "vuex";

    var today = new Date();
    import qualityDeclaration from './qualityDeclaration';
    import qualityIndicators from './qualityIndicators'


    export default {

        name: "endPO_Declaration",

        components: {
            qualityDeclaration,
            qualityIndicators,
        },


        data() {
            return {

                totalUnplannedDowtimes: sessionStorage.getItem("sommeUnplannedEvents"),
                totalPlannedDowtimes: sessionStorage.getItem("sommePlannedEvents"),


                indice: sessionStorage.getItem("indice"),

                startPO: 0,
                /**
                 case11: 0,
                 case12: 0,
                 case13: 0,
                 case14: 0,
                 case21: 0,
                 case22: 0,
                 case23: 0,
                 case24: 0,
                 **/
                FillerCounter: 0,
                CaperCounter: 0,
                EtiqueteuseCounter: 0,
                WieghtBoxCounter: 0,
                FillerRejection: 0,
                CaperRejection: 0,
                EtiqueteuseRejection: 0,
                WieghtBoxRejection: 0,
                availability: 0,
                performance: 0,

                finalQuantityProduced: 0,


                nbSpeedLosses: 0,

                url: sessionStorage.getItem("url"),

                displayNumber: 0,

                productionName: sessionStorage.getItem("productionName"),


                prodlines: sessionStorage.getItem("prodlines").split(','),

                parameters: [],

                username: sessionStorage.getItem("username"),


                speedLossEvent: {
                    OLE: '',

                    productionline: sessionStorage.getItem("productionName"),

                    reason: '',
                    //commentaire
                    comment: '',
                },

                totalOperatingTime: 0,

                totalNetOperatingTime: 0,

                totalProductionTime: 0,

                totalPOQuality: 0,

                endPO: 0,

                GMID: sessionStorage.getItem("GMIDCODE"),

                valider: 0,

                nbBottlesFilled: 0,

                totalDuration: 0,




            };
        },

        methods: {

            validateCalculation: async function () {
                console.log(document.getElementById('endingPO').value);


                var splitted1 = this.startPO.toString().split(':');
                var splitted2 = this.endPO.toString().split(':');

                var time1 = 0;
                var time2 = 0;
                if (splitted1[0] <= splitted2[0] ) {

                    time1 = this.startPO.toString().split(':')[0] * 60 + this.startPO.toString().split(':')[1] * 1;

                    time2 = this.endPO.toString().split(':')[0] * 60 + this.endPO.toString().split(':')[1] * 1;

                    this.totalOperatingTime = time2 - time1;

                    this.totalProductionTime = time2 - time1;

                    this.totalDuration = time2 - time1;


                } else {

                    time1 = 24 * 60 - (this.startPO.toString().split(':')[0] * 60 + this.startPO.toString().split(':')[1] * 1);

                    time2 = this.endPO.toString().split(':')[0] * 60 + this.endPO.toString().split(':')[1] * 1;

                    this.totalOperatingTime = time1 + time2;

                    this.totalProductionTime = time1 + time2;

                    this.totalDuration = time2 - time1;


                }


                this.totalProductionTime -= (this.totalPlannedDowtimes);
                this.totalOperatingTime = this.totalProductionTime - this.totalUnplannedDowtimes;
                this.availability = (this.totalOperatingTime / this.totalProductionTime).toFixed(2);


                //this.$store.dispatch('getNetOPTime', this.parameters);


                await this.$store.dispatch('getNetOPTime', this.GMID);
                await this.resolveAfter1Second();
                console.log(this.netOP);

                this.nbBottlesFilled = this.finalQuantityProduced * this.netOP.bottlesPerCase;

                this.totalNetOperatingTime = (this.finalQuantityProduced * this.netOP.bottlesPerCase) / this.netOP.idealRate;

                console.log('NET OP : ' + this.totalNetOperatingTime);

                this.performance = (this.totalNetOperatingTime / this.totalOperatingTime).toFixed(2);

                console.log('Perf : ' + this.performance);

                this.valider = 1;


            },

            backPage: function () {
                window.location.href = this.url + 'summary';
            },

            resetPage: function () {
                this.displayNumber = 0;
            },

            addSpeedLoss: function () {
                this.displayNumber = 1;
            },

            displayQualityIndicators: function () {
                /**
                 this.case11 = document.getElementById('FillerCounter').value;
                 this.case12 = document.getElementById('CaperCounter').value;
                 this.case13 = document.getElementById('EtiqueteuseCounter').value;
                 this.case14 = document.getElementById('WieghtBoxCounter').value;
                 this.case21 = document.getElementById('FillerRejection').value;
                 this.case22 = document.getElementById('CaperRejection').value;
                 this.case23 = document.getElementById('EtiqueteuseRejection').value;
                 this.case24 = document.getElementById('WieghtBoxRejection').value;
                 **/


                this.totalPOQuality = parseInt(this.finalQuantityProduced, 10) /
                    ((parseInt(this.finalQuantityProduced, 10) + this.FillerCounter + this.CaperCounter + this.EtiqueteuseCounter + this.WieghtBoxCounter)
                        + (this.FillerRejection - parseInt(this.finalQuantityProduced, 10)) + (this.CaperRejection - parseInt(this.finalQuantityProduced, 10))
                        + (this.EtiqueteuseRejection - parseInt(this.finalQuantityProduced, 10)) + (this.WieghtBoxRejection - parseInt(this.finalQuantityProduced, 10)));



                this.displayNumber = 3;

            },

            saveEndPO: async function () {
                this.endPO = sessionStorage.getItem("pos").split(',')[this.indice];

                /**
                var pos = sessionStorage.getItem("pos").split(',');
                pos.splice(this.indice, 1);

                if (sessionStorage.getItem("pos") === null) {
                    sessionStorage.pos = pos;
                } else {
                    sessionStorage.setItem("pos", pos);
                }**/


                this.endPO = sessionStorage.getItem("pos").split(',')[this.indice];


                var array  = [];
                array.push(this.endPO);
                array.push(this.availability);
                array.push(this.performance);
                array.push(this.quality);
                array.push(this.OLE);
                array.push(this.finalQuantityProduced);
                array.push(this.totalDuration);



                this.$store.dispatch('stop_PO', array);

                await this.resolveAfter1Second();

                var array2 = [];
                array2.push(this.endPO);
                array2.push(this.EtiqueteuseCounter);
                array2.push(this.WieghtBoxCounter);
                array2.push(this.CaperCounter);
                array2.push(this.FillerCounter);
                array2.push(this.EtiqueteuseRejection);
                array2.push(this.WieghtBoxRejection);
                array2.push(this.CaperRejection);
                array2.push(this.FillerRejection);


                this.$store.dispatch('store_Rejection', array2);

                await this.resolveAfter1Second();




                this.parameters.push(this.username);
                this.$store.dispatch('fetchUsers', this.parameters);


                await this.resolveAfter1Second();


                //this.loadTable();


                window.location.href = this.url + 'teamInfo';


            },

            loadTable: function () {

                var productionlinesTab = [];

                for (let i = 0; i < this.user[3].length; i++) {
                    if (this.user[3][i].worksiteID === this.user[0][0].worksiteID) {
                        productionlinesTab.push(this.user[3][i].productionline_name);
                    }
                }

                if (sessionStorage.getItem("prodlines") === null) {
                    sessionStorage.prodlines = productionlinesTab;
                } else {
                    sessionStorage.setItem("prodlines", productionlinesTab);
                }

            },

            resolveAfter1Second: function () {
                return new Promise(resolve => {
                    setTimeout(() => {
                        resolve('resolved');
                    }, 1000);
                });


            },

            isRejectionNull: function () {
                return this.FillerRejection === 0 && this.CaperRejection === 0
                    && this.EtiqueteuseRejection === 0 && this.WieghtBoxRejection === 0;
            },

            isCompteurNull: function () {
                return this.FillerCounter === 0 && this.CaperCounter === 0 && this.EtiqueteuseCounter === 0
                    && this.WieghtBoxCounter === 0;
            },


            validateInformations: async function () {

                this.valider = 0;

                if (this.displayNumber === 0) {


                    //this.availability = 100 * 8 * 60;

                    //recuperer la ligne de production
                    var productionLine = sessionStorage.getItem("productionName");


                    //recuperer le nom du shift
                    var shiftLetter = sessionStorage.typeTeam;

                    //recuperer le nom du site
                    var site = sessionStorage.site;


                    console.log('JE TESTE MA FONCTION');

                    if (this.isCompteurNull() && this.isRejectionNull()) {
                        this.quality = 1;
                    } else {

                        var N = this.nbBottlesFilled;
                        var summRejection = this.FillerRejection + this.CaperRejection +
                            this.EtiqueteuseRejection + this.WieghtBoxRejection;
                        var summCompteur = (this.FillerCounter - this.nbBottlesFilled) + (this.CaperCounter - this.nbBottlesFilled)
                            + (this.EtiqueteuseCounter - this.nbBottlesFilled) + (this.WieghtBoxCounter - this.nbBottlesFilled);

                        this.quality = (N / (N + summRejection + summCompteur)).toFixed(2);
                    }


                    this.OLE = (this.quality * this.availability * this.performance).toFixed(2);

                    if (sessionStorage.getItem("quality") === null) {
                        sessionStorage.quality = this.quality;
                    } else {
                        sessionStorage.setItem("quality", this.quality);
                    }

                    if (sessionStorage.getItem("performance") === null) {
                        sessionStorage.performance = this.performance;
                    } else {
                        sessionStorage.setItem("performance", this.performance);
                    }

                    if (sessionStorage.getItem("availability") === null) {
                        sessionStorage.availability = this.availability;
                    } else {
                        sessionStorage.setItem("availability", this.availability);
                    }

                    if (sessionStorage.getItem("OLE") === null) {
                        sessionStorage.OLE = this.OLE;
                    } else {
                        sessionStorage.setItem("OLE", this.OLE);
                    }


                }

                this.displayNumber = 2;

            },

            confirmSpeedloss: async function () {

                var responses = document.getElementsByClassName('response');
                var reason = '';
                for (let i = 0; i < responses.length; i++) {
                    if (responses[i].checked) {
                        reason = responses[i].value;
                    }
                    responses[i].setAttribute('disabled', 'disabled');
                }

                var comment = document.getElementById('comments').value;
                this.speedLossEvent.comment = comment;
                this.speedLossEvent.OLE = sessionStorage.getItem("pos").split(',')[this.indice];
                this.speedLossEvent.reason = reason;


                console.log(this.speedLossEvent);

                this.$store.dispatch('create_SpeedLoss', this.speedLossEvent);
                await this.resolveAfter1Second();

                window.location.reload();
            }


        }
        ,

        mounted() {


            var tab = [];
            tab.push(this.productionName);
            for (let i = 0; i < this.prodlines.length; i++) {
                if (this.productionName === this.prodlines[i]) {
                    this.indice = i;
                }
            }

            console.log('INDICE = ' + this.indice);

            tab.push(sessionStorage.getItem("pos").split(',')[this.indice]);


            console.log(sessionStorage.getItem("pos").split(',')[this.indice]);

            console.log(tab);

            this.$store.dispatch('fetchSpeedLosses', tab);


        }
        ,

        computed: {
            ...
                mapGetters([
                    'speedLoss',
                    'user',
                    'pos',
                    'events1',
                    'netOP',
                ])
        }


    }
</script>

<style scoped>

    .blockInput {
        margin-top: -20px;
    }

    .productionName {
        left: 0;
        top: 0;
        min-width: 150px;
        max-width: 250px;

        margin-bottom: 50px;
    }

    .rcorners1 {
        border-radius: 25px;
        background: lightblue;
        padding: 20px;
        margin-bottom: 30px;
        width: 180px;

    }


    .rcorners2 {
        border-radius: 25px;
        border: 2px solid lightblue;
        padding: 20px;
    }

    .wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
    }


    button {
        color: white;
        margin-top: 20px;
    }

    #addReasonButton {
        color: black;
    }

    input {
        width: 40%;

    }

    #comments {
        height: 150px;
        width: 70%;
        border-radius: 25px;
        border: 2px solid lightblue;
        padding: 20px;

    }

    form {
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    label.checkbox {
        cursor: pointer;
    }

    label.checkbox input {
        position: absolute;
        top: 0;
        left: 0;
        visibility: hidden;
        pointer-events: none;

    }

    label.labelError {
        cursor: pointer;
    }

    label.labelError input {
        position: absolute;
        top: 0;
        left: 0;
        visibility: hidden;
        pointer-events: none;

    }


    label.checkbox span {
        padding: 4px 0px;
        border: 1px solid #56baed;
        display: inline-block;
        color: #56baed;
        width: 200px;
        text-align: center;
        border-radius: 3px;
        margin-top: 7px;
        text-transform: uppercase
    }

    label.checkbox input:checked + span {
        border-color: #56baed;
        background-color: #56baed;
        color: #fff
    }

    thead {
        color: white;
        background: #56baed;
    }

    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }


</style>
