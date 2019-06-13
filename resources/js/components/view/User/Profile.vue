<template>
  <div class="container-fluid">
    <div class="row">
      <Header title="User Profile" back="/"/>
    </div>
    <div class="row current-user justify-content-center">
      <div class="col-12 col-md-10">
        <div class="alert alert-danger" v-if="error">
          <p class="mb-0">
            <span v-for="(e,i) in error" :key="i">{{e[0]}}</span>
          </p>
        </div>
        <div class="card">
          <div class="card-header">
            <h2>Aku Saya</h2>
          </div>
          <div class="card-body">
            <div class="table-responsive" v-if="!edit">
              <table class="table">
                <tbody>
                  <tr>
                    <th>Nama</th>
                    <td>{{user.name}}</td>
                  </tr>
                  <tr>
                    <th>Username</th>
                    <td>{{user.username}}</td>
                  </tr>
                  <tr>
                    <th>Jabatan</th>
                    <td>{{user.roles}}</td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <button class="btn btn-sm btn-primary" v-on:click="editToogle">Edit</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <form @submit.prevent="submitData" v-else>
              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" class="form-control" v-model="user.name">
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control" v-model="user.password">
              </div>
              <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
              <button type="submit" class="btn btn-info btn-sm" v-on:click="editToogle">Batal</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Header from "../../includes/Header.vue";

export default {
  name: "userProfile",
  methods: {
    submitData() {
      axios
        .put("/api/v1/user/update/" + this.user.id, this.user)
        .then(res => {
          if (res.data.success) {
            this.$store.commit("updateUser", res.data);
            this.$swal({
              title: " Berhasil",
              text: "Rubah data berhasil",
              type: "success"
            }).then(() => {
              this.edit = !this.edit;
            });
          }
        })
        .catch(err => {
          this.error = err.response.data;
        });
    },
    editToogle() {
      this.edit = !this.edit;
    }
  },
  data() {
    return {
      edit: false,
      error: null
    };
  },
  components: {
    Header
  },
  computed: {
    user() {
      return this.$store.getters.currentUser;
    }
  }
};
</script>
