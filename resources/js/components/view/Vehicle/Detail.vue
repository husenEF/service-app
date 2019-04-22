<template>
  <div class="card">
    <div class="card-header">Detail Kendaraan</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table">
          <tbody>
            <tr>
              <th>Merek Kendaraan</th>
              <td>{{vehicle.merek}}</td>
            </tr>
            <tr>
              <th>Plat Nomor</th>
              <td>{{vehicle.platnumber}}</td>
            </tr>
            <tr>
              <th>Data di buat Oleh</th>
              <td>{{vehicle.create_user.create_user}} ({{vehicle.create_user.roles}})</td>
            </tr>
            <tr>
              <th>Terakhir memperbaharui</th>
              <td>{{vehicle.update_by}}</td>
            </tr>
            <tr>
              <th></th>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "vehicleDetail",
  data() {
    return {
      vehicle: {}
    };
  },
  created() {
    const id = this.$route.params.id;
    this.getData(id);
    this.$emit("back", "/vehicle");
  },
  methods: {
    getData(id) {
      axios.get("/api/v1/vehicle/" + id).then(res => {
        const { data } = res;
        if (data.success) {
          this.vehicle = data.data;
          this.vehicle.tirescount = Object.keys(data.data.tires).length;
        }
      });
    }
  }
};
</script>