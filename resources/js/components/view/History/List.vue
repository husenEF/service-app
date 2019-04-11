<template>
  <h2>history list</h2>
</template>

<script>
export default {
  name: "historyList",
  created() {
    this.getList();
  },
  data() {
    return {
      list: {}
    };
  },
  methods: {
    getList() {
      axios.get("/api/v1/history/list").then(res => {
        console.log("ress", res.data);
      });
    }
  }
};
</script>
