<template>
  <nav class="navbar fixed-sticky mb-5 w-100 main-nav">
    <h1 class="text-center w-100 h4 mb-0">
      <a href="#" @click="$router.go(-1)" class="float-left btn btn-sm btn-default" v-if="isBack">
        <ArrowLeftIcon />
      </a>
      {{title}}
      <button
        class="btn btn-sm btn-default float-right"
        v-on:click="logout()"
        title="Logout"
      >
        <LogOutIcon />
      </button>
    </h1>
  </nav>
</template>

<script>
import { LogOutIcon, ArrowLeftIcon } from "vue-feather-icons";

export default {
  name: "topHeader",
  props: ["title", "back"],
  computed: {},
  components: {
    LogOutIcon,
    ArrowLeftIcon
  },
  created() {
    this.isBack = this.back !== "" ? true : false;
    this.url = this.back
    console.log("back",this.back)
  },
  mounted() {
    console.log("this", [this.isBack,this.back]);
  },
  data() {
    return {
      isBack: true,
      url:'/'
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
      this.$swal("Anda sudah keluar").then(val => {
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

