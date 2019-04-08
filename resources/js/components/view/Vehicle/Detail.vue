<template>
  <h1>Detail - {{id}}</h1>
</template>

<script>
export default {
  name: "vehicleDetail",
  created() {
    const id = this.$route.params.id;
    this.id = id;
  },
  data() {
    return {
      id: 0
    };
  }
};
</script>
