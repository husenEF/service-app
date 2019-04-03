<template>
  <div id="main-app" class="container-fluid">
    <Header/>
    <div class="container-fluid" id="main-content">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import Header from "./includes/Header.vue";
// import Sidebar from "./includes/Sidebar.vue"

export default {
  name: "main-app",
  computed: {},
  components: {
    Header
  }
};
</script>
<style scoped>
[role="main"] {
  padding-top: 48px; /* Space for fixed navbar */
}
</style>
