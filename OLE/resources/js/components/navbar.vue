<template>
    <nav class="navbar navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="images/icon.png" alt="" width="100" height="60">
            </a>

            <div class="d-flex">

                <select name="page" id="page" class="" v-model="selection">
                    <option value="packagingLineID">
                        Page 1
                    </option>

                    <option value="downtimesReport">
                        Page 2
                    </option>


                </select>

                <button class="btn btn-outline-success" type="button" v-on:click="nextPage()">
                    Ouvrir
                </button>

            </div>


        </div>
    </nav>
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

</style>
