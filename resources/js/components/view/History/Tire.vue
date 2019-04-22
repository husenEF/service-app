<template>
  <div class="card">
    <div class="card-header">
      <h2>Daftar Riwayat Ban</h2>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Data</th>
              <th>Status</th>
              <th>Keterangan</th>
              <th>Kendaraan</th>
              <th>Diperbaharui Oleh</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(h,i) in list" :key="i">
              <td>{{(i+1)+(meta.pagination.per_page*(meta.pagination.current_page-1))}}</td>
              <td>{{h.merek}}</td>
              <td>{{h.status}}</td>
              <td>
                <p>
                  Gambar: {{h.images}}
                  <br>
                  Posisi: {{h.posistion}}
                  <br>
                  Pembelian: {{h.buy_date}}
                </p>
              </td>
              <td>
                <router-link :to="'/vehicle/'+h.vehicle.id">{{h.vehicle.merek}}</router-link>
                <p>Plat Nomor : {{h.vehicle.platnumber}}</p>
              </td>
              <td>
                {{h.user.name}}
                <p>
                  <strong>Diperbaharuai pada :</strong>
                  {{h.created_at}}
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
  name: "historyTire",
  data() {
    return {
      list: {},
      meta: {}
    };
  },
  created() {
    const id = this.$route.params.id;
    this.getList(id);
  },
  computed: {
    getUser() {
      return this.$store.getters.currentUser;
    }
  },
  methods: {
    getList(id) {
      axios.get("/api/v1/history/tire/" + id).then(res => {
        // console.log("res", res.data);
        if (res.data.success) {
          const listId = Object.keys(res.data.data).filter(e => e !== "meta");
          this.list = listId.map(e => res.data.data[e]);
          this.meta = res.data.data.meta;
          this.$emit("back", "/vehicle/" + this.list[0].id_vehicle);
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