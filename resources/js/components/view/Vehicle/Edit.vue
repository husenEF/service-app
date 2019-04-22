<template>
  <form @submit.prevent="submitData">
    <div class="form-group">
      <label for>Merek</label>
      <input type="text" class="form-control" v-model="data.merek">
    </div>
    <div class="form-group">
      <label for>Plat Nomor</label>
      <input type="text" class="form-control" v-model="data.platnumber">
    </div>
    <div class="form-group">
      <label for>Ukuran Ban</label>
      <input type="text" class="form-control" v-model="data.size">
    </div>

    <h3>
      Ban
      <button type="button" class="btn btn-info float-right" v-on:click="addBan()">+ Ban</button>
    </h3>
    <fieldset v-for="(theTire, index) in data.tire" :key="index" class="border pl-3 pr-3 pb-3 mb-3">
      <legend class="w-auto">Ban ke {{index +1}}:</legend>
      <div class="row">
        <div class="col-md-5">
          <input type="text" class="form-control" placeholder="Merek Ban" v-model="theTire.merek">
        </div>
        <div class="col-md-5">
          <!-- <label for="">{{theTire.buy_date}}</label> -->
          <Datetime v-model="theTire.buy_date" input-class="form-control" value-zone="Asia/Jakarta"></Datetime>
        </div>
        <div class="col-2">
          <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-danger btn-sm" v-on:click="deletBan(theTire.id)">X</button>
            <button
              type="button"
              class="btn btn-info btn-sm"
              v-on:click="historyBand(theTire.id)"
              title="History Band"
            >
              <EyeIcon/>
            </button>
            <button
              type="button"
              class="btn btn-warning btn-sm"
              title="History Posisi"
              v-on:click="historyPosition(data.id,theTire.posistion)"
            >
              <EyeIcon/>
            </button>
          </div>
        </div>
      </div>
    </fieldset>
    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
  </form>
</template>

<script>
import { Datetime } from "vue-datetime";
import { EyeIcon } from "vue-feather-icons";
// You need a specific loader for CSS files
import "vue-datetime/dist/vue-datetime.css";

export default {
  name: "vehicleEdit",
  components: {
    Datetime,
    EyeIcon
  },
  created() {
    const id = this.$route.params.id;
    this.getDetail(id);
    this.$emit("back", "/vehicle");
  },
  computed: {
    getUser() {
      return this.$store.getters.currentUser;
    }
  },
  data() {
    return {
      id: 0,
      data: {}
    };
  },
  methods: {
    getDetail(id) {
      axios.get("/api/v1/vehicle/" + id).then(res => {
        const { data } = res;
        if (data.success) {
          this.data = data.data;
          this.data.tirescount = Object.keys(data.data.tires).length;
        }
      });
    },
    submitData(e) {
      const userId = this.getUser.id;
      const { tire, id } = this.data;
      const err = tire.filter(e => e.merek == "" || e.buy_date == "");
      if (err.length) {
        alert("Data Harus terisi");
        return false;
      }

      this.data.session_user = userId;
      axios.put("api/v1/vehicle/" + id, this.data).then(res => {
        if (res.data.success) {
          this.$swal({
            title: "Data Save",
            text: res.data.message,
            type: "success",
            confirmButtonColor: "#3085d6"
          }).then(res => {
            if (res.value) {
              window.location.reload();
            }
          });
        }
      });
    },
    addBan() {
      const { id } = this.getUser;
      const pos = this.data.tirescount + 1;
      let { tire } = this.data;
      const newBan = {
        merek: "",
        buy_date: "",
        position: pos,
        id: 0,
        created_by: id,
        id_vehicle: this.data.id
      };

      tire.push(newBan);

      console.log("tire", tire);
    },
    deletBan(id) {
      const userId = this.getUser.id;
      this.$swal({
        title: "Anda Yakin?",
        text: "Anda akan menghapus data ini?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Hapus!"
      }).then(res => {
        if (res.value) {
          axios.delete("/api/v1/tire/" + id).then(res => {
            console.log("res", res);
          });
        } else {
          console.log("cancel");
        }
      });
    },
    historyBand(id) {
      this.$router.push({ path: "/history/tires/" + id });
    },
    historyPosition(v, i) {
      this.$router.push({ path: "/history/position/" + v + "/" + i });
    }
  }
};
</script>
