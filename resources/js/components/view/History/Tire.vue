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
            </tr>
          </tbody>
        </table>
      </div>
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
        }
      });
    }
  }
};
</script>