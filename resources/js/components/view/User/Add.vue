<template>
  <div class="card">
    <div class="card-header"></div>
    <div class="card-body"></div>
  </div>
</template>

<script>
export default {
  name: "userList",
  created() {
    this.$emit("back", "/user");
  },
  methods:{

  }
};
</script>
