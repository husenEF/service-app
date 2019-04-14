<template>
  <div class="card">
    <div class="card-header">
      <h2>
        Daftar Kendaraan
        <router-link to="/vehicle/add" class="btn btn-info float-right btn-sm"><PlusIcon/></router-link>
      </h2>
    </div>
    <div class="card-body">
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
    <div class="card-footer">
      <nav aria-label="Page navigation example" v-if="meta.pagination.total_pages>1">
        <ul class="pagination mb-0">
          <li v-for="(link,i) in meta.pagination.links" :key="i" class="page-item">
            <button type="button " class="page-link" v-on:click="updateLink(link)">{{i}}</button>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script>
import { PlusIcon } from "vue-feather-icons";

export default {
  name: "vehicleIndex",
  components: {
    PlusIcon
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
    },
    updateLink(link) {
      axios.get(link).then(res => {
        if (res.data.success) {
          const listId = Object.keys(res.data.data).filter(e => e !== "meta");
          this.vehicle = listId.map(e => res.data.data[e]);
          this.meta = res.data.data.meta;
        }
      });
      return false;
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