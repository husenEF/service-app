<template>
  <div class="card">
    <div class="card-header">
      <h2>
        Daftar Kendaraan
        <router-link to="/vehicle/add" class="btn btn-info float-right btn-sm">
          <PlusIcon/>Tambah Kendaraan
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
                    <label for>Filter</label>
                    <select class="form-control" required v-model="filter.key" name="key">
                      <option value>Pilih</option>
                      <option value="merek">merek</option>
                      <option value="platnumber">plat nomor</option>
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
              <th>##</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in vehicle" :key="index">
              <td>
                <router-link :to="`/vehicle/${item.id}`">{{item.merek}}</router-link>
              </td>
              <td>{{item.platnumber}}</td>
              <td>
                {{Object.keys(item.tire).length}}
                <router-link :to="`/vehicle/tire/${item.id}`">Lihat Ban</router-link>
              </td>
              <td>{{item.size}}</td>
              <td>
                <router-link :to="`/vehicle/edit/${item.id}`">Edit</router-link>
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

@media only screen and (max-width: 760px),
  (min-device-width: 768px) and (max-device-width: 1024px) {
  /* Force table to not be like tables anymore */
  table,
  thead,
  tbody,
  th,
  td,
  tr {
    display: block;
  }

  thead {
    tr {
      position: absolute;
      top: -9999px;
      left: -9999px;
    }
  }
  tbody {
    tr {
      border-left: 1px #eee solid;
      border-right: 1px #eee solid;
      &:nth-child(odd) {
        background-color: #dedede;
      }
    }
    td {
      /* Behave  like a "row" */
      border: none;
      border-bottom: 1px solid #eee;
      position: relative;
      padding-left: 50%;
      &::before {
        /* Now like a table header */
        position: absolute;
        /* Top/left values mimic padding */
        top: 6px;
        left: 6px;
        width: 45%;
        padding-right: 10px;
        white-space: nowrap;
      }
    }
  }

  td:nth-of-type(1):before {
    content: "Merek";
  }
  td:nth-of-type(2):before {
    content: "Plat Nomor";
  }
  td:nth-of-type(3):before {
    content: "Jumlah Ban";
  }
  td:nth-of-type(4):before {
    content: "UKuran";
  }
  td:nth-of-type(5):before {
    content: "";
  }
}
</style>