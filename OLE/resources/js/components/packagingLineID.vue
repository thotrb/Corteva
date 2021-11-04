<template>
    <div class="d-flex">


        <br/>




        <div class="col">

            <div class="d-flex">

                <form>
                    <label class="" for="site">{{$t("site")}}  : </label>
                    <select name="site" id="site" class="form-select" v-model="site">
                        <template v-for="site in sites[0]">
                            <option v-bind:value="site.name">
                                {{site.name}}
                            </option>
                        </template>
                    </select>
                </form>

            </div>

            <br/>

            <div class="d-flex">
                <form>
                    <label class="" for="productionline">{{$t("productionLine")}}  : </label>
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

            <template v-if="show === 1">
                <br/>

                <br/>

                <h2>{{$t("flowDiagram")}}</h2>

                <br/>

                <img src="images/diagram.png" alt="" width="80%" height="40%">

            </template>


        </div>


        <div class="col">

            <h1>
                {{$t("packagingLineID")}}
            </h1>

            <br/>

            <template v-if="show === 1">
                <h2>
                    {{$t("machineList")}}
                </h2>

                <div class="table-info-data">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">{{$t("machine")}}</th>
                            <th scope="col">{{$t("operation")}}</th>
                            <th scope="col">{{$t("provider")}}</th>
                            <th scope="col">{{$t("model")}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="machine in machines[0]">
                            <tr>
                                <th scope="row">{{machine.denomination_ordre}}</th>
                                <td>{{machine.name}}</td>
                                <td>{{machine.operation}}</td>
                                <td>{{machine.fabricant}}</td>
                                <td>{{machine.modele}}</td>

                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>


                <br/>

                <h2>
                    {{$t("formatList")}}
                </h2>


                <div class="table-info-data">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">{{$t("format")}}</th>
                            <th scope="col">{{$t("form")}}</th>
                            <th scope="col">{{$t("mat1")}}</th>
                            <th scope="col">{{$t("mat2")}}</th>
                            <th scope="col">{{$t("mat3")}}</th>
                            <th scope="col">{{$t("designRate")}}</th>

                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="format in machines[1]">
                            <tr>
                                <th scope="row">{{format.format}}</th>
                                <td>{{format.shape}}</td>
                                <td>{{format.mat1}}</td>
                                <td>{{format.mat2}}</td>
                                <td>{{format.mat3}}</td>
                                <td>{{format.design_rate}}</td>

                            </tr>
                        </template>
                        </tbody>
                    </table>

                </div>

                <br/>

            </template>
        </div>
    </div>

</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "packagingLineID",

        data() {
            return {
                site: '',
                productionline: '',
                product: '',
                formulationType: '',
                reporting: '',
                tool: '',
                username: sessionStorage.getItem("username"),
                index: -1,
                show: 0,
                lo: this.$t("load")
            }
        },

        methods: {
            load: function () {
                var index = 0;
                for (let i = 0; i < this.sites[1].length; i++) {

                    if (this.sites[1][i].productionline_name === this.productionline) {
                        index = this.sites[1][i].id;
                    }
                }


                if (this.productionline !== '') {

                    this.$store.dispatch('fetchMachines', index);
                    this.index = index;
                    this.show = 1;

                }
            },
        },


        mounted() {
            if(sessionStorage.getItem("language") !== null){
                this.$i18n.locale = sessionStorage.getItem("language");
            }
            this.$store.dispatch('fetchSites');
        },

        computed: {
            ...mapGetters([
                'sites',
                'machines',
            ])
        }
    }


</script>

<style scoped>

    h1 {
        font-size: 1.4em;
        color: #56baed;
    }

    h2 {
        font-size: 1.2em;
        color: #56baed;
    }

    div {
        background-color: #fff;
        padding: 15px;
    }

    thead {
        color: white;
        background: #56baed;
    }

    .table-info-data {
        overflow: scroll;
        max-height: 200px;
    }




    .rcorners2 {
        border: 2px solid lightgray;
        padding: 20px;
    }

</style>
