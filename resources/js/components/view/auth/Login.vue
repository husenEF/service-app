<template>
    <div class="container-fluid">
        <div class="login h-100 row justify-content-center">
            <div class="col-sm-8 col-md-6 col-lg-4">
                <h1 class="mt-5">Login</h1>
                <div class="card">
                    <div class="card-body">
                        {{authError}}
                        <form @submit.prevent="authenticate">
                            <div class="form-group">
                                <label for="username">Nama Pengguna</label>
                                <input
                                    type="text"
                                    name="username"
                                    class="form-control"
                                    id="username"
                                    v-model="form.username"
                                    autofocus
                                >
                            </div>
                            <div class="form-group">
                                <label for="password">Kata Sandi</label>
                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    id="password"
                                    v-model="form.password"
                                >
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Masuk Log</button>
                            </div>
                        </form>
                    </div><!-- .card-body -->
                </div><!-- .card -->
            </div><!-- .col-* -->
        </div><!-- .row -->
    </div><!-- .container-fluid -->
</template>

<script>
    import {login} from "../../../helpers/auth";

    export default {
        name: "login",
        data() {
            return {
                form: {
                    username: "",
                    password: ""
                }
            };
        },
        computed: {
            authError() {
                return this.$store.getters.authError;
            }
        },
        methods: {
            authenticate() {
                this.$store.dispatch("login");

                const send = {
                    username: this.form.username,
                    password: this.form.password
                };

                // As promises need to do after succeed and failed so this part need to have catch
                // to show whatever error that will come.
                login(this.$data.form).then(res => {
                    // console.log("res", res);
                    if (res.success) {
                        this.$store.commit("loginSuccess", res.data);
                        this.$router.go("/");
                        // this.$router.push({ path: "/" });
                    } else {
                        this.$store.commit("loginFailed", {error: res.message});
                    }
                }).catch( (err) =>{
                    this.$store.commit("loginFailed", {error: err});
                });
            }
        }
    };
</script>
