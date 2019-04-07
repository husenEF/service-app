<template>
  <div class="container-fluid">
    <div class="row">
      <Header title="Kendaraan" back="/"/>
    </div>
    <div class="row vehicle-index justify-content-center">
      <div class="col-10 col-md-8">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Merek</th>
                <th>Plat</th>
                <th>Jumlah Ban</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
        </div>
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
  created() {
    this.getList();
    console.log("vehicle", this.vehicle);
  },
  methods: {
    getList() {
      axios.get("api/v1/vehicle/list").then(res => {
        // console.log("res beforecreate", res);
        if (res.status == 200) {
          this.vehicle = res.data.data;
          console.log("vehicle", res.data);
        } else {
          alert("error get list vehicle");
        }
      });
    }
  },
  data() {
    return {
      vehicle: {}
    };
  }
};
</script>