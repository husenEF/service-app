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
        <legend class="w-auto">Form Service</legend>
        <div class="alert alert-danger" v-if="Object.keys(error).length>0" role="alert">
          <p class="mb-0">
            <span v-for="(e,i) in error" :key="i" class="clearfix">{{e[0]}}</span>
          </p>
        </div>
        <form @submit.prevent="submitData">
          <div class="form-group row">
            <div class="col-md-3">
              <label for="tekananangin">Tekanan Angin</label>
              <input
                type="number"
                class="form-control"
                id="tekananangin"
                v-model="service.tekanan_angin"
              >
            </div>
            <div class="col-md-3">
              <label for="tebaltapak">Tebal Tapak</label>
              <input
                type="number"
                class="form-control"
                id="tebaltapak"
                v-model="service.tebal_tapak"
              >
            </div>
            <div class="col-md-3">
              <label for="posisi">Posisi</label>
              <input
                type="number"
                id="posisi"
                v-model="service.posisi"
                class="form-control"
                min="1"
                max="11"
              >
            </div>
            <div class="col-md-3">
              <label for="jarak">Jarak Km</label>
              <input type="number" id="jarak" v-model="service.jarakkm" class="form-control">
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-4">
              <label for>Keterangan lain</label>
              <div class="form-check">
                <input
                  type="radio"
                  id="misalignment"
                  class="form-check-input"
                  v-model="service.kelainan"
                  value="Misalignment"
                >
                <label for="misalignment">Misalignment</label>
              </div>
              <div class="form-check">
                <input
                  type="radio"
                  v-model="service.kelainan"
                  id="tutuppentil"
                  class="form-check-input"
                  value="Tutup pentil tidak ada"
                >
                <label for="tutuppentil">Tutup pentil tidak ada</label>
              </div>
              <div class="form-check">
                <input
                  type="radio"
                  v-model="service.kelainan"
                  id="tekananangin"
                  class="form-check-input"
                  value="Kekurangan / Kelebihan Tekanan Angin"
                >
                <label for="tekananangin">Kekurangan / Kelebihan Tekanan Angin</label>
              </div>
              <div class="form-check">
                <input
                  type="radio"
                  v-model="service.kelainan"
                  id="pentilrusak"
                  class="form-check-input"
                  value="Pentil rusak"
                >
                <label for="pentilrusak">Pentil rusak</label>
              </div>
              <div class="form-check">
                <input
                  type="radio"
                  v-model="service.kelainan"
                  id="bautroda"
                  class="form-check-input"
                  value="Baut roda longgar / rusak"
                >
                <label for="bautroda">Baut roda longgar / rusak</label>
              </div>
              <div class="form-check">
                <input
                  type="radio"
                  v-model="service.kelainan"
                  id="sobek"
                  class="form-check-input"
                  value="Ban cacat / sobek"
                >
                <label for="sobek">Ban cacat / sobek</label>
              </div>
            </div>
            <div class="col-md-8">
              <label for="catatan">Catatan</label>
              <textarea
                name="catatan"
                id="catatan"
                rows="7"
                class="form-control"
                v-model="service.catatan"
              >{{service.catatan}}</textarea>
            </div>
          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-primary">simpan</button>
            <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#exampleModal">Lepas ban?</button>
          </div>
        </form>
      </fieldset>
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
        kendaraan: null,
        tekanan_angin: "",
        tebal_tapak: "",
        posisi: "",
        jarakkm: "",
        catatan: "",
        kelainan: "",
        lepasban: null,
        alasanlepas: ""
      },
      error: {}
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
          this.service.posisi = data.data.posistion;
          this.service.kendaraan = data.data.vehicle.id;
          this.$emit("back", "/vehicle/" + data.data.vehicle.id);
        })
        .catch(err => {
          console.log(err.response.data);
          this.error = err.response.data;
        });
    },
    submitData() {
      axios
        .post("/api/v1/service/save", this.service)
        .then(res => {
          const { data } = res;
          this.$swal(data.message).then(val => window.location.reload());
        })
        .catch(err => {
          this.error = err.response.data;
        });
    },
    lepasban() {
      this.$swal({
        title: "Anda Yakin?",
        text: "Anda akan menghapus data ini?",
        html:"<ul class=''>"+
        "<li class='text-left'><input type='radio' name='alasan' v-on:click='alert(\"a\")' />Gundul</li>"+
        "<li class='text-left'><input type='radio' name='alasan' />Gundul</li>"+
        "<li class='text-left'><input type='radio' name='alasan' />Gundul</li>"+
        "<li class='text-left'><input type='radio' name='alasan' />Gundul</li>"+
        "</ul>",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Hapus!"
      }).then(res => {
        if (res.value) {
          console.log(res);
        } else {
          console.log("cancel");
        }
      });
    }
  }
};
</script>

<style lang="scss">
fieldset {
  border-width: 2px;
  border-style: groove;
  // border-color: threedface;
  border-image: initial;
  padding: inherit;
  margin: inherit;
}
</style>
