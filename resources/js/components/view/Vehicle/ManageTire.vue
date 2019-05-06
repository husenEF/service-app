<template>
  <div class="card">
    <div class="card-header">Lihat Ban</div>
    <div class="card-body">
      <form @submit.prevent="kirimData">
        <fieldset v-for="n in 11 " :key="n" class="p-3">
          <legend class="p-1 w-auto">Posisi {{n}}</legend>
          <div class="form-group">
            <label for>Name</label>
            <multiselect
              v-model="tirePos[n]"
              :options="options"
              label="name"
              placeholder="Select one"
              track-by="name"
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
          console.log("res", res);
        })
        .catch(err => {
          console.log("err", err.response.data);
        });
    },
    getList() {
      const id = this.$route.params.id;
      this.vehicleId = id;
      this.$emit("back", "/vehicle");
      axios
        .post("/api/v1/tire/filter", { key: "posistion", value: 0 })
        .then(res => {
          const { data } = res;
          // console.log(data);
          this.options = data.data.map(e => {
            return { name: e.merek, id: e.id };
          });
          // this.tires = data.data;
        })
        .catch(err => {
          const { data } = err.response;
          if (data.hasOwnProperty("success")) {
            this.options = [];
          } else {
          }
        });
    },
    selectHandler(selectedOption, id) {
      let options = this.options;
      let mySelect = options.map(e => {
        if (e.id == selectedOption.id) {
          return { id: e.id, name: e.name, $isDisabled: true };
        } else {
          return e;
        }
      });

      this.options = mySelect;
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
