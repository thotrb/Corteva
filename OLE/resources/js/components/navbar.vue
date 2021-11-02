<template>
    <div class="nv">

        <nav class="navbar">
            <a class="navbar-brand" v-bind:href="url + 'packagingLineID' ">
                <img src="images/icon.png" alt="" width="200" height="130">
            </a>

            <div class="d-flex">

                <button class="btn btn-outline-success" type="button" v-on:click="swapLanguage('fr')">
                    FR
                </button>
                <button class="btn btn-outline-success" type="button" v-on:click="swapLanguage('en')">
                    EN
                </button>

                <select name="page" id="page" class="" v-model="selection">
                    <option value="packagingLineID">
                        {{$t("packagingLineID")}}
                    </option>

                    <option value="downtimesReport">
                        {{$t("downtimesReport")}}
                    </option>

                    <option value="monthlyLoadFactor">
                        {{$t("monthlyLoadFactor")}}
                    </option>

                    <option value="productionDashboard">
                        {{$t("productionDashboard")}}
                    </option>

                    <option value="overallLineEffectivness">
                        Overall Line Effectivness
                    </option>


                </select>

                <button class="btn btn-outline-success" type="button" v-on:click="nextPage()">
                    {{$t("open")}}
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

            swapLanguage : function(language)
            {
                this.$i18n.locale = language;
            }
        },
    }
</script>

<style scoped>

    .nv {
        background: white;
        width: 100%;
    }
</style>
