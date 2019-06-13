<template>
  <div class="card">
    <div class="card-header">
      Daftar Pengguna
      <router-link
        to="/user/add"
        class="btn btn-primary btn-sm float-right"
        v-if="getUser.roles=='admin'"
      >Tambah Pengguna</router-link>
    </div>
    <div class="card-body">
      <div class="alert alert-danger" v-if="error!==''">
        <p class="mb-0">
          <span>{{error}}</span>
        </p>
      </div>
      <div class="clearfix filter">
        <p>
          <button
            class="btn btn-info"
            type="button"
            data-toggle="collapse"
            data-target="#collapseExample"
            aria-expanded="false"
            aria-controls="collapseExample"
          >
            Filter Data
            <FilterIcon/>
          </button>
        </p>
        <div class="collapse" id="collapseExample">
          <div class="card card-body">
            <form @submit.prevent="filterHandler">
              <div class="row">
                <div class="col-md-5">
                  <div class="form-group">
                    <label for>Filter</label>
                    <select class="form-control" required v-model="filter.key" name="key">
                      <option value>Pilih</option>
                      <option value="name">Nama</option>
                      <option value="username">Username</option>
                      <option value="roles">Jabatan</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <label for>Kata Kunci</label>
                    <input required type="text" class="form-control" v-model="filter.value">
                  </div>
                </div>
                <div class="col-md-2">
                  <button class="btn btn-primary btn-block" type="submit">
                    <SearchIcon/>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Jabatan</th>
              <th>Ubah</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(u,i) in user" :key="i">
              <td>
                <span
                  v-if="typeof meta.pagination !=='undefined'"
                >{{(i+1)+(meta.pagination.per_page*(meta.pagination.current_page-1))}}</span>
                <span v-else>{{i+1}}</span>
              </td>
              <td>
                {{u.name}}
                <strong>({{u.username}})</strong>
              </td>
              <td>{{u.roles}}</td>
              <td>
                <div class="btn-group" role="group" aria-label="Button list">
                  <router-link :to="'/user/'+u.id" class="btn btn-info btn-sm" title="Rubah User">
                    <EyeIcon/>
                  </router-link>
                  <button
                    class="btn btn-danger btn-sm"
                    v-if="u.id!==1"
                    v-on:click="deleteUser(u.id)"
                  >
                    <XIcon/>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer" v-if="typeof meta.pagination !=='undefined'">
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
import { EyeIcon, XIcon, SearchIcon, FilterIcon } from "vue-feather-icons";
export default {
  name: "userList",
  components: {
    EyeIcon,
    XIcon,
    SearchIcon,
    FilterIcon
  },
  created() {
    this.getData("/api/v1/user");
    this.$emit("back", "/");
  },
  data() {
    return {
      user: {},
      meta: {},
      filter: {
        key: "",
        value: ""
      },
      error: ""
    };
  },
  computed: {
    getUser() {
      return this.$store.getters.currentUser;
    }
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
    },
    deleteUser(id) {
      this.$swal({
        title: "Anda Yakin?",
        text: "Anda akan menghapus data ini?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Hapus!"
      }).then(res => {
        if (res.value) {
          axios.delete("/api/v1/user/delete/" + id).then(res => {
            this.getData(
              "/api/v1/user?page=" + this.meta.pagination.current_page
            );
          });
        } else {
          // console.log("cancel");
        }
      });
    },
    filterHandler() {
      axios
        .post("/api/v1/user/filter", this.filter)
        .then(res => {
          const { data } = res;
          if (data.success) {
            const UserKey = Object.keys(data.data).filter(e => e !== "meta");
            this.user = UserKey.map(e => data.data[e]);
            if (data.data.hasOwnProperty("meta")) {
              this.meta = data.data.meta;
            } else {
              this.meta = {};
            }
          }
        })
        .catch(err => {
          // console.log(err.response.data);
          this.error = err.response.data.message;
        });
    }
  }
};
</script>

<style lang="scss" scoped>
.filter {
  form {
    button {
      margin-top: 28px;
    }
  }
}

@media only screen and (max-width: 760px),
  (min-device-width: 768px) and (max-device-width: 1024px) {
  /* Force table to not be like tables anymore */
  table,
  thead,
  tbody,
  th,
  td,
  tr {
    display: block;
  }

  thead {
    tr {
      position: absolute;
      top: -9999px;
      left: -9999px;
    }
  }
  tbody {
    tr {
      border-left: 1px #eee solid;
      border-right: 1px #eee solid;
      &:nth-child(odd) {
        background-color: #dedede;
      }
    }
    td {
      /* Behave  like a "row" */
      border: none;
      border-bottom: 1px solid #eee;
      position: relative;
      padding-left: 40%;
      &::before {
        /* Now like a table header */
        position: absolute;
        /* Top/left values mimic padding */
        top: 6px;
        left: 6px;
        width: 45%;
        padding-right: 10px;
        white-space: nowrap;
      }
    }
  }

  td:nth-of-type(1):before {
    content: "No";
  }
  td:nth-of-type(2):before {
    content: "Nama";
  }
  td:nth-of-type(3):before {
    content: "Jabatan";
  }
  td:nth-of-type(4):before {
    content: "";
  }
  td:nth-of-type(5):before {
    content: "";
  }
}
</style>
