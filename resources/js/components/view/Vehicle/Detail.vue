<template>
  <form @submit.prevent="submitData">
    <div class="form-group">
      <label for>Merek</label>
      <input type="text" class="form-control" v-model="data.merek">
    </div>
    <div class="form-group">
      <label for>Plat</label>
      <input type="text" class="form-control" v-model="data.platnumber">
    </div>
    <div class="form-group">
      <label for>Jumlah ban</label>
      <input type="number" class="form-control" v-model="data.tirescount" min="4" max="15">
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
  </form>
</template>

<script>
export default {
  name: "vehicleDetail",
  created() {
    const id = this.$route.params.id;
    this.getDetail(id);
  },
  data() {
    return {
      id: 0,
      data: {}
    };
  },
  methods: {
    getDetail(id) {
      axios.get("/api/v1/vehicle/" + id).then(res => {
        const { data } = res;
        if (data.success) {
          this.data = data.data;
          this.data.tirescount = Object.keys(data.data.tires).length;
        }
      });
    },
    submitData(e) {
      console.log("d", this.data);
    }
  }
};
</script>
