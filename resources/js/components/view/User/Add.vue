<template>
  <div class="card">
    <div class="card-header">Add New User</div>
    <div class="card-body">
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
      }
    };
  },
  methods: {
    submitData() {
      axios
        .post("/api/v1/user/create", this.user)
        .then(res => {
          console.log("res", res);
        })
        .catch(err => {
          console.log("err", err);
        });
    }
  }
};
</script>
