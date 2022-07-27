<template>
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ product_name }} Kaydını Güncelle</h5>
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
</template>

<script>
export default {
    name: "UpdateProductModalComponent",
    props: ['product_id', 'product_name', 'product_price'],
    data() {
        return {
            product_name_value : this.$props.product_name,
            product_price_value : '',
            product_id_value : '',
            errors: [],
        }
    },
    mounted() {
        this.product_name_value = this.$props.product_name;
    },
    methods: {
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

                });
            }
        }
    }
}
</script>

<style scoped>

</style>
