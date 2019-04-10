<template>
  <form @submit.prevent="submitData">
    <div class="form-group">
      <label for>Merek</label>
      <input type="text" class="form-control" v-model="master.merek">
    </div>
    <div class="form-group">
      <label for>Plat</label>
      <input type="text" class="form-control" v-model="master.platnumber">
    </div>

    <h3>
      Ban
      <button type="button" class="btn btn-info float-right" v-on:click="addBan()">+ Ban</button>
    </h3>
    
    <fieldset v-for="(tire, index) in master.tire" :key="index" class="border pl-3 pr-3 pb-3 mb-3">
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
          <button type="button" class="btn btn-danger btn-block" v-on:click="deletBan(tire.id)">X</button>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          
        </div>
        <div class="col-md-6">
          <img v-bind:src="tire.images_path" class="img-responsive" /> 
          <!-- <img data-original="{{tire.images_path}}" alt="" class="img-responsive"> -->
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
      master: {}
    };
  },
  methods: {
    getDetail(id) {
      axios.get("/api/v1/vehicle/" + id).then(res => {
        const { data } = res;
        if (data.success) {
          this.master = data.data;
          this.master.tirescount = Object.keys(data.data.tires).length;
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

      this.data.session_user = userId;
      axios.put("api/v1/vehicle/" + id, this.data).then(res => {
        console.log("update", res);
      });
    },
    addBan() {
      const { id } = this.getUser;
      const pos = this.master.tirescount + 1;
      let { tires } = this.master;
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
    },
    deletBan(id) {
      this.$swal({
        title: "Anda Yakin?",
        text: "Anda akan menghapus data ini?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Hapus!"
      }).then(res => {
        if (res.value) {
          axios.delete("/api/v1/tire/" + id).then(res => {
            console.log("res", res);
          });
        } else {
          console.log("cancel");
        }
      });
    }
  }
};
</script>
