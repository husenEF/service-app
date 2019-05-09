<template>
  <div class="card">
    <div class="card-header">Edit Tire</div>
    <div class="card-body">
      <form @submit.prevent="submitData">
        <!-- <pre>{{tire}}</pre> -->
        <div class="row">
          <div class="col-md-6">
            <label for="merek">Merek</label>
            <input type="text" class="form-control" id="merek" v-model="tire.merek">
          </div>
          <div class="col-md-6">
            <label for="ukuran">Ukuran Ban</label>
            <input type="text" class="form-control" id="ukuran" v-model="tire.ukuran_ban">
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <label for="nomor">Nomor Ban</label>
            <input type="text" class="form-control" id="nomor" v-model="tire.nomor_ban">
          </div>
          <div class="col-md-6">
            <label for="stempel">Stempel</label>
            <input type="text" class="form-control" id="stempel" v-model="tire.stempel_ban">
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <label for="buydate">Waktu Beli</label>
            <Datetime v-model="tire.buy_date" input-class="form-control" value-zone="Asia/Jakarta"></Datetime>
          </div>
          <div class="col-md-6">
            <label for="images">Foto</label>
            <div class="custom-file">
              <input type="file" id="images" ref="file" @change="previewImage()" accept="image/*">
            </div>
          </div>
        </div>
        <div class="form-group mt-2">
          <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Datetime } from "vue-datetime";
import "vue-datetime/dist/vue-datetime.css";

export default {
  name: "AddTire",
  components: {
    Datetime
  },
  data() {
    return {
      tire: {
        merek: "",
        ukuran_ban: "",
        nomor_ban: "",
        stempel_ban: "",
        buy_date: "",
        image: "",
        uid: null
      }
    };
  },
  created() {
    this.$emit("back", "/ban");
    console.log(this.user);
  },
  computed: {
    user() {
      return this.$store.getters.currentUser;
    }
  },
  methods: {
    submitData() {
      let formData = new FormData();
      formData.append("uid", this.user.id);
      Object.keys(this.tire).forEach(element => {
        if (typeof this.tire[element] === "boolean")
          formData.append(element, this.tire[element] ? 1 : 0);
        else formData.append(element, this.tire[element]);
      });

      // return false;
      // console.log("formData", this.tire);
      axios
        .post("/api/v1/tire/store", formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
        .then(res => {
          const { data } = res;
          // console.log(data);
          this.$swal(data.message).then(val => window.location.reload());
        })
        .catch(err => {
          console.error(err);
        });
    },
    previewImage() {
      // console.log(this.$refs.file.files[0]);
      let tire = this.tire;
      tire.image = this.$refs.file.files[0];
      this.tire = tire;
      // tire.push()
    }
  }
};
</script>
