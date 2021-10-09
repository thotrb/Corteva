<template>
    <div class="row">
        <!--

        machine : {{machines}}
        <br/>

        <h1>
            production : {{productionline}}
        </h1>


        <h1>
            index : {{index}}
        </h1>

-->

        <br/>

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

            <br/>

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

            <template v-if="show === 1">
                <br/>

                <br/>

                <h2>Diagramme de flux</h2>

                <br/>

                <img src="images/diagram.png" alt="" width="80%" height="40%">

            </template>


        </div>


        <div class="col">

            <h1>
                Packaging Line ID
            </h1>

            <br/>

            <template v-if="show === 1">
                <h2>
                    Liste des machines
                </h2>

                <div class="table-info-data">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">Machine</th>
                            <th scope="col">Operation</th>
                            <th scope="col">Fabricant</th>
                            <th scope="col">Modele</th>
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
                    Liste des formats
                </h2>


                <div class="table-info-data">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Format</th>
                            <th scope="col">Forme</th>
                            <th scope="col">Mat1</th>
                            <th scope="col">Mat2</th>
                            <th scope="col">Mat3</th>
                            <th scope="col">Design rate</th>

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

                <div class="d-flex">

                    <form>

                        <label class="" for="product">Produit : </label>

                        <select name="product" id="product" class="form-select" v-model="product">
                            <template v-for="product in machines[2]">
                                <option v-bind:value="product.name">
                                    {{product.name}}
                                </option>

                            </template>
                        </select>
                    </form>

                </div>


                <div class="d-flex">
                    <form>
                        <label class="" for="formulationType">Type de Formulation : </label>

                        <select name="formulationType" id="formulationType" class="form-select"
                                v-model="formulationType">
                            <template v-for="formulation in machines[3]">
                                <option v-bind:value="formulation.formulation">
                                    {{formulation.formulation}}
                                </option>

                            </template>
                        </select>
                    </form>

                </div>


                <div class="d-flex">
                    <form>
                        <label class="" for="oleReporting">OLE Reporting : </label>
                        <div class="col-sm-10">
                            <input type="text" id="oleReporting" class="form-control-plaintext rcorners2"
                                   v-model="reporting">
                        </div>
                    </form>
                </div>

                <div class="d-flex">

                    <form>
                        <label class="" for="oleReporting">OLE Tool : </label>
                        <div class="col-sm-10">
                            <input type="text" id="oleTool" class="form-control-plaintext rcorners2" v-model="tool">
                        </div>
                    </form>

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
