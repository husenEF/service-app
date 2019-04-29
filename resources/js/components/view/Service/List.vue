<template>
  <div class="card">
    <div class="card-header">Daftar Pengecekkan Ban</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Ban</th>
              <th>Ketarangan</th>
              <th>Tanggal di buat</th>
              <th>Lepas Ban?</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(s,i) in service" :key="i">
              <td>
                <span
                  v-if="typeof meta.pagination !=='undefined'"
                >{{(i+1)+(meta.pagination.per_page*(meta.pagination.current_page-1))}}</span>
                <span v-else>{{i+1}}</span>
              </td>
              <td>{{s.tire.merek}}</td>
              <td>
                <p>
                  <strong>Tekanan Angin</strong>
                  : {{s.tekanan_angin}}
                  <br>
                  <strong>Tebal Tapak</strong>
                  : {{s.tebal_tapak}}
                  <br>
                  <strong>Posisi</strong>
                  : {{s.posisi}}
                  <br>
                  <strong>Jarak Km</strong>
                  : {{s.jarakkm}}
                  <br>
                  <strong>Tekanan Angin</strong>
                  : {{s.tekanan_angin}}
                  <br>
                </p>
              </td>
              <td>
                {{s.create_at}}
                <br>
                <p>
                  <small>
                    dibuat oleh :
                    <br>
                    Nama : {{s.user.name}}
                    <br>
                    Jabatan : {{s.user.roles}}
                  </small>
                </p>
              </td>
              <td>{{(s.lepasban)?'Ya':'Tidak'}}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer" v-if="typeof meta.pagination !=='undefined'">
      <nav aria-label="Page navigation example" v-if="(meta.pagination.total_pages)>1">
        <ul class="pagination mb-0">
          <li v-for="(link,i) in meta.pagination.links" :key="i" class="page-item">
            <button type="button " class="page-link" v-on:click="getList(link)">{{i}}</button>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script>
export default {
  name: "serviceList",
  data() {
    return {
      service: {},
      meta: {}
    };
  },
  created() {
    this.getList("/api/v1/service");
  },
  methods: {
    getList(link) {
      axios
        .get(link)
        .then(res => {
          const { data } = res;
          if (data.success) {
            let listId = Object.keys(data.data).filter(i => i !== "meta");
            // console.log("map tireId", tireId);
            this.service = listId.map(i => data.data[i]);
            if (data.data.hasOwnProperty("meta")) {
              this.meta = data.data.meta;
            }
          }
        })
        .catch(err => {
          console.log(err);
        });
    }
  }
};
</script>
