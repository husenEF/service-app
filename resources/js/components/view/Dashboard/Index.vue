<template>
  <div class="container-fluid">
    <div class="row">
      <Header title="Dashboard" back/>
    </div>
    <div class="row">
      <div class="col-6">
        <h1 class="text-center">
          <router-link to="/user">Pengguna</router-link>
        </h1>
      </div>
      <div class="col-6">
        <h1 class="text-center">
          <router-link to="/vehicle">Kendaraan</router-link>
        </h1>
      </div>
      <div class="col-6">
        <h1 class="text-center">
          <router-link to="/ban">Ban</router-link>
        </h1>
      </div>
      <div class="col-6">
        <h1 class="text-center">
          <router-link to="/service">Riwayat Pengecekkan</router-link>
        </h1>
      </div>
    </div>
  </div>
</template>

<script>
import Header from "../../includes/Header.vue";

export default {
  name: "dashboard-index",
  components: {
    Header
  },
  created() {
    // alert('a')
    // this.check();
  },
  methods: {
    check() {
      const { id, token } = this.user;
      axios
        .post("/api/v1/user/check", { id, token })
        .then(res => {
          console.log("res", res);
        })
        .catch(err => {
          console.log(err.response.data);
        });
    }
  },
  computed: {
    user() {
      return this.$store.getters.currentUser;
    }
  }
};
</script>
