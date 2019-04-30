<template>
  <nav class="navbar fixed-sticky mb-5 w-100 main-nav">
    <h1 class="text-center w-100">
      <router-link :to="{path:back}" class="float-left" v-if="isBack">Kembali</router-link>
      <router-link to="/">{{title}}</router-link>
      <button class="btn btn-sm btn-danger float-right" v-on:click="logout()" title="Logout">
        <LogOutIcon/>
      </button>
    </h1>
  </nav>
</template>

<script>
import { LogOutIcon } from "vue-feather-icons";

export default {
  name: "header",
  props: ["title", "back"],
  computed: {},
  components: {
    LogOutIcon
  },
  created() {
    this.isBack = this.back !== "" ? true : false;
  },
  mounted() {
    // console.log("this", this.isBack);
  },
  data() {
    return {
      isBack: true
    };
  },
  computed: {
    user() {
      return this.$store.getters.currentUser;
    }
  },
  methods: {
    logout() {
      this.$store.commit("logout");
      this.$swal("Anda sudah Logout").then(val => {
        this.$router.push("/login");
      });
    }
  }
};
</script>

<style lang="scss" scoped>
.main-nav {
  background-color: #dddddd;
}
</style>

