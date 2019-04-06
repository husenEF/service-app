<template>
  <div id="main-app" class="clearfix">
    <router-view></router-view>
  </div>
</template>

<script>
// import Header from "./includes/Header.vue";
// import Sidebar from "./includes/Sidebar.vue"

export default {
  name: "main-app",
  computed: {},
  components: {
    // Header
  }
};
</script>
<style scoped>
[role="main"] {
  padding-top: 48px; /* Space for fixed navbar */
}
</style>
