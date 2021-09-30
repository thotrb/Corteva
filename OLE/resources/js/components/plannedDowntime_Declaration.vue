<template>
    <div>
        <div class="container">

            <!--<div class="d-flex flex-row justify-content-between align-items-center bg-white">-->


                <div align="center" class="rcorners2 table-info-data">
                    <h4>Historique des arrêts</h4>
                    <br>

                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Type</th>
                            <th scope="col">Date de saisie</th>
                            <th scope="col">Durée (minutes)</th>
                            <th scope="col">Commentaire</th>

                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="event in events1">
                            <tr>
                                <th scope="row">{{event.type}}</th>
                                <td>{{event.updated_at.split(' ')[1]}}</td>
                                <td>{{event.total_duration}}</td>
                                <td>{{event.comment}}</td>
                            </tr>
                        </template>

                        <template v-for="event in events2">
                            <tr>
                                <th scope="row">{{event.type}}</th>
                                <td>{{event.updated_at}}</td>
                                <td>{{event.total_duration}}</td>
                                <td>{{event.comment}}</td>
                            </tr>
                        </template>
                        </tbody>
                    </table>

                </div>
            <!--</div>-->

            <br>

            <div align="center" class="productionName rcorners2">
                {{title}}
            </div>

        </div>


        <br/>

        <form>


            <div class="form-group row">
                <label class="col-sm-2 col-form-label rcorners1" for="totalDuration">Durée (minutes)</label>
                <div class="col-sm-10">
                    <input type="number" id="totalDuration" class="form-control-plaintext rcorners2" required>
                </div>
            </div>

            <div class="form-group row">
                <label for="comments" class="col-sm-2 rcorners1">Commentaires</label>
                <div class="col-sm-10">
                    <textarea id="comments">
                    </textarea>

                </div>
            </div>


            <div align="right">
                <button class="btn btn-primary border-danger align-items-right btn-danger" type="button"
                        @click.prevent="backOrigin()">
                    Annuler
                </button>
            </div>
            <div class="d-flex flex-row justify-content-between align-items-center bg-white">
                <button class="btn btn-primary d-flex align-items-center btn-danger" type="button"
                        @click.prevent="backPage()">
                    Retour
                </button>

                <button class="btn btn-primary d-flex align-items-center btn-warning" id="addReasonButton" type="button"
                        @click.prevent="addReason()">
                    Ajouter une raison
                </button>


                <button class="btn btn-primary border-success align-items-center btn-success" type="button"
                        @click.prevent="validateInformations()">
                    OK
                </button>
            </div>
        </form>


    </div>

</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "plannedDowntime_Declaration",

        data() {
            return {
                url: sessionStorage.getItem("url"),
                productionName: sessionStorage.getItem("productionName"),
                prodlines: sessionStorage.getItem("prodlines").split(','),

                downtimeType: sessionStorage.getItem("downtimeType"),
                indice: sessionStorage.getItem("indice"),
                PO: sessionStorage.getItem("pos").split(','),

                parameters: [],
                printedStep: 0,
                title: sessionStorage.getItem("reasonDowntime"),

                Planned_Event: {


                    OLE: '',
                    productionline: '',
                    reason: '',
                    duration: 10,
                    comment: '',
                },


            }
        },

        methods: {

            validateInformations: function () {
                this.Planned_Event.OLE = sessionStorage.getItem("pos").split(',')[this.indice];
                this.Planned_Event.productionline = sessionStorage.getItem("productionName");
                this.Planned_Event.reason = sessionStorage.getItem("reasonDowntime");

                this.Planned_Event.duration = document.getElementById('totalDuration').value;
                this.Planned_Event.comment = document.getElementById('comments').value;

                //console.log(sessionStorage.getItem("pos").split(','));

                console.log(this.Planned_Event);


                this.$store.dispatch('create_plannedEvent', this.Planned_Event);

                this.backOrigin();

            },

            addReason : function(){
                this.Planned_Event.OLE = sessionStorage.getItem("pos").split(',')[this.indice];
                this.Planned_Event.productionline = sessionStorage.getItem("productionName");
                this.Planned_Event.reason = sessionStorage.getItem("reasonDowntime");

                this.Planned_Event.duration = document.getElementById('totalDuration').value;
                this.Planned_Event.comment = document.getElementById('comments').value;

                //console.log(sessionStorage.getItem("pos").split(','));

                console.log(this.Planned_Event);
                this.$store.dispatch('create_plannedEvent', this.Planned_Event);

                window.location.href = this.url + 'summary/' + this.productionName + '/plannedDowntime';
            },

            backOrigin: function () {

                window.location.href = this.url + 'summary';


            },


            resolveAfter05Second: function () {
                return new Promise(resolve => {
                    setTimeout(() => {
                        resolve('resolved');
                    }, 500);
                });
            },

            backPage: function () {


                window.location.href = this.url + 'summary/' + this.productionName + '/' + this.downtimeType;


            }


        },

        mounted() {

            var tab = [];
            for (let i = 0; i < this.prodlines.length; i++) {
                if (this.productionName === this.prodlines[i]) {
                    this.indice = i;
                    tab.push(i + 1);
                    tab.push(this.PO[i]);
                    tab.push(this.prodlines[i]);
                    this.$store.dispatch('fetchEvents', tab);
                }
            }

        },

        computed: {
            ...mapGetters([
                'events1',
                'events2',
            ])
        }


    }
</script>


<style scoped>


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

    .table-info-data {
        overflow:scroll; max-height: 300px;
    }


</style>
