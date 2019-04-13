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
              <th>Data Name</th>
              <th>Status</th>
              <th>Keterangan</th>
              <th>Date Update</th>
              <th>Update By</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(h,i) in list" :key="i">
              <td>{{i}}</td>
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
  </div>
</template>

<script>
export default {
  name: "positionHistory",
  created() {
    const { vehicle, id } = this.$route.params;
    this.getList(vehicle, id);
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
    }
  }
};
</script>