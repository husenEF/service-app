<template>
  <div class="login row justify-content-center">
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">Login</div>
        <div class="card-body">
          <form @submit.prevent="authenticate">
            <div class="form-group">
              <label for="email">Email</label>
              <input type="text" class="form-control" id="email">
            </div>
            <div class="form-group">
              <label for>Password</label>
              <input type="password" class="form-control" id="password">
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary">Login</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { login } from "../../../helpers/auth";

export default {
  name: "login",
  methods: {
    authenticate() {
      this.$store.dispatch("login");

      login(this.$data.form).then(res => {
        console.log("res", res);
      });
    }
  }
};
</script>
