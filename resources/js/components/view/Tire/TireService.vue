<template>
  <div class="card">
    <div class="card-header">Tambah Data Uji</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table">
          <thead class="thead-dark">
            <tr>
              <th colspan="2">Detail Ban</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th>Merek</th>
              <td>{{tire.merek}}</td>
            </tr>
            <tr>
              <th>Nomor Ban</th>
              <td>{{tire.nomor_ban}}</td>
            </tr>
            <tr>
              <th>Posisi</th>
              <td>{{tire.posistion}}</td>
            </tr>
            <tr>
              <th>Stempel Ban</th>
              <td>{{tire.stempel_ban}}</td>
            </tr>
            <tr>
              <th>Dibuat Oleh</th>
              <td>{{tire.user.name}}</td>
            </tr>
            <tr>
              <th>Kendaraan Saat ini</th>
              <td>
                {{tire.vehicle.merek}}
                <br>
                {{tire.vehicle.platnumber}}
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
  name: "AddHistoryTire",
  created() {
    const { tireid } = this.$route.params;
    this.getTire(tireid);
  },
  data() {
    return {
      tire: {}
    };
  },
  methods: {
    getTire(id) {
      axios
        .get("/api/v1/tire/" + id)
        .then(res => {
          const { data } = res;
          this.tire = data.data;
          this.$emit("back", "/vehicle/" + data.data.vehicle.id);
        })
        .catch(err => {
          console.log("err", err.response.data);
        });
    }
  }
};
</script>