<template>
    <div class="container">
        <div class="row justify-content-center mt-3">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Yeni Ürün Ekle</div>

                    <div class="card-body">

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

                        <form ref="newProductForm" method="POST" @submit.prevent="yeniUrunEkle()">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="">Ürün Adı</label>
                                    <input type="text" class="form-control" v-model="product_name">
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label for="">Ürün Fiyatı</label>
                                    <input type="number" step="0.01" class="form-control" v-model="product_price">
                                </div>
                            </div>

                            <div class="form-group mt-2">
                                <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Kaydet
                                </button>
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
            product_name: '', // URUN ADINI ALCAZ
            product_price: '',  // URUN ADINI YAZDIRCAZ
            errors: [], // URUN HATALARINI ALCAZ
            alert: [],  // GELEN DURUM MESAJLARINI ALCAZ
            alert_class : '',  // ALERT CLASS KISMI AYARLANACAK
        }
    },
    mounted() {
        console.log("Ürün Ekleme Formu Geldi");
    },
    methods: {
        yeniUrunEkle() {
            this.errors = [];
            if (this.product_name == "") {
                this.errors.push("Ürün Adı Boş Bırakılamaz");
            }

            if (this.product_price == "") {
                this.errors.push("Ürün Fiyatı Boş Bırakılamaz");
            }

            /** HERHANGI BIR HATA YOKSA **/
            if (this.errors.length == 0) {
                axios.post("http://127.0.0.1:8000/api/product/save", {
                    product_name: this.product_name,
                    product_price: this.product_price
                }).then((res) => {

                    this.$refs.newProductForm.reset(); // FORM RESETLEDIK

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
