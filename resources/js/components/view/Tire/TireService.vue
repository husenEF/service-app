<template>
  <div class="card">
    <div class="card-header">Tambah Data Uji</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table">
          <thead class="thead-dark">
            <tr>
              <th colspan="2">Info Ban</th>
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

      <fieldset>
        <legend>Form Service</legend>
        <form @submit.prevent="submitData">
          <div class="form-group row">
            <div class="col-md-3">
              <label for="tekananangin">Tekanan Angin</label>
              <input
                type="text"
                class="form-control"
                id="tekananangin"
                v-model="service.tekanan_angin"
              >
            </div>
            <div class="col-md-3">
              <label for="tebaltapak">Tebal Tapak</label>
              <input type="text" class="form-control" id="tebaltapak" v-model="service.tebal_tapak">
            </div>
            <div class="col-md-3">
              <label for="posisi">Posisi</label>
              <input type="text" id="posisi" v-model="service.posisi" class="form-control">
            </div>
            <div class="col-md-3">
              <label for="jarak">Jarak Km</label>
              <input type="text" id="jarak" v-model="service.jarakkm" class="form-control">
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12">
              <label for="catatan">Catatan</label>
              <textarea name="catatan" id="catatan" class="form-control">{{service.catatan}}</textarea>
            </div>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary">simpan</button>
            <button class="btn btn-danger" type="button">Lepas ban?</button>
          </div>
        </form>
      </fieldset>
      <pre>{{getUser}}</pre>
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
      tire: {},
      service: {
        tire_id: null,
        user: null,
        tekanan_angin: "",
        tebal_tapak: "",
        posisi: "",
        jarakkm: "",
        catatan: "",
        kelainan: "",
        lepasban: null,
        alasanlepas: ""
      }
    };
  },
  computed: {
    getUser() {
      return this.$store.getters.currentUser;
    }
  },
  methods: {
    getTire(id) {
      axios
        .get("/api/v1/tire/" + id)
        .then(res => {
          const { data } = res;
          this.tire = data.data;
          this.service.user = this.getUser.id;
          this.service.tire_id = id;
          this.$emit("back", "/vehicle/" + data.data.vehicle.id);
        })
        .catch(err => {
          console.log("err", err.response.data);
        });
    },
    submitData() {
      console.log(this.service);
    }
  }
};
</script>