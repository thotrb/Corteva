<template>
    <div class="d-flex main-container">
        <!-- Page title - Unplanned Downtime Dashboard -->
        <div class="row container-title">
            <span>Unplanned Downtime Dashboard</span>
        </div>

        <!-- Interval, site and production line selection menu -->
        <div class="d-flex selection-menu">
            <!-- Site and production line selection-->
            <div class="d-flex site-pl-selection">
                <div class="d-flex">
                    <label for="site-selection">Site: </label>
                    <select id="site-selection" v-model="site" v-on:change="siteSelected();">
                        <template v-for="site of sites[0]">
                            <option v-bind:key="site.name" v-bind:value="site.name">{{site.name}}</option>
                        </template>
                    </select>
                </div>
                <div class="d-flex">
                    <label for="pl-selection">Production line: </label>
                    <select id="pl-selection">
                        <template v-for="productionLine of sites[1]">
                            <template v-if="productionLine.name === site">
                                <option v-bind:key="productionLine.productionline_name" v-bind:value="productionLine.productionline_name">
                                    {{productionLine.productionline_name}}
                                </option>
                            </template>
                        </template>
                    </select>
                </div>
            </div>

            <!-- Production window -->
            <div class="d-flex production-window">
                <div class="d-flex title">
                    <span>Production Window</span>
                </div>
                <div class="d-flex interval-selection">
                    <span>From</span>
                    <select id="select-year-from" v-on:change="calculateYearsAfterFrom();">
                        <template v-for="year of years">
                            <option v-bind:key="year" v-bind:value="year">{{year}}</option>
                        </template>
                    </select>
                    <span>to</span>
                    <select id="select-year-to" v-on:change="loadTable();">
                        <template v-for="year of yearsAfterFrom">
                            <option v-if="year == currentYear" :key="year" selected>{{year}}</option>
                            <option v-else :key="year">{{year}}</option>
                        </template>
                    </select>
                </div>
            </div>
        </div>

        <!-- Downtime table, yearly and average information container-->
        <div class="d-flex table-ya-container">
            <!-- Downtime table -->
            <div class="d-flex container-table">
                <div class="table">
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            <template v-for="month of months">
                                <th :key="month" scope="col">{{month}}</th>
                            </template>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="cat of unplannedDowntimesCategories">
                            <tr :key="cat">
                                <th scope="col" class="side">
                                    <tr class="table-row-title">{{cat}}</tr>
                                    <tr class="table-sub-row">&emsp;Duration</tr>
                                    <tr class="table-sub-row">&emsp;Number</tr>
                                </th>
                                <template v-for="month of months">
                                    <td :key="month">
                                        <tr style="visibility: hidden;">-----</tr>
                                        <tr class="table-sub-row">{{month}}</tr>
                                        <tr class="table-sub-row">{{month}}</tr>
                                    </td>
                                </template>
                            </tr>
                        </template>
                    </tbody>
                </div>
            </div>

            <!-- Downtime yearly and average info -->
            <div class="d-flex container-yearly-avg-info">
                <template v-for="cat of ['cip', 'cov', 'bnc']">
                    <div :key="cat" class="d-flex ya-info-row">
                        <div class="d-flex">
                            <span style="font-weight: bold">{{cat.toUpperCase()}}</span>
                            <span>&emsp;{{yearlyDowntimes[cat].hours}} Hours</span>
                            <span>&emsp;{{yearlyDowntimes[cat].count}} {{cat.toUpperCase()}}</span>
                        </div>
                        <div class="d-flex">
                            <span style="font-weight: bold">Average</span>
                            <span>&emsp;{{yearlyDowntimes[cat].avgHours}} Hours</span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
        <button v-on:click="test()">TESTE</button>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "unplannedDowntimeDashboard",

        data() {
            return {
                months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                years: [],
                yearsAfterFrom: [],
                currentYear: (new Date()).getFullYear(),
                startYear: 2000,
                unplannedDowntimesCategories: ['Cleaning in Place (CIP)', 'Change Over (COV)', 'Batch Number Change (BNC)'],
                yearlyDowntimes: {
                    cip: {
                        hours: 20,
                        count: 30,
                        avgHours: 3
                    },
                    cov: {
                        hours: 20,
                        count: 30,
                        avgHours: 3
                    },
                    bnc: {
                        hours: 20,
                        count: 30,
                        avgHours: 3
                    }
                },
                site: '',
                productionLine: ''
            }
        },

        methods: {
          calculateYearsAfterFrom: function () {
              const selectedYear = document.getElementById('select-year-from').value;
              this.yearsAfterFrom = [];
              for (let i = selectedYear; i <= this.currentYear; i++) this.yearsAfterFrom.push(i);

          },
          siteSelected: function () {
              if (document.getElementById("site-selection").value) document.querySelector('div.production-window').style.visibility = 'visible';
              else document.querySelector('div.production-window').style.visibility = 'hidden';
          },
          loadTable: function () {
              
          },
          test: function () {
              const selectedSite = document.getElementById('site-selection').value;
              const selectedPL = document.getElementById('pl-selection').value;

              //Api accepts dates in the format yyyy-mm-dd
              const dateFrom = document.getElementById('select-year-from').value + '-01-01';
              const dateTo = document.getElementById('select-year-to').value + '-31-12';
              const params = [selectedSite, selectedPL, dateFrom, dateTo];

              this.$store.dispatch('fetchAllEvents', params);
              console.log(this.allEvents)
          }
        },

        mounted() {
            this.$store.dispatch('fetchSites');

            //Populate years array
            for (let i = this.startYear; i <= this.currentYear; i++) this.years.push(i);
            this.yearsAfterFrom = this.years;
        },

        computed: {
            ...mapGetters(['sites', 'allEvents'])
        }
    }
</script>
<style scoped>
    div.main-container {
        flex-direction: column;
        background-color: white;
        padding: 20px;
        min-width: 1000px;
        border-radius: 5px;
        margin-top: 20px;
    }

    div.container-title {
        justify-content: center;
    }

    div.container-title > span {
        font-size: 30px;
        font-weight: bold;
        color: black;
    }

    div.selection-menu {
        flex-direction: row;
        padding: 20px 0px;
        border-bottom: solid 1px;
    }

    div.production-window {
        flex-direction: column;
        width: 25%;
        min-width: 350px;
        border: solid 1px;
        border-radius: 5px;
        padding: 10px 5px;
        height: 91px;
        margin-left: auto;
        visibility: hidden;
    }

    div.production-window > div {
        justify-content: center;
    }

    div.production-window > div.title span {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    div.production-window > div.interval-selection > select {
        margin: 0px 10px;
    }

    div.production-window > div.interval-selection > * {
        font-size: 17px;
    }

    div.site-pl-selection {
        flex-direction: column;
        justify-content: space-evenly;
    }

    div.site-pl-selection > div{
        align-items: center;
    }



    div.site-pl-selection label {
        margin: 0px 10px 0px 0px;
    }

    div.table-ya-container {
        margin-top: 20px;
        justify-content: center;
    }

    div.container-table tr.table-sub-row {
        color: gray;
    }

    div.container-yearly-avg-info {
        flex-direction: column;
        justify-content: space-around;
        margin-left: 30px;
    }

    div.container-yearly-avg-info div.ya-info-row > div {
        flex-direction: column;
        margin: 0px 50px 15px 0px;
    }
        
    thead {
        color: white;
        background: #56baed;
    }

</style>