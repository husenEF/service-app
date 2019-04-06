<template>
  <nav class="navbar fixed-sticky mb-5 w-100 main-nav">
    <h1 class="text-center w-100">
      <router-link :to="{path:back}" class="float-left">Back</router-link>
      {{title}}
    </h1>
  </nav>
</template>

<script>
export default {
  name: "header",
  props:['title','back'],
  computed: {}
};
</script>

<style lang="scss" scoped>
.main-nav{
  background-color: #dddddd
}
</style>

