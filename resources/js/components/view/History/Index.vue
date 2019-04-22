<template>
  <div class="container-fluid">
    <div class="row">
      <Header title="History" :back="url"/>
    </div>
    <div class="row vehicle-index justify-content-center">
      <div class="col-12 col-md-10">
        <router-view @back="onBack"></router-view>
      </div>
    </div>
  </div>
</template>

<script>
import Header from "../../includes/Header";
export default {
  name: "historyMain",
  components: {
    Header
  },
  data() {
    return {
      url: "/"
    };
  },
  methods: {
    onBack(back) {
      this.url = back;
    }
  }
};
</script>
