<template>
  <div class="card">
    <div class="card-header">
      <h2>Detail Pengguna</h2>
    </div>
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
              <label>Nama</label>
              <input type="text" class="form-control" v-model="user.name">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Username</label>
              <input type="text" class="form-control" v-model="user.username" disabled>
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
          <div class="col-md-6" v-if="currentUser.roles=='admin'">
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
            <label class="form-check-label" for="checkbox">Aktif</label>
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
      user: {},
      error: {}
    };
  },
  created() {
    const { id } = this.$route.params;
    this.getUser(id);
    this.$emit("back", "/user");
  },
  computed: {
    currentUser() {
      return this.$store.getters.currentUser;
    }
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
      axios
        .put("/api/v1/user/update/" + this.user.id, this.user)
        .then(res => {
          const { data } = res;
          if (data.success) {
            this.$swal(data.message).then(val => window.location.reload());
          } else {
            this.error = res.response.data;
          }
        })
        .catch(err => {
          this.error = err.response.data;
        });
    }
  }
};
</script>
