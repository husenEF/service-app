<template>
  <div id="main-app">
      <div class="clearfix header">
          <h1>Header</h1>
      </div>
      <div class="content">
          <router-view></router-view>
      </div>
  </div>
</template>

<script>
// import Header from "./includes/Header.vue";
// import Sidebar from "./includes/Sidebar.vue"

export default {
  name: "main-app",
  computed: {}
};
</script>
<style scoped>
[role="main"] {
  padding-top: 48px; /* Space for fixed navbar */
}
</style>
