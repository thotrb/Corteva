<template>
    <div>
        <div align="center" class="productionName rcorners2">

            {{title}}

        </div>

        <br/>

        <form>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label rcorners1" for="previousBulk">{{$t("previousBulk")}}</label>
                <div class="col-sm-10">
                    <input type="text" id="previousBulk" class="form-control-plaintext rcorners2" value="prevBulk" disabled>
                </div>
            </div>


            <div class="form-group row">
                <label class="col-sm-2 col-form-label rcorners1" for="expectedDuration">{{$t("expectedDuration(Minutes)")}}</label>
                <div class="col-sm-10">
                    <input type="number" id="expectedDuration" class="form-control-plaintext rcorners2" value="5" disabled>
                </div>
            </div>


            <div class="form-group row">
                <label class="col-sm-2 col-form-label rcorners1" for="totalDuration">{{$t("totalDuration(Minutes)")}}</label>
                <div class="col-sm-10">
                    <input type="number" id="totalDuration" class="form-control-plaintext rcorners2">
                </div>
            </div>

            <div class="form-group row">
                <label for="comments" class="col-sm-2 rcorners1">{{$t("comments")}}</label>
                <div class="col-sm-10">
                    <textarea id="comments" class="form-control-plaintext rcorners2"></textarea>
                </div>
            </div>


            <div align="right">
                <button class="btn btn-primary border-danger align-items-right btn-danger" type="button"
                        @click.prevent="backOrigin()">
                    {{$t("cancel")}}
                </button>
            </div>
            <div class="d-flex flex-row justify-content-between align-items-center bg-white">
                <button class="btn btn-primary d-flex align-items-center btn-danger" type="button"
                        @click.prevent="backPage()">
                    {{$t("back")}}
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

    export default {
        name: "CIP_Declaration",

        data() {
            return {
                url: sessionStorage.getItem("url"),
                productionName: sessionStorage.getItem("productionName"),
                downtimeType: sessionStorage.getItem("downtimeType"),
                indice: sessionStorage.getItem("indice"),

                parameters: [],
                printedStep: 0,
                title: 'CIP',

                CIP_Event: {

                    OLE: '',

                    productionline: sessionStorage.getItem("productionName"),

                    previous_bulk: '',

                    predicted_duration:  5,

                    total_duration: 10,

                    //commentaire
                    comment :'',
                },


            }
        },

        methods: {

            validateInformations : function(){

                this.CIP_Event.OLE = sessionStorage.getItem("pos").split(',')[this.indice];

                this.CIP_Event.previous_bulk = document.getElementById('previousBulk').value;
                this.CIP_Event.predicted_duration = document.getElementById('expectedDuration').value;
                this.CIP_Event.total_duration = document.getElementById('totalDuration').value;
                this.CIP_Event.comment = document.getElementById('comments').value;

                console.log(this.CIP_Event);

                this.$store.dispatch('create_UnplannedEvent_CIP', this.CIP_Event);
                this.backOrigin();

            },

            backOrigin : function(){

                window.location.href =  this.url + 'summary';


            },




            resolveAfter05Second: function () {
                return new Promise(resolve => {
                    setTimeout(() => {
                        resolve('resolved');
                    }, 500);
                });
            },

            backPage: function () {


                window.location.href =  this.url + 'summary/' + this.productionName + '/' + this.downtimeType;


            }


        },

        mounted() {




        },



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

</style>
