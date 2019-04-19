<template>
  <div class="card">
    <div class="card-header">
      <h2>List</h2>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Merek</th>
              <th>Status</th>
              <th>Keterangan</th>
              <th>Kendaraan</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(h,i) in list" :key="i">
              <td>{{(i+1)+(meta.pagination.per_page*(meta.pagination.current_page-1))}}</td>
              <td>{{h.merek}}</td>
              <td>{{h.status}}</td>
              <td>
                <p>
                  Merek: {{h.merek}}
                  <br>
                  Images: {{h.images}}
                  <br>
                  Posisi: {{h.posistion}}
                  <br>
                  Pembelian: {{h.buy_date}}
                </p>
              </td>
              <td>
                <router-link :to="'/vehicle/'+h.vehicle.id">{{h.vehicle.merek}}</router-link>
                <p>
                  Plat Number : {{h.vehicle.platnumber}}
                </p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer">
      <nav aria-label="Page navigation example" v-if="meta.pagination.total_pages>1">
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
export default {
  name: "historyList",
  created() {
    this.getList();
    this.$emit("back", "/");
  },
  data() {
    return {
      list: {},
      meta: {}
    };
  },
  methods: {
    getList() {
      axios.get("/api/v1/history/list").then(res => {
        console.log("ress", res.data);
        if (res.data.success) {
          const histId = Object.keys(res.data.data).filter(e => e != "meta");
          this.list = histId.map(i => res.data.data[i]);
          this.meta = res.data.data.meta;
        }
      });
    },
    updateLink(link) {
      axios.get(link).then(res => {
        if (res.data.success) {
          const listId = Object.keys(res.data.data).filter(e => e !== "meta");
          this.list = listId.map(e => res.data.data[e]);
          this.meta = res.data.data.meta;
        }
      });
      return false;
    }
  }
};
</script>
