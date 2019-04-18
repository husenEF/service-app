<template>
  <div class="card">
    <div class="card-header">Add New User</div>
    <div class="card-body">
      <div class="alert alert-danger" v-if="Object.keys(error).length>0">
        <p class="mb-0">
          <span v-for="(e,i) in error" :key="i" class="clearfix">{{e[0]}}</span>
        </p>
      </div>
      <form @submit.prevent="submitData">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Name</label>
              <input type="text" class="form-control" v-model="user.name">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Email</label>
              <input type="email" class="form-control" v-model="user.email">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Password</label>
              <input type="password" class="form-control" v-model="user.password">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Jabatan</label>
              <select v-model="user.roles" class="form-control">
                <option value="mekanik">Mekanik</option>
                <option value="admin">Admin</option>
              </select>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-check">
            <input type="checkbox" id="checkbox" v-model="user.active" class="form-check-input">
            <label class="form-check-label" for="checkbox">Active User</label>
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <router-link to="/user" class="btn btn-secondary float-right">Back</router-link>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: "userList",
  created() {
    this.$emit("back", "/user");
  },
  data() {
    return {
      user: {
        name: "",
        email: "",
        roles: "mekanik",
        active: false
      },
      error: {}
    };
  },
  methods: {
    submitData() {
      axios
        .post("/api/v1/user/create", this.user)
        .then(res => {
          const { data } = res;
          if (data.success) {
            this.$swal(data.message).then(val => {
              this.$router.push("/user");
            });
          }
        })
        .catch(err => {
          this.error = err.response.data;
        });
    }
  }
};
</script>
