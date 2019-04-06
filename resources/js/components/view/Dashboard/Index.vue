<template>
  <div class="container-fluid">
    <div class="row">
      <Header title="Dashboard" back=""/>
    </div>
    <div class="row">
      <div class="col-6">
        <h1 class="text-center">
          <router-link to="/user">Profile</router-link>
        </h1>
      </div>
      <div class="col-6">
        <h1 class="text-center">Kendaraan</h1>
      </div>
      <div class="col-6">
        <h1 class="text-center">Ban</h1>
      </div>
      <div class="col-6">
        <h1 class="text-center">Histori Ban</h1>
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
