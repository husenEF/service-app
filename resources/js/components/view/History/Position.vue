<template>
  <div class="card">
    <div class="card-header">
      <h2>Daftar Riwayat Posisi</h2>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Tipe Data</th>
              <th>Status</th>
              <th>Keterangan</th>
              <th>Tanggal Pembaharuan</th>
              <th>DIperbaharui Oleh</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(h,i) in list" :key="i">
              <td>{{(i+1)+(meta.pagination.per_page*(meta.pagination.current_page-1))}}</td>
              <td>{{h.dataname}}</td>
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
              <td>{{h.created_at}}</td>
              <td>{{h.user.name}} ({{h.user.roles}})</td>
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
  name: "positionHistory",
  created() {
    const { vehicle, id } = this.$route.params;
    this.getList(vehicle, id);
    this.$emit("back", "/vehicle/" + vehicle);
  },
  data() {
    return {
      list: {},
      meta: {}
    };
  },
  methods: {
    getList(v, i) {
      axios.get("/api/v1/history/position/" + v + "/" + i).then(res => {
        if (res.data.success) {
          const listId = Object.keys(res.data.data).filter(e => e !== "meta");
          this.list = listId.map(e => res.data.data[e]);
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