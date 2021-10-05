<template>


    <div>

        <template v-if="displayNumber === 0">
            <div align="center">
                <div align="center" class="col productionName rcorners2">
                    Performance
                </div>

                <form>
                    <div class="form-group row blockInput">
                        <label class="col-sm-2 col-form-label rcorners1" for="startingPO">Heure de début de PO</label>
                        <div class="col-sm-10">
                            <!--<input type="number" id="startingPO" class="form-control-plaintext rcorners2"
                            v-bind:value="startPO" disabled>-->

                            <input type="time" id="startingPO" class="form-control-plaintext rcorners2"
                                   min="00:00" max="24:00" required v-model="startPO">
                        </div>

                    </div>

                    <div class="form-group row blockInput">
                        <label for="endingPO" class="col-sm-2 rcorners1">Heure de fin de PO</label>
                        <div class="col-sm-10">
                            <!--<input type="number" id="endingPO" class="form-control-plaintext rcorners2"
                            v-bind:value="endPO">-->

                            <input type="time" id="endingPO" class="form-control-plaintext rcorners2"
                                   min="00:00" max="24:00" required v-model="endPO">
                        </div>
                    </div>

                    <div class="form-group row blockInput">
                        <label for="finalQuantityProduced" class="col-sm-2 rcorners1">Quantité finale produite</label>
                        <div class="col-sm-10">
                            <input type="number" id="finalQuantityProduced" class="form-control-plaintext rcorners2" v-model="finalQuantityProduced">
                        </div>
                    </div>

                </form>


                <div align="center" class="rcorners2">

                    <div align="left">


                        TOTAL PO OPERATING TIME (min): {{ ( (endPO.toString().split(':')[0]*60 +
                        endPO.toString().split(':')[1])
                        - (startPO.toString().split(':')[0]*60 + startPO.toString().split(':')[1])) }}
                        <br/>
                        TOTAL PO NET OPERATING TIME (min): {{(endPO.toString().split(':')[0]*60 +
                        endPO.toString().split(':')[1])
                        - (startPO.toString().split(':')[0]*60 + startPO.toString().split(':')[1])
                        - total_dowtimes}}
                        <br/>
                        TOTAL PO PERFORMANCE (%) : {{ ( (endPO.toString().split(':')[0]*60 +
                        endPO.toString().split(':')[1])
                        - (startPO.toString().split(':')[0]*60 + startPO.toString().split(':')[1]) - total_dowtimes) /

                        ( (endPO.toString().split(':')[0]*60 + endPO.toString().split(':')[1])
                        - (startPO.toString().split(':')[0]*60 + startPO.toString().split(':')[1]) )

                        *100}}
                    </div>

                    <br/>

                    <template v-if="speedLoss.length <= 0">
                        <h4>Aucune perte de performance enregistrée</h4>
                    </template>
                    <template v-else>
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Raison</th>
                                <th scope="col">Commentaire</th>
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


                <div align="right">
                    <button class="btn btn-primary border-danger align-items-right btn-danger" type="button"
                            @click.prevent="backPage()">
                        Annuler
                    </button>
                </div>
                <div class="d-flex flex-row justify-content-between align-items-center bg-white">
                    <button class="btn btn-primary d-flex align-items-center btn-danger" type="button"
                            @click.prevent="backPage()">
                        Retour
                    </button>

                    <button class="btn btn-primary d-flex align-items-center btn-warning" id="addReasonButton"
                            type="button"
                            @click.prevent="addSpeedLoss()">
                        Justification perte de performance
                    </button>


                    <button class="btn btn-primary border-success align-items-center btn-success" type="button"
                            @click.prevent="validateInformations()">
                        OK
                    </button>
                </div>


            </div>
        </template>

        <template v-if="displayNumber ===1">
            <div>
                <div align="center" class="productionName rcorners2">
                    Perte de vitesse {{nbSpeedLosses + 1}}
                </div>

                <br/>


                <div class="row" align="center" style="margin-bottom: 30px;">

                    <div class="col-sm-4">
                        <label for="answer1" class="checkbox labelsAnswer">
                            <input type="radio"
                                   id="answer1" name="reponseQuestion"
                                   value="Reduced Rate At Filler" class="response">
                            <span>Niveau réduit au remplisseur</span>
                        </label>

                    </div>

                    <div class="col-sm-4">
                        <label for="answer2" class="checkbox labelsAnswer">
                            <input type="radio"
                                   id="answer2" name="reponseQuestion"
                                   value="Reduce Rate At An Other Machine" class="response">
                            <span>Niveau réduit à une autre machine</span>
                        </label>


                    </div>

                    <div class="col-sm-4">

                        <label for="answer3" class="checkbox labelsAnswer">
                            <input type="radio"
                                   id="answer3" name="reponseQuestion"
                                   value="Filler Own Stoppage" class="response">
                            <span>Remplisseur mis à l'arrêt de lui-même</span>
                        </label>


                    </div>

                    <div class="col-sm-4">
                        <label for="answer4" class="checkbox labelsAnswer">
                            <input type="radio"
                                   id="answer4" name="reponseQuestion"
                                   value="Filler Own Stoppage By An Other Machine" class="response">
                            <span>Remplisseur mis à l'arrêt par une autre source</span>
                        </label>

                    </div>

                </div>

                <br>

                <form>
                    <div class="form-group row">
                        <label for="comments" class="col-sm-2 rcorners1">Commentaires</label>
                        <div class="col-sm-10">
                            <textarea id="comments" class="form-control-plaintext rcorners2"></textarea>
                        </div>
                    </div>
                </form>

                <br/>

                <div class="d-flex flex-row justify-content-between align-items-center bg-white">
                    <button class="btn btn-primary d-flex align-items-center btn-danger" type="button"
                            @click.prevent="resetPage()">
                        Retour
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
                    Qualité
                </div>


                <table class="table table-hover" align="center">
                    <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col"> Remplisseuse</th>
                        <th scope="col">Visseuse</th>
                        <th scope="col">Etiqueteuse</th>
                        <th scope="col">Poids des boites</th>

                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">Compteur</th>
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
                        <th scope="row">Rejection</th>
                        <td>
                            <input type="number" id="FillerRejection" class="rcorners2"  v-model="FillerRejection">
                        </td>
                        <td>
                            <input type="number" id="CaperRejection" class="rcorners2"  v-model="CaperRejection">
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
                    Retour
                </button>


                <button class="btn btn-primary border-success align-items-center btn-success" type="button"
                        @click.prevent="displayQualityIndicators()">
                    OK
                </button>
            </div>
            <!--<quality-declaration></quality-declaration>-->

        </template>

        <template v-if="displayNumber===3">

            <h2 align="center">Récapitulatif</h2>

            <br>

            <span>
                 TOTAL PO OPERATING TIME (min):  {{totalOperatingTime}}
                <br/>
                TOTAL PO NET OPERATING TIME (min): {{totalNetOperatingTime}}
                <br/>
                TOTAL PO PERFORMANCE (%) : {{ 100 * totalNetOperatingTime / totalOperatingTime}}
            </span>

            <br/>


            <h5>Pertes de vitesse</h5>

            <template v-if="speedLoss.length <= 0">
                <h5>Aucune perte de performance enregistrée</h5>
            </template>

            <template v-else>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Raison</th>
                        <th scope="col">Commentaire</th>
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


            <h5>Qualité</h5>

            <span>
                 TOTAL PO QUALITY(min):  {{totalPOQuality}}
            </span>

            <br/>


            <table class="table table-hover" align="center">
                <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col"> Remplisseuse</th>
                    <th scope="col">Caper</th>
                    <th scope="col">Etiqueteuse</th>
                    <th scope="col">Poids des boites</th>

                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">Compteur</th>
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
                    <th scope="row">Rejection</th>
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

            <h5>Indicateurs</h5>

            <span>

                 Disponibilité (%) :  {{availability}}
                <br/>
                 Performance (%) :  {{availability}}
                <br/>
                 OLE (%) :  {{availability}}

            </span>

            <br/>

            <div class="d-flex flex-row justify-content-between align-items-center bg-white">
                <button class="btn btn-primary d-flex align-items-center btn-danger" type="button"
                        @click.prevent="validateInformations()">
                    Retour
                </button>


                <button class="btn btn-primary border-success align-items-center btn-success" type="button"
                        @click.prevent="saveEndPO()">
                    Valider
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

                total_dowtimes: sessionStorage.getItem("sommeUnplannedEvent"),

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
                availability : 0,
                performance : 0,

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

                totalPOQuality: 0,

                endPO: 0,

            };
        },

        methods: {


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


                this.totalPOQuality =  parseInt(this.finalQuantityProduced, 10) /
                    ( ( parseInt(this.finalQuantityProduced, 10) + this.FillerCounter + this.CaperCounter + this.EtiqueteuseCounter + this.WieghtBoxCounter)
                        + (this.FillerRejection -  parseInt(this.finalQuantityProduced, 10) ) + (this.CaperRejection -  parseInt(this.finalQuantityProduced, 10) )
                        + (this.EtiqueteuseRejection -  parseInt(this.finalQuantityProduced, 10) ) + (this.WieghtBoxRejection -  parseInt(this.finalQuantityProduced, 10)));



/**
                console.log("QUALITY : " + this.totalPOQuality);
                console.log("QUALITY : " + this.finalQuantityProduced);
                console.log("QUALITY : " + this.FillerCounter);
                console.log("QUALITY : " + this.CaperCounter);
                console.log("QUALITY : " + this.EtiqueteuseCounter);
                console.log("QUALITY : " + this.WieghtBoxCounter);

                console.log("QUALITY : " + this.FillerRejection);
                console.log("QUALITY : " + this.CaperRejection);
                console.log("QUALITY : " + this.EtiqueteuseRejection);
                console.log("QUALITY : " + this.WieghtBoxRejection);
**/
                this.displayNumber = 3;

            },

            saveEndPO: async function () {
                this.endPO = sessionStorage.getItem("pos").split(',')[this.indice];


                var pos = sessionStorage.getItem("pos").split(',');
                pos.splice(this.indice, 1);

                if (sessionStorage.getItem("pos") === null) {
                    sessionStorage.pos = pos;
                } else {
                    sessionStorage.setItem("pos", pos);
                }


                this.$store.dispatch('stop_PO', this.endPO);

                this.parameters.push(this.username);
                this.$store.dispatch('fetchUsers', this.parameters);


                await this.resolveAfter1Second();


                this.loadTable();

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


            validateInformations: async function () {

                if (this.displayNumber === 0) {

                    console.log(document.getElementById('endingPO').value);

                    this.totalOperatingTime = (this.endPO.toString().split(':')[0] * 60 + this.endPO.toString().split(':')[1])
                        - (this.startPO.toString().split(':')[0] * 60 + this.startPO.toString().split(':')[1]);


                    this.totalNetOperatingTime = (this.endPO.toString().split(':')[0] * 60 + this.endPO.toString().split(':')[1])
                        - (this.startPO.toString().split(':')[0] * 60 + this.startPO.toString().split(':')[1])
                        - this.total_dowtimes;


                    this.availability = 100 * 8 * 60;

                    //recuperer la ligne de production
                    var productionLine = sessionStorage.getItem("productionName");


                    //recuperer le nom du shift
                    var shiftLetter = sessionStorage.typeTeam;

                    //recuperer le nom du site
                    var site = sessionStorage.site;


                    console.log('JE TESTE MA FONCTION');

                    //recuperer les PO number
                    var parameters = [];
                    parameters.push(shiftLetter);
                    parameters.push(site);


                    await this.$store.dispatch('fetchPO', parameters);

                    await this.resolveAfter1Second();

                    console.log(this.pos);

                    var sumUnplannedDuration = 0;
                    var sumPlannedDuration = 0;

                    for(let i =0; i<this.pos.length; i++){
                        var tab = [];
                        tab.push(1);
                        tab.push(this.pos[i].po);
                        tab.push(productionLine);

                        await this.$store.dispatch('fetchEvents', tab);
                        await this.resolveAfter1Second();

                        console.log('EVENTS');

                        console.log(this.events1);


                        for (let j = 0; j < this.events1.length; j++) {
                            if(this.events1[j].kind === 1){
                                sumUnplannedDuration += this.events1[j].total_duration;
                            }else{
                                sumPlannedDuration += this.events1[j].total_duration;
                            }
                        }


                    }

                    console.log('SOMME totale : ' + sumUnplannedDuration + sumPlannedDuration);

                    this.availability = this.availability - (sumPlannedDuration - sumUnplannedDuration) / (sumPlannedDuration);


                    var sumNetOperatingTime = 0;
                    var sumTotalOperatingTime = 0;
                    for(let j =0; j<this.pos.length; j++){

                        if(this.pos[j].po !== sessionStorage.getItem("pos").split(',')[this.indice]){
                            sumNetOperatingTime += this.pos[j].totalNetOperatingTime;
                            sumTotalOperatingTime += this.pos[j].totalOperatingTime;
                        }else{
                            sumNetOperatingTime += this.totalNetOperatingTime;
                            sumTotalOperatingTime += this.totalOperatingTime;
                        }
                    }

                    this.performance = 100 * sumNetOperatingTime + sumTotalOperatingTime;



                    //100 *SUM ( for all the PO of the shift including the last one ( even not achieved ) ) TOTAL PO NET OPERATING TIME
                    /// SUM ( for all the PO of the shift including the last one ( even not achieved ) ) TOTAL PO OPERATING TIME

                    //this.$store.dispatch('fetchEventsShift', parameters);


                    /**
                     if (sessionStorage.getItem("TOTAL_PO_OPERATING_TIME") === null) {
                        sessionStorage.TOTAL_PO_OPERATING_TIME = totalOperatingTime;
                    } else {
                        sessionStorage.setItem("TOTAL_PO_OPERATING_TIME", totalOperatingTime);
                    }**/

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


        },

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


        },

        computed: {
            ...mapGetters([
                'speedLoss',
                'user',
                'pos',
                'events1',
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
