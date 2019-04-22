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
          <router-link to="/history">Riwayat</router-link>
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
  }
};
</script>
