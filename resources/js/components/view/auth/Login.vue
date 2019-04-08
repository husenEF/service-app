<template>
  <div class="container-fluid">
    <div class="login h-100 row justify-content-center">
      <div class="col-md-6 col-lg-4">
        <div class="card">
          <div class="card-header">Login</div>
          <div class="card-body">
            {{authError}}
            <form @submit.prevent="authenticate">
              <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" id="email" v-model="form.email">
              </div>
              <div class="form-group">
                <label for>Password</label>
                <input type="password" class="form-control" id="password" v-model="form.password">
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary">Login</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { login } from "../../../helpers/auth";

export default {
  name: "login",
  data() {
    return {
      form: {
        emai: "",
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
      console.log("fomr", this.$data);

      const send = {
        email: this.$data.form.email,
        password: this.$data.form.password
      };
      login(this.$data.form).then(res => {
        // console.log("res", res);
        if (res.success) {
          this.$store.commit("loginSuccess", res.data);
          this.$router.push({ path: "/" });
        } else {
          this.$store.commit("loginFailed", { error: res.message });
        }
      });
    }
  }
};
</script>
