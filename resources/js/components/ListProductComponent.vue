<template>
    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Ürün Listesi</div>

                    <div class="card-body">
                        <table class="table table-bordered yajra-datatable" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ürün Adı</th>
                                <th>Ürün Fiyat</th>
                                <th>Ürün Durum</th>
                                <th>İşlemler</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- MODAL KISMI AYARLANMASI -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ product_name }} Kaydını Güncelle</h5>
                        </div>

                        <!-- ERROR MESAJI BU KISIMDA YER ALACAK -->
                        <div class="col-md-12 alert alert-danger text-center" v-if="errors.length > 0">
                            <ul v-for="error in errors">
                                <li>{{ error }}</li>
                            </ul>
                        </div>

                        <!-- ALERT MESAJININ BASTIRILMASINI GERCEKLESTIRELIM -->
                        <div :class="alert_class" v-if="alert.length > 0">
                            <b>{{ alert[0].title }}</b>
                            <br>
                            <p>{{ alert[0].text }}</p>
                        </div>

                        <form action="" method="POST" @submit.prevent="updateProduct()">
                            <div class="modal-body">
                                <div class="container">
                                    <div class="col-md-12">
                                        <label for="">Ürün Adı</label>
                                        <input type="text" class="form-control" v-model="product_name">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="">Ürün Fiyatı</label>
                                        <input type="number" step="0.01" class="form-control" v-model="product_price">
                                    </div>
                                    <input type="hidden" v-model="product_id">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Kaydı Güncelle</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            product_id : '',
            product_name: '',
            product_price: '',
            errors: [],
            alert: [],  // GELEN DURUM MESAJLARINI ALCAZ
            alert_class : '',  // ALERT CLASS KISMI AYARLANACAK
        }
    },
    mounted() {

        this.getTable();  // URUN TABLOSUNU GETIRDIK
        var self = this;  // GENEL KISMI ALDIK

        /** SILME KISMI AYARLANMASI **/
        $(".yajra-datatable").on("click", ".isDelete", function () {
            var id = $(this).data("id");
            var url = "http://127.0.0.1:8000/api/product/" + id + "/delete";

            Swal.fire({
                title: 'Dikkat!',
                text: "Kayıt silinecektir. Onaylıyor Musunuz ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Evet, Kaydı Sil!',
                cancelButtonText: 'Vazgeç'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.get(url).then((res) => {
                        Swal.fire({
                            icon: res.data.type,
                            title: res.data.title,
                            text: res.data.text,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(function () {
                            location.reload();
                        });
                    });
                }
            });
        });

        /** URUN AKTIF PASIF OALYINI AYARLAYALIM **/
        $(".yajra-datatable").on("change", ".isActive", function () {
            var id = $(this).data("id");
            var data = $(this).prop("checked");
            var url = "http://127.0.0.1:8000/api/product/" + id + "/active";

            axios.post(url, {
                data: data
            });
        });

        /** EDIT KISMI AYARLANMASI **/
        $(".yajra-datatable").on("click", ".editProduct", function () {
            var id = $(this).data("id");
            var url = "http://127.0.0.1:8000/api/product/"+id+"/edit";

            axios.get(url).then((res)=>{
                var data = res.data;
                self.product_id = data.product_id;
                self.product_name = data.product_name;
                self.product_price = data.product_price;
            });

            $('#myModal').modal('show');
        });

    },

    methods: {
        getTable() {
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    type: "GET",
                    url: "http://127.0.0.1:8000/api/product",
                    error: function (e) {
                        console.log(e);
                    }
                },
                columns: [
                    {data: 'product_id', name: 'product_id'},
                    {data: 'product_name', name: 'product_name'},
                    {data: 'product_price', name: 'product_price'},
                    {data: 'product_status', name: 'product_status'},
                    {data: 'actions', name: 'actions'},
                ],
            });
        },

        updateProduct() {
            this.errors = [];
            if (this.product_name == ""){
                this.errors.push("Ürün Adı Boş Bırakılamaz");
            }

            if (this.product_price==""){
                this.errors.push("Ürün Fiyatı Boş Bırakılamaz");
            }


            /** HATA KISMI VAR MI KONTROL EDELIM BI **/
            if (this.errors.length == 0){
                var id = this.product_id
                var url = "http://127.0.0.1:8000/api/product/"+id+"/update";

                axios.post(url,{
                    product_name : this.product_name,
                    product_price : this.product_price
                }).then((res)=>{

                    this.alert = [];
                    this.alert_class = "";
                    this.alert_class = "col-md-12 alert alert-"+res.data.type+" text-center";

                    if (res.status == 200) {
                        this.alert.push(res.data);

                        setTimeout(function(){
                            location.reload();
                        }, 2000);
                    } else {
                        this.alert.push(res.data);
                    }

                });
            }
        }
    }
}
</script>
