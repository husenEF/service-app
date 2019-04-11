<template>
  <div class="container-fluid">
    <div class="row">
      <Header title="History" back="/"/>
    </div>
    <div class="row vehicle-index justify-content-center">
      <div class="col-10 col-md-8">
        <router-view></router-view>
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
  }
};
</script>
