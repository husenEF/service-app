<template>
    <div class="container-fluid">
        <div class="row">
            <Header title="Dasbor" back/>
        </div>
        <div class="row">
            <div class="col-6 text-center mb-5">
                <router-link to="/user" v-if="user.roles=='admin'">
                    <img src="/images/user.svg" alt="Ikon Pengguna" class="icon"/>
                    <br />Pengguna
                </router-link>
                <router-link to="/my-account" v-else>
                    <img src="/images/user.svg" alt="Profilku" title="Profilku" class="icon">
                    <br />Akun Saya
                </router-link>
            </div>
            <div class="col-6 text-center">
                <router-link to="/vehicle">
                    <img src="/images/bus.svg" alt="Ikon Kendaraan" class="icon"/>
                    <br />Kendaraan
                </router-link>
            </div>
            <div class="col-6 text-center">
                <router-link to="/ban">
                    <img src="/images/wheel.svg" alt="Ikon Ban" class="icon"/>
                    <br />Ban
                </router-link>
            </div>
            <div class="col-6 text-center">
                <router-link to="/service">
                    <img src="/images/notes.svg" alt="Ikon riwayat" class="icon"/>
                    <br />Riwayat
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
    import Header from "../../includes/Header.vue"

    export default {
        name: "dashboard-index",
        data() {
            return {
                
            }
        },
        components: {
            Header,
        },
        created() {
           // console.log("user",this.user)
            // alert('a')
            // this.check();
        },
        methods: {
            check() {
                const {id, token} = this.user;
                axios
                    .post("/api/v1/user/check", {id, token})
                    .then(res => {
                        // console.log("res", res);
                    })
                    .catch(err => {
                        // console.log(err.response.data);
                    });
            }
        },
        computed: {
            user() {
                return this.$store.getters.currentUser;
            },
            path() {
                return this.$store.getters.getImagePath;
            }
        }
    };
</script>

<style>
    .icon {
        width: 52px;
        height: 52px;
        margin-bottom: 15px;
    }
</style>
