<template>
    <div class="nv">

        <nav class="navbar">
            <img src="images/icon.png" alt="" width="100" height="50">

            <div class="d-flex">

                <button class="btn btn-outline-success" type="button" v-on:click="swapLanguage('fr')">
                    FR
                </button>
                <button class="btn btn-outline-success" type="button" v-on:click="swapLanguage('en')">
                    EN
                </button>

            </div>

        </nav>


    </div>

</template>

<script>
    export default {
        name: "navbarSaisie",

        data() {
            return {}
        },

        methods: {

            swapLanguage: function (language) {
                this.$i18n.locale = language;
                if(sessionStorage.getItem("language") === null){
                    sessionStorage.language = language;
                }else{
                    sessionStorage.setItem("language",language);
                }
            }
        },
    }
</script>

<style scoped>

    .nv {
        background: lightblue;
        width: 100%;
    }
</style>
