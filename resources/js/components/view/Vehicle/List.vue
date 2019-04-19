<template>
  <div class="card">
    <div class="card-header">
      <h2>
        Daftar Kendaraan
        <router-link to="/vehicle/add" class="btn btn-info float-right btn-sm">
          <PlusIcon/>
        </router-link>
      </h2>
    </div>
    <div class="card-body">
      <div class="alert alert-danger" v-if="error!==''">
        <p class="mb-0">
          <span>{{error}}</span>
        </p>
      </div>
      <div class="clearfix filter">
        <p>
          <button
            class="btn btn-info"
            type="button"
            data-toggle="collapse"
            data-target="#collapseExample"
            aria-expanded="false"
            aria-controls="collapseExample"
          >
            Filter Data
            <FilterIcon/>
          </button>
        </p>
        <div class="collapse" id="collapseExample">
          <div class="card card-body">
            <form @submit.prevent="filterHandler">
              <div class="row">
                <div class="col-md-5">
                  <div class="form-group">
                    <label for>Filter By</label>
                    <select class="form-control" required v-model="filter.key" name="key">
                      <option value>Pilih</option>
                      <option value="merek">merek</option>
                      <option value="platnumber">platnumber</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <label for>Kata Kunci</label>
                    <input required type="text" class="form-control" v-model="filter.value">
                  </div>
                </div>
                <div class="col-md-2">
                  <button class="btn btn-primary btn-block" type="submit">
                    <SearchIcon/>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Merek</th>
              <th>Plat</th>
              <th>Jumlah Ban</th>
              <th>Ukuran</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in vehicle" :key="index">
              <td>{{item.merek}}</td>
              <td>{{item.platnumber}}</td>
              <td>{{Object.keys(item.tire).length}}</td>
              <td>{{item.size}}</td>
              <td>
                <router-link :to="`/vehicle/${item.id}`">Edit</router-link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer" v-if="typeof meta.pagination!=='undefined'">
      <nav aria-label="Page navigation example" v-if="(meta.pagination.total_pages)>1">
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
import { PlusIcon, SearchIcon, FilterIcon } from "vue-feather-icons";

export default {
  name: "vehicleIndex",
  components: {
    PlusIcon,
    SearchIcon,
    FilterIcon
  },
  beforeCreate() {
    // this.getList()
  },
  created() {
    this.getList();
    this.$emit("back", "/");
  },
  methods: {
    getList() {
      axios.get("api/v1/vehicle/list").then(res => {
        const { data } = res;
        // console.log("the data", data);
        // console.log("res beforecreate", res);
        if (data.success == true) {
          let vehicleId = Object.keys(data.data).filter(i => i !== "meta");
          // console.log("map vehicle", vehicleId);
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
    },
    filterHandler() {
      axios
        .post("/api/v1/vehicle/filter", this.filter)
        .then(res => {
          const { data } = res;
          // console.log("the data", data);
          // console.log("res beforecreate", res);
          if (data.success == true) {
            let vehicleId = Object.keys(data.data).filter(i => i !== "meta");
            // console.log("map vehicle", vehicleId);
            this.vehicle = vehicleId.map(i => data.data[i]);
            if (data.data.hasOwnProperty("meta")) {
              this.meta = data.data.meta;
            }
          }
        })
        .catch(err => {
          this.error = err.response.data.message;
        });
    }
  },
  data() {
    return {
      vehicle: {},
      meta: {},
      filter: {
        key: "",
        value: ""
      },
      error: ""
    };
  }
};
</script>

<style lang="scss" scoped>
.filter {
  form {
    button {
      margin-top: 28px;
    }
  }
}
</style>