<template>
  <div class="card">
    <div class="card-header">
      <h2>Unduh Pengecekkan Ban</h2>
    </div>
    <div class="card-body">
      <div class="clearfix filter">
        <!-- <p>
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
        </p>-->
        <!-- <div class="collapse" id="collapseExample"> -->
        <div class="card card-body">
          <div class="alert alert-danger" role="alert" v-if="Object.keys(filter.error).length > 0">
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
                  <label for="buydate">Waktu Beli</label>
                  <select name="key" id v-model="filterDate.key" class="form-control">
                    <option value="buy_date">Waktu Pembelian</option>
                    <option value="check_date">Waktu Pengecekkan</option>
                  </select>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label for>Tanggal Cek Ban</label>
                  <Datetime
                    v-model="filterDate.value"
                    input-class="form-control"
                    value-zone="Asia/Jakarta"
                    :max-datetime="today"
                  ></Datetime>
                </div>
              </div>
              <div class="col-md-2">
                <button class="btn btn-primary btn-block" type="submit">
                  <DownloadIcon />
                </button>
              </div>
            </div>
          </form>
        </div>
        <!-- </div> -->
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
import { Datetime } from "vue-datetime";
import moment from "moment";

import {
  EyeIcon,
  SearchIcon,
  FilterIcon,
  PrinterIcon,
  DownloadIcon
} from "vue-feather-icons";
import "vue-multiselect/dist/vue-multiselect.min.css";
import "vue-datetime/dist/vue-datetime.css";

export default {
  name: "serviceList",
  components: {
    EyeIcon,
    SearchIcon,
    FilterIcon,
    Multiselect,
    PrinterIcon,
    DownloadIcon,
    Datetime
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
      filterDate: {
        key: null,
        value: new Date()
      },
      vehicle: [],
      tire: [],
      raw: {
        vehicle: {}
      },
      printed: false,
      today: moment(Date.now())
        .add(1, "day")
        .format("YYYY-MM-DD")
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
        .post("/api/v1/service/filterdate", this.filterDate)
        .then(res => {
          const { data } = res;
          const url = data.url;
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", "tire.xlsx"); //or any other extension
          document.body.appendChild(link);
          link.click();
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
