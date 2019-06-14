<template>
  <div class="card">
    <div class="card-header">
      <h2>Trash</h2>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Merek</th>
              <th>Ukuran</th>
              <th>Stempel</th>
              <th>Tanggal Beli</th>
              <th>Tanggal Afkir</th>
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
              <td>{{tire.stempel_ban}}</td>
              <td>{{tire.buy_date}}</td>
              <td>{{tire.afkir}}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer" v-if="typeof meta.pagination !=='undefined'">
      <nav aria-label="Page navigation example" v-if="(meta.pagination.total_pages)>1">
        <ul class="pagination mb-0">
          <li v-for="(link,i) in meta.pagination.links" :key="i" class="page-item">
            <button type="button " class="page-link" v-on:click="getData(link)">{{i}}</button>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script>
import { constants } from "crypto";
export default {
  name: "tireTrash",
  created() {
    this.$emit("back", "/ban");
    this.getData("/api/v1/tire/trash");
  },
  data() {
    return {
      list: {},
      meta: {}
    };
  },
  methods: {
    getData(url) {
      axios
        .get(url)
        .then(res => {
          //   console.log(res);
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
        .catch(err => constants.log("err", err));
    }
  }
};
</script>