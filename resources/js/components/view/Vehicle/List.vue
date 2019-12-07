<template>
  <div class="card">
    <div class="card-header">
      <h2>
        Daftar Kendaraan
        <router-link to="/vehicle/add" class="btn btn-info float-right btn-sm">
          <PlusIcon />Tambah Kendaraan
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
        <div class="btn-group mb-2">
          <button
            class="btn btn-info"
            type="button"
            data-toggle="collapse"
            data-target="#collapseExample"
            aria-expanded="false"
            aria-controls="collapseExample"
          >
            Filter
            <FilterIcon />
          </button>
          <button class="btn btn-success" title="Cetak Data" type="button" v-on:click="printData">
            <PrinterIcon />
          </button>
          <a href="/export/kendaraan" target="blank" class="btn btn-info" title="Cetak data 2">
            <PrinterIcon />
          </a>
        </div>
        <div class="collapse mb-3" id="collapseExample">
          <div class="card card-body">
            <form @submit.prevent="filterHandler">
              <div class="row">
                <div class="col-md-5">
                  <div class="form-group">
                    <label for>Filter</label>
                    <select class="form-control" required v-model="filter.key" name="key">
                      <option value>Pilih</option>
                      <option value="merek">Merek</option>
                      <option value="platnumber">Plat nomor</option>
                      <option value="size">Ukuran Ban</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <label for>Kata Kunci</label>
                    <input required type="text" class="form-control" v-model="filter.value" />
                  </div>
                </div>
                <div class="col-md-2">
                  <button class="btn btn-primary btn-block" type="submit">
                    <SearchIcon />
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
              <th>##</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in vehicle" :key="index">
              <td title="Merek">
                <router-link :to="`/vehicle/${item.id}`">
                  <span>{{item.merek}}</span>
                </router-link>
              </td>
              <td title="Plat">
                <span>{{item.platnumber}}</span>
              </td>
              <td title="Jumlah Ban">
                <span>
                  {{Object.keys(item.tire).length}}
                  <router-link :to="`/vehicle/tire/${item.id}`">Lihat Ban</router-link>
                </span>
              </td>
              <td title="Ukuran">
                <span>{{item.size}}</span>
              </td>
              <td title="Action">
                <router-link :to="`/vehicle/edit/${item.id}`">
                  <span>Edit</span>
                </router-link>
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
import {
  PlusIcon,
  SearchIcon,
  FilterIcon,
  PrinterIcon
} from "vue-feather-icons";

export default {
  name: "vehicleIndex",
  components: {
    PlusIcon,
    SearchIcon,
    FilterIcon,
    PrinterIcon
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
    },
    printData() {
      const { token } = this.user;
      const newCode = window.btoa(token);
      // console.log("user", [newCode, token]);
      window.open("api/v1/vehicle/download/" + newCode, "_blank");
      // axios
      //   .get("api/v1/vehicle/download/" + newCode)
      //   .then(res => {
      //     console.log("res", res);
      //   })
      //   .catch(err => {
      //     console.log("err", err);
      //   });
    }
  },
  computed: {
    user() {
      return this.$store.getters.currentUser;
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