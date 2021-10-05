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
                    <select id="site-selection"></select>
                </div>
                <div class="d-flex">
                    <label for="pl-selection">Production line: </label>
                    <select id="pl-selection"></select>
                </div>
            </div>

            <!-- Production window -->
            <div class="d-flex production-window">
                <div class="d-flex title">
                    <span>Production Window</span>
                </div>
                <div class="d-flex interval-selection">
                    <span>From</span>
                    <select id="select-month-from" v-on:change="calculateMonthsAfterFrom(); calculateTableHeaders();">
                        <template v-for="month of months">
                            <option v-bind:key="month" v-bind:value="month">{{month}}</option>
                        </template>
                    </select>
                    <span>to</span>
                    <select id="select-month-to" v-on:change="calculateTableHeaders()">
                        <template v-for="month of months">
                            <option :key="month">{{month}}</option>
                        </template>
                    </select>
                </div>
            </div>
        </div>

        <!-- Downtime table, yeraly and average information container-->
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
                                        <tr style="visibility: hidden;"> -- </tr>
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
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "unplannedDowntimeDashboard",

        data() {
            return {
                months: ['January', 'February', 'Mars', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                unplannedDowntimesCategories: ['Cleaning in Place (CIP)', 'Change Over (COV)', 'Batch Number Change (BNC)'],
                downtimeTableHeaderCols: [],
                unplannedDowntimesMonthly: {},
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
                }
            }
        },

        methods: {
          calculateMonthsAfterFrom: function () {
            let monthFrom = document.getElementById("select-month-from").value;
            let monthsInInterval = [];
            for (let i = this.months.indexOf(monthFrom); i < this.months.length; i++) {
                monthsInInterval.push(this.months[i]);
            }
            this.monthsAfterFrom = monthsInInterval;
          },

          calculateTableHeaders: function () {
              let monthFrom = document.getElementById("select-month-from").value;
              let monthTo = document.getElementById("select-month-to").value;
              let headers = [];

              for (let i = this.months.indexOf(monthFrom); i <= this.months.indexOf(monthTo); i++) {
                  headers.push(this.months[i]);
              }

              this.downtimeTableHeaderCols = headers;
          }
        },

        mounted() {
            this.monthsAfterFrom = this.months;
            this.downtimeTableHeaderCols = this.months;
            this.$store.dispatch('fetchSites');
        },

        computed: {
            ...mapGetters(['sites'])
        }
    }
</script>
<style scoped>
    div.main-container {
        flex-direction: column;
        background-color: white;
        padding: 20px;
        min-width: 1320px;
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