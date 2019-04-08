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

    <h3>
      Ban
      <button type="button" class="btn btn-info float-right" v-on:click="addBan()">+ Ban</button>
    </h3>
    <fieldset v-for="(tire, index) in data.tires" :key="index" class="border pl-3 pr-3 pb-3 mb-3">
      <legend class="w-auto">Tire {{index +1}}:</legend>
      <div class="row">
        <div class="col-md-5">
          <input type="text" class="form-control" placeholder="Merek Ban" v-model="tire.merek">
        </div>
        <div class="col-md-5">
          <input
            type="text"
            class="form-control"
            placeholder="Tanggal Beli"
            v-model="tire.buy_date"
          >
        </div>
        <div class="col-2">
          <button type="button" class="btn btn-danger btn-block">X</button>
        </div>
      </div>
    </fieldset>
    <button type="submit" class="btn btn-primary">Update</button>this
    <pre>{{data.tires}}</pre>
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
    },
    addBan() {
      const pos = this.data.tirescount + 1;
      const newBan = {
        merek: "",
        buy_date: "",
        position: pos,
        id: 0
      };
      let { tires } = this.data;
      tires.push(newBan);

      console.log("tire", tires);
    }
  }
};
</script>
