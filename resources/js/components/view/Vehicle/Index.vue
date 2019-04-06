<template>
  <div class="container-fluid">
    <div class="row">
      <Header title="Kendaraan" back="/"/>
    </div>
    <div class="row vehicle-index justify-content-center">
      <div class="col-10 col-md-8">
        <form @submit.prevent="submitData">
          <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control">
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="text" class="form-control">
          </div>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import Header from "../../includes/Header";
import axios from "axios";

export default {
  name: "vehicleIndex",
  components: {
    Header
  },
  beforeCreate() {
    axios.get("api/v1/vehicle/list").then(res => {
      console.log("res beforecreate", res);
    });
  },
  data() {
    return {
      vehicle: {}
    };
  }
};
</script>