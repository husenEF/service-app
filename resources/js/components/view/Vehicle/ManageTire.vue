<template>
  <div class="card">
    <div class="card-header">
      <h2>Lihat Ban</h2>
    </div>
    <div class="card-body">
        <img src="/images/diagram.jpg" alt="Diagram" title="Diagram" class="img-fluid">
      <!-- <pre>{{tirePos}}</pre> -->
      <form @submit.prevent="kirimData">
        <fieldset v-for="(n,i) in 11 " :key="i" class="p-3">
          <!-- <legend class="p-1 w-auto">Posisi {{n}}</legend> -->
          <legend class="p-1 w-auto">
            Posisi <span>[{{ i+1 }}]</span> -
            <span
              v-if="!_.isEmpty(tirePos[i]) && (tirePos[i].posistion>0)"
            >Stempel Ban: {{tirePos[i].stempel_ban}}</span>
          </legend>
          <div class="row" v-if="!_.isEmpty(tirePos[i]) && (tirePos[i].posistion>0)">
            <div class="col-md-4">
              <img
                :src="tirePos[i].images_path"
                :alt="tirePos[i].merek"
                class="mr-3 img-fluid"
                v-if="tirePos[i].images_path"
              >
            </div>
            <div class="col-md-8">
              <div class="table-responsive">
                <table class="table">
                  <tr>
                    <th>Merek</th>
                    <td>{{tirePos[i].merek}}</td>
                  </tr>
                  <tr>
                    <th>Ukuran Ban</th>
                    <td>{{tirePos[i].ukuran_ban}}</td>
                  </tr>
                  <tr>
                    <th>Nomor Ban</th>
                    <td>{{tirePos[i].nomor_ban}}</td>
                  </tr>
                  <tr>
                    <th>Stempel Ban</th>
                    <td>{{tirePos[i].stempel_ban}}</td>
                  </tr>
                  <tr>
                    <th>Tanggal Beli</th>
                    <td>{{tirePos[i].buy_date}}</td>
                  </tr>
                  <tr>
                    <th>Pembuat</th>
                    <td>{{tirePos[i].user.name}}</td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <button
                        type="button"
                        class="btn btn-danger btn-sm"
                        v-on:click="serviceBan(tirePos[i].id)"
                      >Service/Lepas?</button>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </div>

          <div class="form-group" v-else>
            <label for>Name</label>
            <multiselect
              v-model="tirePos[i]"
              :options="options"
              label="stempel_ban"
              placeholder="Select one"
              track-by="stempel_ban"
              :hide-selected="true"
              :allow-empty="true"
              @remove="removeHandler"
              @select="selectHandler"
            ></multiselect>
          </div>
        </fieldset>
        <div class="form-group mt-2">
          <button class="btn-sm btn btn-primary" type="submit">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";

export default {
  name: "vehicleTireManagement",
  components: {
    Multiselect
  },
  data() {
    return {
      tirePos: {},
      options: [],
      vehicleId: 0
    };
  },
  created() {
    this.getList();
  },
  computed: {
    user() {
      return this.$store.getters.currentUser;
    }
  },
  methods: {
    kirimData() {
      // console.log("tirePos", this.tirePos);
      const data = {
        vehicle_id: this.vehicleId,
        tires: this.tirePos,
        uid: this.user.id
      };

      axios
        .post("/api/v1/tire/assign", data)
        .then(res => {
          // console.log("res", res);
          const { data } = res;
          if (data.success) {
            this.$swal({
              title: data.message,
              type: "success"
            }).then(res => {
              window.location.reload();
            });
          }
        })
        .catch(err => {
          // console.log("err", err.response.data);
        });
    },
    getList() {
      const id = this.$route.params.id;
      this.vehicleId = id;
      this.$emit("back", "/vehicle");
      axios
        .all([
          axios.post("/api/v1/tire/filter", { key: "posistion", value: "0" }),
          axios.get("/api/v1/tire/assign/" + id)
        ])
        .then(
          axios.spread((options, tires) => {
            // this.options = options.data.data.map(e => {
            //   return { name: e.merek, id: e.id };
            // });
            // console.log("op",options.data.data)
            this.options = options.data.data;
            this.tirePos = tires.data.data;
          })
        )
        .catch(
          axios.spread(err => {
            // console.log("multi2", err);
          })
        );
    },
    selectHandler(selectedOption, id) {
      // console.log("selectoption", [selectedOption, id]);
      let options = this.options;
      let mySelect = options.map(e => {
        // console.log("e", selectedOption);
        if (e.id == selectedOption.id) {
          return {
            id: selectedOption.id,
            name: selectedOption.name,
            $isDisabled: true
          };
        } else {
          return e;
        }
      });
      // console.log("myselc", mySelect);
      this.options = mySelect;

      // console.log("tirePos", this.tirePos);
      // console.log("selectHandler", [selectedOption, id, mySelect]);
    },
    removeHandler(removedOption, id) {
      let options = this.options;
      let mySelect = options.map(e => {
        if (e.id == removedOption.id) {
          return { id: e.id, name: e.name, $isDisabled: false };
        } else {
          return e;
        }
      });
      this.options = mySelect;
      // console.log("removedOption", removedOption);
    },
    serviceBan(id) {
      this.$swal({
        title: "Mau Service?",
        text: "mau cek ban atau lepas ban?",
        type: "info",
        showCancelButton: true
      }).then(res => {
        if (res.value) {
          this.$router.push("/ban/service/" + id);
          // this.$router.push({ name: "tireService", params: { id } });
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
