<template>
  <div class="container-fluid">
    <div class="row">
      <Header title="Tes" back="/"/>
    </div>
    <div class="row current-user justify-content-center">
      <div class="col-8">
        <form >
          
        </form>
      </div>
    </div>
  </div>
</template>
<script>
import Header from "../../includes/Header.vue";
export default {
  name: "userIndex",
  mounted: {},
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
