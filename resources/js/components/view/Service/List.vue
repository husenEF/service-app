<template>
  <div class="card">
    <div class="card-header">Daftar Pengecekkan Ban</div>
    <div class="card-body"></div>
  </div>
</template>

<script>
export default {
  name: "serviceList",
  data() {
    return {
      service: {}
    };
  },
  created() {
    this.getList("/api/v1/service");
  },
  methods: {
    getList(link) {
      axios
        .get(link)
        .then(res => {
          console.log("res", res);
          // const {data} = res
        })
        .catch(err => {
          console.log(err);
        });
    }
  }
};
</script>
