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
          <!-- <input
            type="text"
            class="form-control"
            placeholder="Tanggal Beli"
            v-model="tire.buy_date"
          >-->
          <Datetime v-model="tire.buy_date" input-class="form-control"></Datetime>
        </div>
        <div class="col-2">
          <button type="button" class="btn btn-danger btn-block">X</button>
        </div>
      </div>
    </fieldset>
    <button type="submit" class="btn btn-primary">Update</button>
  </form>
</template>

<script>
import { Datetime } from "vue-datetime";
// You need a specific loader for CSS files
import "vue-datetime/dist/vue-datetime.css";

export default {
  name: "vehicleDetail",
  components: {
    Datetime
  },
  created() {
    const id = this.$route.params.id;
    this.getDetail(id);
  },
  computed: {
    getUser() {
      return this.$store.getters.currentUser;
    }
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
      const userId = this.getUser.id;
      const { tires, id } = this.data;
      const err = tires.filter(e => e.merek == "" || e.buy_date == "");
      if (err.length) {
        alert("Data Harus terisi");
        return false;
      }

      this.data.session_user = userId
      axios.put("api/v1/vehicle/" + id, this.data).then(res => {
        console.log("update", res);
      });
    },
    addBan() {
      const { id } = this.getUser;
      const pos = this.data.tirescount + 1;
      let { tires } = this.data;
      const newBan = {
        merek: "",
        buy_date: "",
        position: pos,
        id: 0,
        created_by: id,
        id_vehicle: this.data.id
      };

      tires.push(newBan);

      console.log("tire", tires);
    }
  }
};
</script>
