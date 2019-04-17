<template>
  <div class="card">
    <div class="card-header">User Detail</div>
    <div class="card-body">
      <form @submit.prevent="submitData">
        <div class="form-group">
          <label>Name</label>
          <input type="text" class="form-control" v-model="user.name">
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" class="form-control" v-model="user.email">
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" class="form-control" v-model="user.password">
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
  name: "editUser",
  data() {
    return {
      user: {}
    };
  },
  created() {
    const { id } = this.$route.params;
    this.getUser(id);
    this.$emit("back", "/user");
  },
  methods: {
    getUser(id) {
      axios.get("/api/v1/user/detail/" + id).then(res => {
        const { data } = res;
        if (data.success) {
          this.user = data.data;
        }
      });
    },
    submitData() {
      axios.put("/api/v1/user/update/" + this.user.id, this.user).then(res => {
        console.log("res update", res);
      });
    }
  }
};
</script>
