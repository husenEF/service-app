<template>
  <div class="container-fluid">
    <div class="row">
      <Header title="User" :back="url"/>
    </div>
    <div class="row user-index justify-content-center">
      <div class="col-10 col-md-8">
        <router-view @back="onBack"></router-view>
      </div>
    </div>
  </div>
</template>

<script>
import Header from "../../includes/Header";
export default {
  name: "userIndex",
  components: { Header },
  data() {
    return {
      url: "/"
    };
  },
  methods: {
    onBack(back) {
      this.url = back;
      // console.log("onData vehiclemain", back);
    }
  }
};
</script>