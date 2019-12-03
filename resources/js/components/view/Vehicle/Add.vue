<template>
  <div class="card">
    <div class="card-header">
      <h2>Tambah Kendaraan</h2>
    </div>
    <div class="card-body">
      <div class="alert alert-danger" v-if="Object.keys(error).length>0">
        <p class="mb-0">
          <span v-for="(e,i) in error" :key="i" class="clearfix">{{e[0]}}</span>
        </p>
      </div>
      <form @submit.prevent="submitData">
        <div class="row mb-3">
          <div class="col-md-4">
            <input type="text" class="form-control" v-model="form.merek" placeholder="Merek">
          </div>
          <div class="col-md-4">
            <input
              type="text"
              class="form-control"
              v-model="form.plat_number"
              placeholder="Plat nomor"
            >
          </div>
          <div class="col-md-4">
            <input
              type="text"
              class="form-control"
              v-model="form.size"
              min="0"
              placeholder="Ukuran Ban"
            >
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: "vehicleAdd",
  data() {
    return {
      form: {},
      error: {}
    };
  },
  created() {
    this.$emit("back", "/vehicle");
  },
  methods: {
    submitData() {
      this.error = {};
      axios
        .post("/api/v1/vehicle/add", this.form)
        .then(res => {
          if (res.data.success) {
            this.$swal({
              title: "Data Save",
              text: res.data.message,
              type: "success",
              confirmButtonColor: "#3085d6"
            }).then(res => {
              if (res.value) {
                this.$router.push("/vehicle");
              }
            });
          }
        })
        .catch(err => {
          //   console.log("err", err.response.data);
          this.error = err.response.data;
        });
    }
  }
};
</script>