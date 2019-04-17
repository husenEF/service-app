<template>
  <div class="card">
    <div class="card-header">
      User List
      <router-link to="/user/add" class="btn btn-primary btn-sm float-right">Add User</router-link>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table">
          <thead>
            <th>No</th>
            <th>Nama</th>
            <th>Roles</th>
            <th>Edit</th>
          </thead>
          <tbody>
            <tr v-for="(u,i) in user" :key="i">
              <td>{{(i+1)+(meta.pagination.per_page*(meta.pagination.current_page-1))}}</td>
              <td>{{u.name}}</td>
              <td>{{u.roles}}</td>
              <td>
                <router-link :to="'/user/'+u.id" v-if="u.id!==1">Edit</router-link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer">
      <nav aria-label="Page navigation example" v-if="(meta.pagination.total_pages)>1">
        <ul class="pagination mb-0">
          <li v-for="(link,i) in meta.pagination.links" :key="i" class="page-item">
            <button type="button " class="page-link" v-on:click="getData(link)">{{i}}</button>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script>
export default {
  name: "userList",
  created() {
    this.getData("/api/v1/user");
    this.$emit("back", "/");
  },
  data() {
    return {
      user: {},
      meta: {}
    };
  },
  methods: {
    getData(url) {
      axios.get(url).then(res => {
        const { data } = res;
        if (data.success) {
          const UserKey = Object.keys(data.data).filter(e => e !== "meta");
          this.user = UserKey.map(e => data.data[e]);
          this.meta = data.data.meta;
        }
      });
    }
  }
};
</script>