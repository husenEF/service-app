<template>
  <div class="card">
    <div class="card-header">Lihat Ban</div>
    <div class="card-body">
      <form action>
        <fieldset v-for="n in 11 " :key="n" class="p-3">
          <legend class="p-1 w-auto">Posisi {{n}}</legend>
          <div class="form-group">
            <label for>Name</label>
            <input type="text" class="form-control">
          </div>
        </fieldset>
      </form>
    </div>
  </div>
</template>

<script>


export default {
  name: "vehicleTireManagement",
  data() {
    return {
      tires: {}
    };
  },
  created() {
    this.getList();
  },
  methods: {
    getList() {
      const id = this.$route.params.id;
      this.$emit("back", "/vehicle");
      axios
        .post("/api/v1/tire/filter", { key: "id_vehicle", value: id })
        .then(res => {
          const { data } = res;
          this.tires = data.data;
        })
        .catch(err => {
          const { data } = err.response;
          if (data.hasOwnProperty("success")) {
            this.tires = {};
          } else {
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
