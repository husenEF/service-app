<template>
  <div class="card">
    <div class="card-header">
      <h2>Daftar Ban</h2>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="row filter">
          <div class="col-md-6">
            <div class="btn-group mb-2">
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
              <a
                href="/export/ban"
                target="_blank"
                rel="noopener noreferrer"
                class="btn btn-warning"
              >
                <PrinterIcon/>
              </a>
              <router-link to="/ban/add" class="btn btn-success float-right">
                <PlusIcon/>Tambah Ban
              </router-link>
              <!-- <button type="button" class="btn btn-success float-right"><PlusIcon/> Tambah Ban</button> -->
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
                          <option value="ukuran">Ukuran</option>
                          <option value="stempel">Stempel</option>
                          <option value="datetime">Tanggal Beli</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-5">
                      <div class="form-group" v-if="filter.key !=='datetime'">
                        <label for>Kata Kunci</label>
                        <input
                          required
                          type="text"
                          class="form-control"
                          v-model="filter.value"
                          placeholder="Kata Kunci"
                          v-if="filter.key !=='datetime'"
                        >
                      </div>
                      <div class="form-group" v-else>
                        <label for>Pilih Tanggal</label>
                        <Datetime
                          v-model="filter.value"
                          input-class="form-control"
                          value-zone="Asia/Jakarta"
                        ></Datetime>
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
          <div class="col-md-6 text-md-right">
            <router-link to="/ban/trash">
              <Trash2Icon/>
            </router-link>
          </div>
        </div>
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Merek</th>
              <th>Ukuran</th>
              <th>Stempel</th>
              <th>Tanggal Beli</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(tire, i) in list" :key="i">
              <td>
                <span
                  v-if="typeof meta.pagination !=='undefined'"
                >{{(i+1)+(meta.pagination.per_page*(meta.pagination.current_page-1))}}</span>
                <span v-else>{{i+1}}</span>
              </td>
              <td>{{tire.merek}}</td>
              <td>{{tire.ukuran_ban}}</td>
              <td>
                {{tire.stempel_ban}}
                <!-- <pre>{{tire}}</pre> -->
                <!-- <img
                  :src="tire.images_path"
                  :alt="tire.merek"
                  class="img-fluid"
                  v-if="tire.images_path"
                >-->
              </td>
              <td>{{tire.buy_date}}</td>
              <td>
                <div class="btn-group" role="group" aria-label="Tire Button">
                  <router-link class="btn btn-info btn-sm" :to="'/ban/edit/'+tire.id">Edit</router-link>
                  <button
                    class="btn btn-danger btn-sm"
                    v-on:click="confirmAfkir(tire.id)"
                  >afkir/buang?</button>
                  <!-- <router-link
                    class="btn btn-info btn-sm"
                    :to="'/history/tires/'+tire.id"
                    title="Riwayat Ban"
                  >
                    <EyeIcon/>
                  </router-link>-->
                  <!-- <router-link
                    class="btn btn-primary btn-sm"
                    :to="'/history/position/'+tire.vehicle.id+`/`+tire.id"
                    title="Riwayat berdasarkan Posisi"
                  >
                    <EyeIcon/>
                  </router-link>-->
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer" v-if="typeof meta.pagination !=='undefined'">
      <nav aria-label="Page navigation example" v-if="(meta.pagination.total_pages)>1">
        <ul class="pagination mb-0">
          <li v-for="(link,i) in meta.pagination.links" :key="i" class="page-item">
            <button type="button " class="page-link" v-on:click="getList(link)">{{i}}</button>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script>
import {
  EyeIcon,
  SearchIcon,
  FilterIcon,
  PlusIcon,
  PrinterIcon,
  Trash2Icon
} from "vue-feather-icons";
import { Datetime } from "vue-datetime";
import "vue-datetime/dist/vue-datetime.css";
import { constants } from "crypto";

export default {
  name: "tireList",
  components: {
    EyeIcon,
    SearchIcon,
    FilterIcon,
    PlusIcon,
    PrinterIcon,
    Datetime,
    Trash2Icon
  },
  created() {
    this.getList("/api/v1/tire/list");
  },
  data() {
    return {
      list: {},
      meta: {},
      filter: {
        key: "",
        value: ""
      }
    };
  },
  methods: {
    getList(uri) {
      axios.get(uri).then(res => {
        const { data } = res;
        // console.log("d", data.data);
        if (data.success) {
          let tireId = Object.keys(data.data).filter(i => i !== "meta");
          // console.log("map tireId", tireId);
          this.list = tireId.map(i => data.data[i]);
          if (data.data.hasOwnProperty("meta")) {
            this.meta = data.data.meta;
          }
        }
      });
    },
    filterHandler() {
      axios.post("/api/v1/tire/filter", this.filter).then(res => {
        const { data } = res;
        if (data.success) {
          const ListId = Object.keys(data.data).filter(e => e !== "meta");
          this.list = ListId.map(e => data.data[e]);
          if (data.data.hasOwnProperty("meta")) {
            this.meta = data.data.meta;
          } else {
            this.meta = {};
          }
        }
      });
    },
    confirmAfkir(id) {
      this.$swal({
        title: "Anda Yakin?",
        text: "Yakin ban akan di Afkir/Buang?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya!"
      }).then(val => {
        if (val.value) {
          // console.log("meta",this.meta)
          axios
            .delete("/api/v1/tire/" + id)
            .then(res => {
              // console.log("res", res);
              this.$swal("Ban berhasil di Afkir/Buang").then(res => {
                const meta = this.meta;
                if (typeof meta.pagination !== "undefined") {
                  this.getList(
                    "/api/v1/tire/list?page=" + meta.pagination.current_page
                  );
                } else {
                  this.getList("/api/v1/tire/list");
                }
              });
            })
            .catch(error => {
              const res = error.response.data;
              this.$swal({
                title: res.message,
                text:
                  "Ban masih terpasang di kendaraan " +
                  res.data.get_vehicle.merek,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#5bc0de",
                cancelButtonColor: "#d33",
                confirmButtonText: "Lepas ban?"
              }).then(val => {
                //vehicle/tire/1
                if (val.value) {
                  this.$router.push({
                    path: "/vehicle/tire/" + res.data.get_vehicle.id
                  });
                }
                // console.log([val, res]);
              });
              // console.log("err", error.response.data);
            });
        }
      });
    }
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
      padding-left: 40%;
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
    content: "No";
  }
  td:nth-of-type(2):before {
    content: "Merek";
  }
  td:nth-of-type(3):before {
    content: "Ukuran";
  }
  td:nth-of-type(4):before {
    content: "Kendaraan";
  }
  td:nth-of-type(5):before {
    content: "Tanggal Beli";
  }
  td:nth-of-type(6):before {
    content: "Action";
  }
}
</style>