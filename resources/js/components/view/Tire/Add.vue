<template>
  <div class="card">
    <div class="card-header">Tambah Ban</div>
    <div class="card-body">
      <div class="alert alert-danger" v-if="Object.keys(error).length>0">
        <p class="mb-0">
          <span v-for="(e,i) in error" :key="i" class="clearfix">{{e[0]}}</span>
        </p>
      </div>
      <form @submit.prevent="submitData">
        <!-- <pre>{{error}}</pre> -->
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
          <!-- <div class="col-md-6">
             <label for="position">Posisi</label>
            <select name="position" id="position" class="form-control" v-model="tire.position">
              <option value>Pilih Posisi</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
            </select> 
          </div>-->
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
            <Datetime
              v-model="tire.buy_date"
              input-class="form-control"
              value-zone="Asia/Jakarta"
              :max-datetime="today"
            ></Datetime>
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
import moment from "moment";
import "vue-datetime/dist/vue-datetime.css";

export default {
  name: "AddTire",
  components: {
    Datetime
  },
  data() {
    // console.log("data", "data");
    return {
      tire: {
        merek: "",
        ukuran_ban: "",
        nomor_ban: "",
        stempel_ban: "",
        buy_date: "",
        image: "",
        uid: null
      },
      error: {},
      today: moment(Date.now())
        .add(1, "day")
        .format("YYYY-MM-DD")
    };
  },
  mounted() {
    console.log("mounted", "mount");
  },
  created() {
    this.$emit("back", "/ban");
    this.tire.uid = this.user.id;
    console.log("created", today);
  },
  computed: {
    user() {
      return this.$store.getters.currentUser;
    }
  },
  methods: {
    submitData() {
      let formData = new FormData();
      // formData.append("uid", this.user.id);
      Object.keys(this.tire).forEach(element => {
        if (typeof this.tire[element] === "boolean")
          formData.append(element, this.tire[element] ? 1 : 0);
        else formData.append(element, this.tire[element]);
      });

      // return false;
      // console.log("formData", this.user);
      // return false;
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
          // console.error(err);
          this.error = err.response.data;
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
