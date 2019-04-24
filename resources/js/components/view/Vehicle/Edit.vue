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
