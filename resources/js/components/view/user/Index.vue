<template>
  <div class="row">
    <div class="col-12">
      <pre>
                {{user}}
            </pre>
    </div>
  </div>
</template>

<script>
export default {
  name: "userIndex",
  mounted() {
    console.log("userindex", "mounted");
  },
  computed: {
    user() {
      return this.$store.getters.currentUser;
    }
  }
};
</script>