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
              <th>Raw</th>
              <th>#</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "historyList",
  created() {
    this.getList();
  },
  data() {
    return {
      list: {}
    };
  },
  methods: {
    getList() {
      axios.get("/api/v1/history/list").then(res => {
        console.log("ress", res.data);
        if (res.data.success) {
          const histId = Object.keys(res.data.data).filter(e => e != "meta");
          this.list = histId.map(i => res.data.data[i]);
        }
      });
    }
  }
};
</script>
