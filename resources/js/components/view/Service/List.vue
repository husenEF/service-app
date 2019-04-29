<template>
  <div class="card">
    <div class="card-header">Daftar Pengecekkan Ban</div>
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
                    <select class="form-control" required  name="key">
                      <option value>Pilih</option>
                      <option value="merek">Merek</option>
                      <option value="datetime">Tanggal Beli</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group" >
                    <label for>Kata Kunci</label>
                    <input
                      required
                      type="text"
                      class="form-control"
                      placeholder="Kata Kunci"
                    >
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
              <th>No</th>
              <th>Ban</th>
              <th>Ketarangan</th>
              <th>Tanggal di buat</th>
              <th>Lepas Ban?</th>
            </tr>
          </thead>
          <tbody>
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
                  <br>
                  <strong>Tebal Tapak</strong>
                  : {{s.tebal_tapak}}
                  <br>
                  <strong>Posisi</strong>
                  : {{s.posisi}}
                  <br>
                  <strong>Jarak Km</strong>
                  : {{s.jarakkm}}
                  <br>
                  <strong>Tekanan Angin</strong>
                  : {{s.tekanan_angin}}
                  <br>
                </p>
              </td>
              <td>
                {{s.create_at}}
                <br>
                <p>
                  <small>
                    dibuat oleh :
                    <br>
                    Nama : {{s.user.name}}
                    <br>
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
import { EyeIcon, SearchIcon, FilterIcon } from "vue-feather-icons";
export default {
  name: "serviceList",
  components: {
    EyeIcon,
    SearchIcon,
    FilterIcon
  },
  data() {
    return {
      service: {},
      meta: {}
    };
  },
  created() {
    this.getList("/api/v1/service");
  },
  methods: {
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
    }
  }
};
</script>
