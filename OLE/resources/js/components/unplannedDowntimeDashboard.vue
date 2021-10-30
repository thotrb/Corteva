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
                    <select id="site-selection" v-model="site">
                        <option disabled selected value>-- Select --</option>
                        <template v-for="site of sites[0]">
                            <option v-bind:key="site.name" v-bind:value="site.name">{{site.name}}</option>
                        </template>
                    </select>
                </div>
                <div class="d-flex">
                    <label for="pl-selection">Production line: </label>
                    <select id="pl-selection" v-on:change="productionLineSelected();">
                        <option disabled selected value>-- Select --</option>
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
            <production-window :yearSelectedFunction="chargeUnplannedEventsData"/>
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
                        <template v-for="cat of Object.keys(unplannedDowntimesCategories)">
                            <tr :key="cat">
                                <th scope="col" class="side">
                                    <tr class="table-row-title">{{unplannedDowntimesCategories[cat]}}</tr>
                                    <tr class="table-sub-row">&emsp;Duration</tr>
                                    <tr class="table-sub-row">&emsp;Number</tr>
                                </th>

                                <template v-for="month of months">
                                    <td class="table-data" :key="month">
                                        <tr style="visibility: hidden;">-----</tr>
<<<<<<< HEAD
                                        <tr class="table-sub-row">{{downtimes[cat][month].totalDuration}}</tr>
                                        <tr class="table-sub-row">{{downtimes[cat][month].totalNb}}</tr>
=======
                                        <tr class="table-sub-row">{{(downtimes[cat][month].totalDuration ? downtimes[cat][month].totalDuration.toFixed(2) : 0)}}</tr>
                                        <tr class="table-sub-row">{{downtimes[cat][month].totalNb || "0"}}</tr>
>>>>>>> 5fb64a79da0788f13cb10fb31e83c4f523a63195
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
                            <span style="font-weight: bold">{{"Yearly " + cat.toUpperCase()}}</span>
                            <span>&emsp;{{(downtimes[cat].general.avgYearlyDuration ? downtimes[cat].general.avgYearlyDuration.toFixed(2) : 0)}} Hours</span>
                            <span>&emsp;{{downtimes[cat].general.avgYearlyDuration ? downtimes[cat].general.avgYearlyNb.toFixed(2) : 0}} {{cat.toUpperCase()}}</span>
                        </div>
                        <div class="d-flex">
                            <span style="font-weight: bold">Average</span>
                            <span>&emsp;{{(downtimes[cat].general.averageEventDuration ? downtimes[cat].general.averageEventDuration.toFixed(2) : 0)}} Hours</span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Downtime graphs-->
        <div class="main-chart-container">
            <div class="chart-container">
                <canvas class="chart" id="cip-chart"></canvas>
                <p class="downtime-percent" id="cip-percent"></p>
            </div>
            <div class="chart-container">
                <canvas class="chart" id="cov-chart"></canvas>
                <p class="downtime-percent" id="cov-percent"></p>
            </div>
            <div class="chart-container">
                <canvas class="chart" id="bnc-chart"></canvas>
                <p class="downtime-percent" id="bnc-percent"></p>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import ProductionWindow from './productionWindow.vue';

    export default {
        name: "unplannedDowntimeDashboard",

        data() {

            var data = {
                months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                years: [],
                yearsAfterFrom: [],
                currentYear: (new Date()).getFullYear(),
                startYear: 2000,
                unplannedDowntimesCategories: {
                    cip: 'Cleaning in Place (CIP)',
                    cov: 'Change Over (COV)',
                    bnc: 'Batch Number Change (BNC)'
                },
                downtimes: {
                    cip: {},
                    cov: {},
                    bnc: {}
                },
                chartObjects: {
                    cip: undefined,
                    cov: undefined,
                    bnc: undefined,
                    created: false
                },
                site: '',
                productionLine: ''
            }

            //Populate downtimes array
            for (let month of data.months) {
                data.downtimes.cip[month] = {totalNb: undefined, totalDuration: undefined, events: []};
                data.downtimes.cov[month] = {totalNb: undefined, totalDuration: undefined, events: []};
                data.downtimes.bnc[month] = {totalNb: undefined, totalDuration: undefined, events: []};
            }
            data.downtimes.cip.general = {totalNb: undefined, totalDuration: undefined, average: undefined};
            data.downtimes.cov.general = {totalNb: undefined, totalDuration: undefined, average: undefined};
            data.downtimes.bnc.general = {totalNb: undefined, totalDuration: undefined, average: undefined};


            //Populate years array
            for (let i = data.startYear; i <= data.currentYear; i++) data.years.push(i);
            data.yearsAfterFrom = data.years;

            return data;
        },

        methods: {
          calculateYearsAfterFrom: function () {
              const selectedYear = document.getElementById('select-year-from').value;
              this.yearsAfterFrom = [];
              for (let i = selectedYear; i <= this.currentYear; i++) this.yearsAfterFrom.push(i);

          },

          productionLineSelected: function () {
              if (document.getElementById("pl-selection").value) {
                  document.querySelector('div.production-window').style.visibility = 'visible';
                  this.chargeUnplannedEventsData(this.startYear, this.currentYear);
              } else document.querySelector('div.production-window').style.visibility = 'hidden';
          },

          getMonth: function (dateString) {
              return parseInt(dateString.substring(5,7));
          },

          createDowntimeObject: function () {
              for (let month of this.months) {
                this.downtimes.cip[month] = {totalNb: 0, totalDuration: 0, events: []};
                this.downtimes.cov[month] = {totalNb: 0, totalDuration: 0, events: []};
                this.downtimes.bnc[month] = {totalNb: 0, totalDuration: 0, events: []};
              }
          },

          chargeUnplannedEventsData: function (dateFrom, dateTo) {
              console.log("charge events called - "+dateFrom)
              const selectedPL = document.getElementById('pl-selection').value;
              const params = [selectedPL, dateFrom, dateTo];

              this.$store.dispatch('fetchDowntimeEvents', params).then(() => {
                    //Wait for data
              this.resolveAfter(1000).then(() => {
                  //A new downtime object is created to delete previous data
                  this.createDowntimeObject();
                  let totalDuration = {
                      cip: 0,
                      cov: 0,
                      bnc: 0
                  };
                  let totalNb = {
                      cip: 0,
                      cov: 0,
                      bnc: 0
                  };
                  let totalDowntimeDuration = 0;
                  const years = dateTo - dateFrom + 1;


                  for (let type of ['cip', 'cov', 'bnc']) {
                      for (let event of this.unplannedDowntimeEvents[0][type.toUpperCase()]) {
                          const monthCreated = this.getMonth(event.created_at);
                          const month = this.months[monthCreated - 1];
                          const eventDurationInHours = event.total_duration / 60;
                          this.downtimes[type][month].events.push(event);
                          this.downtimes[type][month].totalNb++;
                          this.downtimes[type][month].totalDuration += eventDurationInHours;
                          totalDuration[type] += eventDurationInHours;
                          totalNb[type]++;
                      }
                      totalDowntimeDuration += totalDuration[type];
                      const avgYearlyNb = totalNb[type] / years;
                      const avgYearlyDuration = totalDuration[type] / years;
                      const avgEventDuration = avgYearlyDuration / avgYearlyNb;
                      //Nb.: Or (||) operator returns last velue when both are falsy
                      this.downtimes[type].general.avgYearlyNb = (avgYearlyNb || 0);
                      this.downtimes[type].general.avgYearlyDuration = (avgYearlyDuration || 0);
                      this.downtimes[type].general.averageEventDuration = (avgEventDuration || 0);
                      this.downtimes[type].general.totalNb = totalNb[type];
                      this.downtimes[type].general.totalDuration = totalDuration[type];

                      //Create charts if they dont exist already
                      if (!this.chartObjects.created) this.createCharts();

                      //Update chart data
                      this.chartObjects[type].data.datasets[0].data = [];
                      for (let month of this.months) {
                          this.chartObjects[type].data.datasets[0].data.push(this.downtimes[type][month].totalNb);
                      }
                      this.chartObjects[type].update();
                  }

                  for (let type of ['cip', 'cov', 'bnc'])  {
                      let downtimePercent = (this.downtimes[type].general.totalDuration / totalDowntimeDuration) * 100;
                      if (downtimePercent) downtimePercent.toFixed(2);
                      this.downtimes[type].general.downtimePercentage = downtimePercent;
                      //Insert into good element
                      let textToInsert = downtimePercent ? downtimePercent + ' % ' : '-- % ';
                      textToInsert += "of Unplanned Downtime";
                      document.getElementById(type + '-percent').innerText = textToInsert;
                  }

                });
            });
          },

          resolveAfter: function (milliseconds) {
                return new Promise(resolve => {
                    setTimeout(() => resolve(), milliseconds);
                });
          },

          createCharts: function () {
              for (let type of ['cip', 'cov', 'bnc']) {
                  const chartName = type + '-chart'
                  this.chartObjects[type] = new Chart(chartName, {
                    type: 'bar',
                    data: {
                      labels: this.months,
                      datasets: [{
                          label: type.toUpperCase(),
                          data: [],
                          backgroundColor: 'rgb(112, 184, 232)'
                      }]
                    },
                    options: {
                      responsive: true,
                      maintainAspectRatio: false,
                      plugins: {
                          title: {
                            display: true,
                            text: this.unplannedDowntimesCategories[type]
                        }
                      }
                    }
                    });
              }
              this.chartObjects.created = true;
          }

        },

        mounted() {
            this.$store.dispatch('fetchSites');

            //Load chart.js into vue component
            let chartJs = document.createElement('script');
            chartJs.setAttribute('src', 'https://cdn.jsdelivr.net/npm/chart.js');
            document.head.appendChild(chartJs);
        },

        computed: {
            ...mapGetters(['sites', 'unplannedDowntimeEvents'])
        },

        components: {
            ProductionWindow
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
        margin: 20px 0px;
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

    div.site-pl-selection {
        flex-direction: column;
        justify-content: space-evenly;
        min-width: 200px;
    }

    div.site-pl-selection > div{
        align-items: center;
    }

    div.site-pl-selection select {
        width: 100%;
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

    div.container-table td.table-data > tr {
        text-align: center;
    }

    div.main-chart-container {
        margin-top: 20px;
        display: flex;
        justify-content: center;
        height: 350px;
    }

    div.chart-container {
        width: 25% !important;
        height: 300px;
        margin: 0px 10px;
    }

    p.downtime-percent {
        text-align: center;
        margin: 10px 0px;
        font-size: 16px;
    }

    thead {
        color: white;
        background: #56baed;
    }

</style>
