<template>
  <div class="card">
    <div class="card-header">List ban</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Merek</th>
              <th>Tanggal Beli</th>
              <th>Kendaraan</th>
              <th>history</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(tire, i) in list" :key="i">
              <td>{{(i+1)+(meta.pagination.per_page*(meta.pagination.current_page-1))}}</td>
              <td>{{tire.merek}}</td>
              <td>{{tire.buy_date}}</td>
              <td>
                <router-link :to="'/vehicle/'+tire.vehicle.id">t{{tire.vehicle.merek}}</router-link>
              </td>
              <td>
                <div class="btn-group" role="group" aria-label="Tire Button">
                  <router-link class="btn btn-info btn-sm" :to="'/history/tires/'+tire.id">
                    <EyeIcon/>
                  </router-link>
                  <router-link
                    class="btn btn-primary btn-sm"
                    :to="'/history/position/'+tire.vehicle.id+`/`+tire.id"
                  >
                    <EyeIcon/>
                  </router-link>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer">
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
import { EyeIcon } from "vue-feather-icons";

export default {
  name: "tireList",
  components: {
    EyeIcon
  },
  created() {
    this.getList("/api/v1/tire/list");
  },
  data() {
    return {
      list: {},
      meta: {}
    };
  },
  methods: {
    getList(uri) {
      axios.get(uri).then(res => {
        const { data } = res;
        console.log("d", data.data);
        if (data.success) {
          let tireId = Object.keys(data.data).filter(i => i !== "meta");
          console.log("map tireId", tireId);
          this.list = tireId.map(i => data.data[i]);
          if (data.data.hasOwnProperty("meta")) {
            this.meta = data.data.meta;
          }
        }
      });
    }
  }
};
</script>
