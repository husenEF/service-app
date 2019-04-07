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
            <tbody>
              <tr v-for="(item, index) in vehicle" :key="index">
                <td>{{item.merek}}</td>
                <td>{{item.platnumber}}</td>
                <td>{{Object.keys(item.tire).length}}</td>
                <td>
                  <router-link :to="`/vehicle/${item.id}`">Edit</router-link>
                </td>
              </tr>
            </tbody>
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
  beforeCreate() {
    // this.getList()
  },
  created() {
    this.getList();
  },
  methods: {
    getList() {
      axios.get("api/v1/vehicle/list").then(res => {
        const { data } = res;
        // console.log("the data", data);
        // console.log("res beforecreate", res);
        if (data.success == true) {
          let vehicleId = Object.keys(data.data).filter(i => i !== "meta");
          console.log("map vehicle", vehicleId);
          this.vehicle = vehicleId.map(i => data.data[i]);
          if (data.data.hasOwnProperty("meta")) {
            this.meta = data.data.meta;
          }
        } else {
          alert("error get list vehicle");
        }
      });
    }
  },
  data() {
    return {
      vehicle: {},
      meta: {}
    };
  }
};
</script>