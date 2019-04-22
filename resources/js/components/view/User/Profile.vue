<template>
  <div class="container-fluid">
    <div class="row">
      <Header title="User Profile" back="/"/>
    </div>
    <div class="row current-user justify-content-center">
      <div class="col-12 col-md-10">
        <form @submit.prevent="submitData">
          <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" v-model="user.name">
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="text" class="form-control" v-model="user.email">
          </div>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import Header from "../../includes/Header.vue";

export default {
  name: "userIndex",
  methods: {
    submitData() {
      const { id, name, roles } = this.user;
      const send = {
        name: name,
        roles: roles
      };

      axios.put("/api/v1/user/update/" + id, send).then(res => {
        if(res.data.success){
          this.$store.commit("updateUser",res.data)
        }
      });
    }
  },
  mounted: {},
  components: {
    Header 
  },
  computed: {
    user() {
      return this.$store.getters.currentUser;
    }
  }
};
</script>
