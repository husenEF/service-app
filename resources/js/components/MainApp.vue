<template>
    <div id="main">
        header
        <!-- <router-view></router-view> -->
        <div class="container-fluid">
            <div class="row">
                <template v-if="currentUser">
                    sidebar
                </template>
                <main role="main" v-bind:class="{'col-md-9 col-lg-10':isLogin}" class="col ml-sm-auto  px-4">
                    <router-view></router-view>
                </main>
            </div>
        </div>
    </div>
</template>

<script>
// import Header from "./includes/Header.vue";
// import Sidebar from "./includes/Sidebar.vue"

export default {
  name: "main-app",
  
  computed:{
      
  }
};
</script>
<style scoped>
[role="main"] {
  padding-top: 48px; /* Space for fixed navbar */
}

</style>
