<template>
    <div class="nv">

        <nav class="navbar">
            <a class="navbar-brand" v-bind:href="url + 'packagingLineID' ">
                <img src="images/icon.png" alt="" width="200" height="130">
            </a>

            <div class="d-flex">

                <select name="page" id="page" class="" v-model="selection">
                    <option value="packagingLineID">
                        Packaging line ID
                    </option>

                    <option value="downtimesReport">
                        Downtimes report
                    </option>

                    <option value="monthlyLoadFactor">
                        Monthly load factor
                    </option>

                    <option value="productionDashboard">
                        Production dashboard
                    </option>

                    <option value="overallLineEffectivness">
                        Overall Line Effectivness
                    </option>


                </select>

                <button class="btn btn-outline-success" type="button" v-on:click="nextPage()">
                    Ouvrir
                </button>

            </div>

        </nav>
    </div>

</template>

<script>
    export default {
        name: "navbar",

        data() {
            return {
                selection: 'packagingLineID',
                url: sessionStorage.getItem("url"),

            }
        },

        methods: {
            nextPage: function () {
                console.log(this.url + this.selection);
                window.location.href = this.url + this.selection;
            },
        },
    }
</script>

<style scoped>

    .nv {
        background: white;
        width: 100%;
    }
</style>
