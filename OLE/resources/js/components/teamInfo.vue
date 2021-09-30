<template>
    <div>

        <form>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label rcorners1" for="site">Site</label>
                <div class="col-sm-10">
                    <input type="text" id="site" readonly class="form-control-plaintext rcorners2"
                           v-bind:value="user[0][0].name">
                </div>
            </div>

            <div class="form-group row">
                <label for="crewLeader" class="col-sm-2 rcorners1">Chef d'équipe</label>
                <select name="crewLeader" id="crewLeader" class="rcorners2">
                    <template v-for="leader in user[1]">


                        <!--
                        <template v-if="leader.firstname+' '+leader.lastname === crewL">

                            <option v-if="leader.id === user[0][0].worksiteID"
                                    v-bind:value="leader.firstname+' '+leader.lastname" selected>
                                {{leader.firstname}} {{leader.lastname}}
                            </option>

                        </template>


                        <template v-else>-->
                        <option v-if="leader.id === user[0][0].worksiteID"
                                v-bind:value="leader.firstname+' '+leader.lastname">
                            {{leader.firstname}} {{leader.lastname}}
                        </option>
                        <!--</template>-->

                    </template>

                </select>
            </div>

            <div class="form-group row">

                <label for="typeTeam" class="col-sm-2 rcorners1">Type d'équipe</label>
                <select name="Leader" id="typeTeam" class="rcorners2" v-model="selected">
                    <template v-for="shift in user[2]">
                        <option v-if="shift.worksite === user[0][0].worksiteID" v-bind:value="shift.type">
                            {{shift.type}}
                        </option>
                    </template>

                </select>
            </div>


            <div class="form-group row">
                <label for="workingDebut" class="col-sm-2 rcorners1">Heure de début</label>
                <div class="col-sm-10">
                    <template v-for="shift in user[2]">
                        <input v-if="shift.type===selected" type="text" id="workingDebut" readonly
                               class="form-control-plaintext rcorners2"
                               v-bind:value="shift.workingDebut">
                    </template>


                </div>
            </div>

            <div class="form-group row">
                <label for="workingEnd" class="col-sm-2 rcorners1">Heure de fin</label>
                <div class="col-sm-10">
                    <template v-for="shift in user[2]">
                        <input v-if="shift.type===selected" type="text" id="workingEnd" readonly
                               class="form-control-plaintext rcorners2"
                               v-bind:value="shift.workingEnd">
                    </template>


                </div>
            </div>
            <!--
                    <template v-if="pos.length === 2">

                        <div class="row production">
                            <div class="col">
                                <p align="center" class="form-control-plaintext rcorners1">
                                    Production {{productionlines[0]}}
                                </p>
                            </div>

                            <div class="col">
                                <input type="text" class="form-control-plaintext rcorners2 D-Code" name="D-Code/GMID"
                                       placeholder="D-Code/GMID">
                            </div>

                            <div class="col">
                                <input type="text" class="form-control-plaintext rcorners2 PO" name="PO" placeholder="PO"
                                       v-bind:value="pos[0]">
                            </div>
                        </div>

                        <br/>

                        <div class="row production">
                            <div class="col">
                                <p align="center" class="form-control-plaintext rcorners1">
                                    Production {{productionlines[1]}}
                                </p>
                            </div>

                            <div class="col">
                                <input type="text" class="form-control-plaintext rcorners2 D-Code" name="D-Code/GMID"
                                       placeholder="D-Code/GMID">
                            </div>

                            <div class="col">
                                <input type="text" class="form-control-plaintext rcorners2 PO" name="PO" placeholder="PO"
                                       v-bind:value="pos[1]">
                            </div>
                        </div>

                        <br/>

                    </template>
                    <template v-else>
                    -->
            <template v-for="productionLine in user[3]">

                <template v-if="productionLine.worksiteID === user[0][0].worksiteID">

                    <div class="row production">
                        <div class="col">
                            <p align="center" class="form-control-plaintext rcorners1">
                                Production {{productionLine.productionline_name}}
                            </p>
                        </div>
                        <!--
                                                <div class="col">
                                                    <p align="center" class="form-control-plaintext rcorners1">
                                                        Oui/Non
                                                    </p>
                                                </div>
                        -->
                        <div class="col">
                            <input type="text" class="form-control-plaintext rcorners2 D-Code" name="D-Code/GMID"
                                   placeholder="D-Code/GMID">
                        </div>

                        <div class="col">
                            <input type="text" class="form-control-plaintext rcorners2 PO" name="PO"
                                   placeholder="PO">
                        </div>
                    </div>

                    <br/>

                    <!--</template>-->

                </template>
            </template>


            <div align="right">

                <button class="btn btn-primary border-success align-items-center btn-success" type="button"
                        v-on:click="nextPage()">
                    OK
                </button>

            </div>
        </form>


    </div>


</template>

<script>
    import {mapGetters} from 'vuex'

    var selectElem = document.getElementById('typeTeam');


    export default {
        name: "Questionnaire",

        data() {
            return {
                username: sessionStorage.getItem("username"),
                url: sessionStorage.getItem("url"),
                parameters: [],
                selected: '',
                //productionlines: sessionStorage.getItem("prodlines").split(','),

                //pos: sessionStorage.getItem("pos").split(','),


                //crewL: sessionStorage.getItem("crewLeader"),
                //typeT: sessionStorage.getItem("typeTeam"),
                //workingE: sessionStorage.getItem("workingEnd"),
                //workingD: sessionStorage.getItem("workingDebut"),
                //site: sessionStorage.getItem("site"),


            };
        },

        mounted() {
            this.parameters.push(this.username);
            this.$store.dispatch('fetchUsers', this.parameters);


        },
        beforeMount: function () {
        },

        methods: {


            resolveAfter05Second: function () {
                return new Promise(resolve => {
                    setTimeout(() => {
                        resolve('resolved');
                    }, 500);
                });


            },

            backLogin: function () {
                window.location.href = this.url + 'menu';
            },

            nextPage: function () {

                //this.addSessionValue("productionA", this.productionA);
                var DCODES = document.getElementsByClassName('D-Code');

                var dcodesTab = [];

                for (let i = 0; i < DCODES.length; i++) {
                    dcodesTab.push(DCODES[i].value);
                }


                if (sessionStorage.getItem("dcodes") === null) {
                    sessionStorage.dcodes = dcodesTab;
                } else {
                    sessionStorage.setItem("dcodes", dcodesTab);
                }


                var POs = document.getElementsByClassName('PO');


                var poTab = [];
                var POElement = [];

                for (let i = 0; i < POs.length; i++) {
                    poTab.push(POs[i].value);
                    let po = {
                        number: POs[i].value,
                    };

                    POElement.push(po);


                }


                if (sessionStorage.getItem("pos") === null) {
                    sessionStorage.pos = poTab;
                } else {
                    sessionStorage.setItem("pos", poTab);
                }


                var nbProd = document.getElementsByClassName('production').length;


                if (sessionStorage.getItem("nbProductionlines") === null) {
                    sessionStorage.nbProductionlines = nbProd;
                } else {
                    sessionStorage.setItem("nbProductionlines", nbProd);
                }


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


                var selectCrewLeader = document.getElementById('crewLeader');
                var valueCrewLeader = selectCrewLeader.options[selectCrewLeader.selectedIndex].value;


                if (sessionStorage.getItem("crewLeader") === null) {
                    sessionStorage.crewLeader = valueCrewLeader;
                } else {
                    sessionStorage.setItem("crewLeader", valueCrewLeader);
                }

                var typeTeam = document.getElementById('typeTeam');
                var valueTypeTeam = typeTeam.options[typeTeam.selectedIndex].value;

                if (sessionStorage.getItem("typeTeam") === null) {
                    sessionStorage.typeTeam = valueTypeTeam;
                } else {
                    sessionStorage.setItem("typeTeam", valueTypeTeam);
                }


                if (sessionStorage.getItem("workingEnd") === null) {
                    sessionStorage.workingEnd = document.getElementById('workingEnd').value;
                } else {
                    sessionStorage.setItem("workingEnd", document.getElementById('workingEnd').value);
                }


                if (sessionStorage.getItem("workingDebut") === null) {
                    sessionStorage.workingDebut = document.getElementById('workingDebut').value;
                } else {
                    sessionStorage.setItem("workingDebut", document.getElementById('workingDebut').value);
                }

                if (sessionStorage.getItem("site") === null) {
                    sessionStorage.site = document.getElementById('site').value;
                } else {
                    sessionStorage.setItem("site", document.getElementById('site').value);
                }


                this.$store.dispatch('create_PO', POElement);



                window.location.href = this.url + 'summary';

            }

        },
        computed: {
            ...mapGetters([
                'user'
            ])
        }
    }


</script>

<style scoped>

    form {
        padding: 2%;
    }

    .rcorners1 {
        border-radius: 25px;
        background: lightblue;
        padding: 20px;

    }

    .rcorners2 {
        border-radius: 25px;
        border: 2px solid lightblue;
        padding: 20px;
    }

    select option {
        padding-left: 20px;
    }

    .btn:focus {
        outline: 0 !important;
        box-shadow: none !important
    }

    .btn:active {
        outline: 0 !important;
        box-shadow: none !important
    }

</style>
