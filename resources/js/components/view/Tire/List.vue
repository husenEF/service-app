<template>
  <div class="card">
    <div class="card-header">
      <h2>Daftar Ban</h2>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <div class="row filter">
          <div class="col-md-8">
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
                <FilterIcon />
              </button>
              <a
                href="/export/ban"
                target="_blank"
                rel="noopener noreferrer"
                class="btn btn-warning"
              >
                <PrinterIcon />
              </a>
              <router-link to="/ban/add" class="btn btn-success float-right">
                <PlusIcon />Tambah Ban
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
                        <select
                          class="form-control"
                          required
                          v-model="filter.key"
                          name="key"
                          @change="filterHandlerChange"
                        >
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
                          @change="filterHandlerChange"
                        />
                      </div>
                      <div class="form-group" v-else>
                        <label for>Pilih Tanggal</label>
                        <Datetime
                          v-model="filter.value"
                          input-class="form-control"
                          value-zone="Asia/Jakarta"
                          @change="filterHandlerChange"
                        ></Datetime>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <button class="btn btn-primary btn-block" type="submit">
                        <SearchIcon v-if="!filter.loading" />
                        <span class="spinner-border spinner-border-sm" v-else></span>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-4 text-md-right">
            <router-link to="/ban/trash">
              <Trash2Icon />
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
              <td title="No">
                <span
                  v-if="typeof meta.pagination !=='undefined'"
                >{{(i+1)+(meta.pagination.per_page*(meta.pagination.current_page-1))}}</span>
                <span v-else>{{i+1}}</span>
              </td>
              <td title="Merek">
                <span>{{tire.merek}}</span>
              </td>
              <td title="Ukuran">
                <span>{{tire.ukuran_ban}}</span>
              </td>
              <td title="Stempel">
                <span>{{tire.stempel_ban}}</span>
              </td>
              <td title="Tangal Beli">
                <span>{{tire.buy_date}}</span>
              </td>
              <td title="Action">
                <span class="btn-group" role="group" aria-label="Tire Button">
                  <router-link class="btn btn-info btn-sm" :to="'/ban/edit/'+tire.id">Edit</router-link>
                  <button
                    class="btn btn-danger btn-sm"
                    v-on:click="confirmAfkir(tire.id)"
                  >afkir/buang?</button>
                </span>
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
        value: "",
        loading: false
      }
    };
  },
  methods: {
    getList(uri) {
      axios
        .get(uri)
        .then(res => {
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
        })
        .catch(err => {
          console.list("err", err);
        });
    },
    filterHandler() {
      this.filter.loading = true;
      axios
        .post("/api/v1/tire/filter", this.filter)
        .then(res => {
          const { data } = res;
          if (data.success) {
            const ListId = Object.keys(data.data).filter(e => e !== "meta");
            this.list = ListId.map(e => data.data[e]);
            if (data.data.hasOwnProperty("meta")) {
              this.meta = data.data.meta;
            } else {
              this.meta = {};
            }
            this.filter.loading = false;
          }
        })
        .catch(err => {
          this.filter.loading = false;
          this.$swal({
            icon: "error",
            title: "Oops...",
            text: err.response.data.message
          });
        });
    },
    filterHandlerChange() {
      const { key, value } = this.filter;
      if (key == "" && value == "") {
        this.getList("/api/v1/tire/list");
      }
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
              });
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
</style>