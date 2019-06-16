<template>
    <nav class="navbar fixed-sticky mb-5 w-100 main-nav">
        <h1 class="text-center w-100 h4 mb-0">
            <router-link :to="{path:back}" class="float-left btn btn-sm btn-default" v-if="isBack"><ArrowLeftIcon/></router-link>
            {{title}}
            <button class="btn btn-sm btn-default float-right" v-on:click="logout()" title="Logout">
                <LogOutIcon/>
            </button>
        </h1>
    </nav>
</template>

<script>
    import { LogOutIcon, ArrowLeftIcon } from "vue-feather-icons";

    export default {
        name: "header",
        props: ["title", "back"],
        computed: {},
        components: {
            LogOutIcon,
            ArrowLeftIcon
        },
        created() {
            this.isBack = this.back !== "" ? true : false;
        },
        mounted() {
            // console.log("this", this.isBack);
        },
        data() {
            return {
                isBack: true
            };
        },
        computed: {
            user() {
                return this.$store.getters.currentUser;
            }
        },
        methods: {
            logout() {
                this.$store.commit("logout");
                this.$swal("Anda sudah keluar").then(val => {
                    this.$router.push("/login");
                });
            }
        }
    };
</script>

<style lang="scss" scoped>
    .main-nav {
        background-color: #dddddd;
    }
</style>

