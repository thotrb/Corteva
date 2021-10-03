<template>
    <div class="row">

        <!--{{allEvents['CP']}}-->



        <!--{{allEvents}}-->


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

            <br/>
            <br/>
            <br/>

            <template v-if="show===1">

                <div class="row">

                    <div class="col">
                        <p>
                            Plant Operating Time : ...mn <br/>
                            Planned Production Time : ...mn <br/>
                            Load factor : ... % <br/>
                        </p>
                    </div>

                    <div class="col">

                        <p>
                            Volume packed : ...L <br/>
                            Nber of Production Order : ... <br/>
                            Nber of items Produced : ... <br/>

                        </p>

                    </div>
                </div>
                

                <div class="">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Planned Downtime (Prioritize list)</th>
                            <th scope="col">Duration</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row">1. No production planned (PP)</th>
                            <template v-if="allEvents['PP'][0].Duration === null">
                                <td>0 mn</td>
                            </template>
                            <template v-else>
                                <td>{{allEvents['PP'][0].Duration}} mn</td>
                            </template>

                        </tr>
                        <tr>
                            <th scope="row">2. Planned Maintenance Activities (PM)</th>
                            <template v-if="allEvents['PM'][0].Duration === null">
                                <td>0 mn</td>
                            </template>
                            <template v-else>
                                <td>{{allEvents['PM'][0].Duration}} mn</td>
                            </template>
                        </tr>
                        <tr>
                            <th scope="row">3. Capital project implementation (CP)</th>
                            <template v-if="allEvents['CP'][0].Duration === null">
                                <td>0 mn</td>
                            </template>
                            <template v-else>
                                <td>{{allEvents['CP'][0].Duration}} mn</td>
                            </template>
                        </tr>
                        <tr>
                            <th scope="row">4. Breaks, meeting, shift change (BM)</th>
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
                            <th scope="col">Unplanned Downtime </th>
                            <th scope="col">Duration</th>
                            <th scope="col">Nber Events</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row">1. Cleaning in place (CIP)</th>
                            <template v-if="allEvents['CIP'][0].Duration === null">
                                <td>0 mn</td>
                                <td>0</td>

                            </template>
                            <template v-else>
                                <td>{{allEvents['CIP'][0].Duration}}  mn</td>
                                <td>{{allEvents['CIP'][0].nbEvents}} </td>
                            </template>


                        </tr>
                        <tr>
                            <th scope="row">2. Change-Over (COV)</th>
                            <template v-if="allEvents['COV'][0].Duration === null">
                                <td>0 mn</td>
                                <td>0</td>

                            </template>
                            <template v-else>
                                <td>{{allEvents['COV'][0].Duration}}  mn</td>
                                <td>{{allEvents['COV'][0].nbEvents}} </td>
                            </template>


                        </tr>
                        <tr>
                            <th scope="row">3. Batch Number Change (BNC)</th>
                            <template v-if="allEvents['BNC'][0].Duration === null">
                                <td>0 mn</td>
                                <td>0</td>

                            </template>
                            <template v-else>
                                <td>{{allEvents['BNC'][0].Duration}}  mn</td>
                                <td>{{allEvents['BNC'][0].nbEvents}} </td>
                            </template>


                        </tr>
                        <tr>
                            <th scope="row">4. Unplanned External Events (UEE)</th>
                            <template v-if="allEvents['UEE'][0].Duration === null">
                                <td>0 mn</td>
                                <td>0</td>

                            </template>
                            <template v-else>
                                <td>{{allEvents['UEE'][0].Duration}}  mn</td>
                                <td>{{allEvents['UEE'][0].nbEvents}} </td>
                            </template>


                        </tr>
                        <tr>
                            <th scope="row">5. Unplanned Shutdown of Machine (USM)</th>
                            <template v-if="allEvents['USM'][0].Duration === null">
                                <td>0 mn</td>
                                <td>0</td>
                            </template>
                            <template v-else>
                                <td>{{allEvents['USM'][0].Duration}}  mn</td>
                                <td>{{allEvents['USM'][0].nbEvents}} </td>
                            </template>


                        </tr>
                        <tr>
                            <th scope="row">6. Filler Unplanned Shutdown (UEE)</th>
                            <template v-if="allEvents['FUS'][0].Duration === null">
                                <td>0 mn</td>
                                <td>0</td>

                            </template>
                            <template v-else>
                                <td>{{allEvents['FUS'][0].Duration}}  mn</td>
                                <td>{{allEvents['FUS'][0].nbEvents}} </td>
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
                            <th scope="col">Speed Losses </th>
                            <th scope="col">Duration</th>
                            <th scope="col">Nber Events</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row">1. Reduced Rate at Filler (RRF)</th>
                            <template v-if="allEvents['RRF'][0].Duration === null">
                                <td>0 mn</td>
                                <td>0</td>

                            </template>
                            <template v-else>
                                <td>{{allEvents['RRF'][0].Duration}}  mn</td>
                                <td>{{allEvents['RRF'][0].nbEvents}} </td>
                            </template>


                        </tr>
                        <tr>
                            <th scope="row">2.  Reduced Rate at a Machine (RRM)</th>
                            <template v-if="allEvents['RRM'][0].Duration === null">
                                <td>0 mn</td>
                                <td>0</td>

                            </template>
                            <template v-else>
                                <td>{{allEvents['RRM'][0].Duration}}  mn</td>
                                <td>{{allEvents['RRM'][0].nbEvents}} </td>
                            </template>


                        </tr>
                        <tr>
                            <th scope="row">3. Filler Own Stoppage (FOS)</th>
                            <template v-if="allEvents['FOS'][0].Duration === null">
                                <td>0 mn</td>
                                <td>0</td>
                            </template>
                            <template v-else>
                                <td>{{allEvents['FOS'][0].Duration}}  mn</td>
                                <td>{{allEvents['FOS'][0].nbEvents}} </td>
                            </template>

                        </tr>
                        <tr>
                            <th scope="row">4. Filler Stoppage by other Machine (FSM)</th>
                            <template v-if="allEvents['FSM'][0].Duration === null">
                                <td>0 mn</td>
                                <td>0</td>

                            </template>
                            <template v-else>
                                <td>{{allEvents['FSM'][0].Duration}}  mn</td>
                                <td>{{allEvents['FSM'][0].nbEvents}} </td>
                            </template>

                        </tr>
                        </tbody>
                    </table>
                </div>
            </template>



        </div>

        <div class="col">
            <p>
                Fenetre de production

            </p>
            <br/>
            <div class="d-flex">
                <label class="" for="startingPO">De</label>
                <input type="date" id="startingPO" class=" " required v-model="beginningDate">


                <label class="" for="endingPO">A</label>
                <input type="date" id="endingPO" class=""
                       required v-model="endingDate">
            </div>

        </div>


    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "downtimesReport",


        data() {
            return {
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

                    this.$store.dispatch('fecthAllEvents', tab);
                    await this.resolveAfter15Second();
                    this.index = index;
                    this.show = 1;
                }

                console.log(this.productionline);

                console.log(this.beginningDate);
                console.log(this.endingDate);
            },

            resolveAfter15Second: function () {
                return new Promise(resolve => {
                    setTimeout(() => {
                        resolve('resolved');
                    }, 1500);
                });
            },

        },


        mounted() {
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
        max-height: 300px;
    }


    .rcorners2 {
        border: 2px solid lightgray;
        padding: 20px;
    }
</style>
