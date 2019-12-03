<template>
  <div class="card">
    <div class="card-header">
      <h2>Edit Ban</h2>
    </div>
    <div class="card-body">
      <form @submit.prevent="submitData">
        <!-- <pre>{{tire}}</pre> -->
        <div class="row">
          <div class="col-md-6">
            <label for="merek">Merek</label>
            <input type="text" class="form-control" id="merek" v-model="tire.merek" />
          </div>
          <div class="col-md-6">
            <label for="ukuran">Ukuran Ban</label>
            <input type="text" class="form-control" id="ukuran" v-model="tire.ukuran_ban" />
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <label for="nomor">Nomor Ban</label>
            <input type="text" class="form-control" id="nomor" v-model="tire.nomor_ban" />
          </div>
          <div class="col-md-6">
            <label for="stempel">Stempel</label>
            <input type="text" class="form-control" id="stempel" v-model="tire.stempel_ban" />
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
              <input type="file" id="images" ref="file" @change="previewImage()" accept="image/*" />
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
  name: "TireEdit",
  components: {
    Datetime
  },
  data() {
    return {
      tire: {},
      today: moment(Date.now())
        .add(1, "day")
        .format("YYYY-MM-DD")
    };
  },
  created() {
    this.getData();
    this.$emit("back", "/ban");
  },
  methods: {
    getData() {
      const { id } = this.$route.params;
      axios
        .get("/api/v1/tire/" + id)
        .then(res => {
          // console.log(res);
          const { data } = res;
          this.tire = data.data;
        })
        .catch(err => {
          err;
        });
    },
    submitData() {
      let formData = new FormData();
      Object.keys(this.tire).forEach(element => {
        if (typeof this.tire[element] === "boolean")
          formData.append(element, this.tire[element] ? 1 : 0);
        else formData.append(element, this.tire[element]);
      });
      axios
        .post("/api/v1/tire/update", formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
        .then(res => {
          const { data } = res;
          console.log("data", data);
          this.$swal(data.message).then(val => window.location.reload());
        })
        .catch(err => {
          // console.error("err", err.response);
          const {
            response: { data }
          } = err;
          // console.log("errrr", data);

          this.$swal({
            type: "error",
            title: "Opps..!",
            text: data.message
          });
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
