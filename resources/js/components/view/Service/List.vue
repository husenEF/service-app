<template>
  <div class="card">
    <div class="card-header">
      <h2>Daftar Pengecekkan Ban</h2>
    </div>
    <div class="card-body">
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
            <FilterIcon />
          </button>
          <button
            class="btn btn-warning"
            type="button"
            v-on:click="printFilter"
            :disabled="printed==false"
            title="Cetak Data Pencarian"
          >
            <PrinterIcon />
          </button>
        </p>
        <div class="collapse" id="collapseExample">
          <div class="card card-body">
            <div
              class="alert alert-danger"
              role="alert"
              v-if="Object.keys(filter.error).length > 0"
            >
              <p class="m-0">
                <span v-for="(j,i) in filter.error" :key="i">
                  {{j[0]}}
                  <br />
                </span>
              </p>
            </div>
            <form @submit.prevent="filterHandler">
              <div class="row">
                <div class="col-md-5">
                  <div class="form-group">
                    <label for="vehicle">Kendaraan</label>
                    <multiselect
                      v-model="filter.vehicle"
                      :options="vehicle"
                      label="name"
                      placeholder="Select one"
                      track-by="name"
                      @select="selectVehicle"
                      :allow-empty="true"
                    ></multiselect>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <label for>Pilih Ban</label>
                    <multiselect
                      v-model="filter.tire"
                      :options="tire"
                      label="name"
                      placeholder="Select one"
                      track-by="name"
                    ></multiselect>
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
        <!-- <pre>{{service}}</pre> -->
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Ban</th>
              <th>Ketarangan</th>
              <th>Tanggal di buat</th>
              <th>Lepas Ban?</th>
            </tr>
          </thead>
          <tbody v-if="service.length>0">
            <tr v-for="(s,i) in service" :key="i">
              <td>
                <span
                  v-if="typeof meta.pagination !=='undefined'"
                >{{(i+1)+(meta.pagination.per_page*(meta.pagination.current_page-1))}}</span>
                <span v-else>{{i+1}}</span>
              </td>
              <td>{{s.tire.merek}}</td>
              <td>
                <p>
                  <strong>Tekanan Angin</strong>
                  : {{s.tekanan_angin}}
                  <br />
                  <strong>Tebal Tapak</strong>
                  :
                  <br />
                  <ol>
                  <li v-for="(e,i ) in s.tebal_tapak.split(',')" v-bind:key="i">Tebal Tapak {{i+1}}: {{e}}</li>
                  </ol>
                  <strong>Posisi</strong>
                  : {{s.posisi}}
                  <br />
                  <strong>Jarak Km</strong>
                  : {{s.jarakkm}}
                  <br />
                  <strong>Tekanan Angin</strong>
                  : {{s.tekanan_angin}}
                  <br />
                  <strong>Kelainan</strong>
                  : {{s.kelainan}}
                  <br />
                  <strong>Gambar</strong>
                  :
                  <a
                    :href="s.image"
                    target="_blank"
                    rel="noopener noreferrer"
                  >Foto</a>
                </p>
              </td>
              <td>
                {{s.create_at}}
                <br />
                <p>
                  <small>
                    dibuat oleh :
                    <br />
                    Nama : {{s.user.name}}
                    <br />
                    Jabatan : {{s.user.roles}}
                  </small>
                </p>
              </td>
              <td>
                {{(s.lepasban)?'Ya':'Tidak'}}
                <p>Alasan : {{s.alasanlepas}}</p>
              </td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr>
              <td colspan="5">
                <div
                  class="alert alert-info"
                  role="alert"
                >Silakan pilih Kendaraan dan Ban di tombol Filter</div>
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
import Multiselect from "vue-multiselect";
import {
  EyeIcon,
  SearchIcon,
  FilterIcon,
  PrinterIcon
} from "vue-feather-icons";
import "vue-multiselect/dist/vue-multiselect.min.css";

export default {
  name: "serviceList",
  components: {
    EyeIcon,
    SearchIcon,
    FilterIcon,
    Multiselect,
    PrinterIcon
  },
  data() {
    return {
      service: {},
      meta: {},
      filter: {
        vehicle: null,
        tire: null,
        error: {}
      },
      vehicle: [],
      tire: [],
      raw: {
        vehicle: {}
      },
      printed: false
    };
  },
  created() {
    // this.getList("/api/v1/service");
    this.getAll();
  },
  methods: {
    getAll() {
      axios
        .all([
          axios.get("/api/v1/vehicle/list?page=all"),
          axios.post("/api/v1/tire/filter", { key: "page", value: "all" })
        ])
        .then(
          axios.spread((vehicle, tire) => {
            this.vehicle = vehicle.data.data.map(e => {
              return { name: e.merek, id: e.id };
            });
            this.tire = tire.data.data.map(d => {
              return { name: d.merek, id: d.id };
            });
            // console.log("vehicle", vehicle);
            // console.log("tire", tire);
          })
        );
      // axios
      //   .get("/api/v1/vehicle/list?page=all")
      //   .then(res => {
      //     const { data } = res;
      //     // console.log(data)
      //     this.vehicle = data.data.map(e => {
      //       return { name: e.merek, id: e.id };
      //     });
      //     this.raw.vehicle = data.data;
      //   })
      //   .catch(err => {});
    },
    selectVehicle(selectedOption, id) {
      // const { vehicle } = this.raw;
      // const theVehicle = vehicle.filter(v => v.id == selectedOption.id);
      // const theTire = theVehicle[0].tire.map(t => {
      //   return { name: t.merek, id: t.id };
      // });
      // console.log(theTire);
      // this.tire = theTire;
      this.filter.vehicle = selectedOption.id;
    },
    filterHandler() {
      this.printed = false;
      this.filter.error = {};
      //remove old link
      $("[download='service.xlsx']").remove();

      axios
        .post("/api/v1/service/filter", this.filter)
        .then(res => {
          const { data } = res;
          // console.log("data", data);
          let listId = Object.keys(data.data).filter(i => i !== "meta");
          // console.log("map tireId", listId);
          this.service = listId.map(i => data.data[i]);
          this.printed = true;
          if (data.data.hasOwnProperty("meta")) {
            this.meta = data.data.meta;
          }
        })
        .catch(err => {
          // console.log("errrr", err.response.data);
          let { data } = err.response;
          if (data.hasOwnProperty("message")) {
            this.filter.error = [[data.message]];
          } else {
            this.filter.error = err.response.data;
          }
        });
    },
    getList(link) {
      axios
        .get(link)
        .then(res => {
          const { data } = res;
          if (data.success) {
            let listId = Object.keys(data.data).filter(i => i !== "meta");
            // console.log("map tireId", tireId);
            this.service = listId.map(i => data.data[i]);
            if (data.data.hasOwnProperty("meta")) {
              this.meta = data.data.meta;
            }
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
    printFilter() {
      // console.log("printFilter", this.filter);
      // $("[download='service.xlsx']").remove();
      // doc.findElementByAttribute("download", "service.xlsx");
      axios
        .post("/api/v1/service/export", this.filter)
        .then(res => {
          // console.log("res", res);
          const url = res.data;
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", "service.xlsx"); //or any other extension
          document.body.appendChild(link);
          link.click();
        })
        .catch(err => {
          console.log("err", err);
        });
    }
  }
};
</script>

<style lang="scss" scoped>
.filter {
  form {
    button {
      margin-top: 30px;
    }
  }
}
</style>
