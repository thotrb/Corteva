<template>
    <!-- Production window -->
    <div class="d-flex production-window">
        <div class="d-flex title">
            <span>Production Window</span>
        </div>
        <div class="d-flex interval-selection">
            <span>From</span>
            <select id="select-year-from" v-on:change="calculateYearsAfterFrom(); yearSelected();">
                <template v-for="year of years">
                    <option v-bind:key="year" v-bind:value="year">{{year}}</option>
                </template>
            </select>
            <span>to</span>
            <select id="select-year-to" v-on:change="yearSelected();">
                <template v-for="year of yearsAfterFrom">
                    <option v-if="year == currentYear" :key="year" selected>{{year}}</option>
                    <option v-else :key="year">{{year}}</option>
                </template>
            </select>
        </div>
    </div>
</template>

<script>
export default {
    name: "productionWindow",

    data() {
        var data = {
            months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            years: [],
            yearsAfterFrom: [],
            currentYear: (new Date()).getFullYear(),
            startYear: 2000
        }

        //Populate years array
        for (let year = data.startYear; year <= data.currentYear; year++) data.years.push(year);
        data.yearsAfterFrom = data.years;

        return data;
    },

    methods: {
        calculateYearsAfterFrom: function () {
            const selectedYear = parseInt(document.getElementById('select-year-from').value);
            this.yearsAfterFrom = [];
            for (let i = selectedYear; i <= this.currentYear; i++) this.yearsAfterFrom.push(i);
        },

        yearSelected: function () {
            const dateFrom = document.getElementById('select-year-from').value;
            const dateTo = document.getElementById('select-year-to').value;
            this.yearSelectedFunction(dateFrom, dateTo);
        },

        showMenu: function () {
            document.querySelector("div.production-window").style.visibility = "";
        }
    },

    mounted() {

    },

    computed: {

    },

    props: ['yearSelectedFunction']
}
</script>
<style scoped>
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
</style>
