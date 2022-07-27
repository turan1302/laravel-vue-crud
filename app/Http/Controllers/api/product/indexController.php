<?php

namespace App\Http\Controllers\api\product;

use App\Http\Controllers\Controller;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use DataTables;


class indexController extends Controller
{
    public function index(){
        $query = ProductModel::select("product_id","product_name","product_price","product_status");

        $data = DataTables::of($query)
            ->addIndexColumn()
            ->addColumn("product_status", function ($query) {
                $durum = ($query->product_status==1) ? 'checked' : '';
                return "<label class='switch'>
                        <input type='checkbox' class='isActive' data-id='$query->product_id' $durum>
                         <span class='slider round'></span>
                        </label>";
            })
            ->addColumn("actions", function ($query) {

                $edit = "<a href='javascript:void(0)' data-toggle='modal' data-id='$query->product_id'  class='btn btn-primary btn-sm editProduct'><i class='fa fa-eye'></i> Düzenle</a>";
                $update = "<button type='button' class='btn btn-danger btn-sm isDelete' data-id='$query->product_id'><i class='fa fa-times'></i> Sil</button>";

                return $edit . " " . $update;
            })
            ->rawColumns(["product_status", "actions"])
            ->make(true);
        return $data;
    }

    /** YENI URUN EKLEME KISMI AYARLAMASI **/
    public function save_product(Request $request){
        $data = $request->except("_token");
        $insert = ProductModel::create($data);

        $alert = [];
        if ($insert){
            $alert = [
                "type" => "success",
                "title" => "İşlem Başarılı",
                "text" => "Ürün Başarıyla Eklendi"
            ];
        }else{
            $alert = [
                "type" => "error",
                "title" => "İşlem Başarısız",
                "text" => "Ürün Eklenirken Hata Oluştu"
            ];
        }

        return response()->json($alert);
    }

    /** URUN SILME KISMI AYARLANAMSI **/
    public function delete(ProductModel $item){
        $sonuc = $item->delete();

        $alert = [];
        if ($sonuc){
            $alert = [
                "type" => "success",
                "title" => "Başarılı",
                "text" => "İşlem Başarılı"
            ];
        }else{
            $alert = [
                "type" => "error",
                "title" => "Hata",
                "text" => "İşlem Başarısız"
            ];
        }

        return response()->json($alert);
    }

    // AKTIF PASIF OLAYI
    public function isActiveSetter(Request $request,ProductModel $item){
        $data = ($request->data=="true") ? 1 : 0;
        $item->update(array(
            "product_status" => $data
        ));
    }

    // KAYIT GETIRME OLAYI
    public function edit(ProductModel $item){
        return response()->json($item);
    }

    // KAYIT GUNCELLEME OLAYI
    public function update(Request $request,ProductModel $item){
        $data = $request->except("_token");
        $sonuc = $item->update($data);

        $alert = [];
        if ($sonuc){
            $alert = [
                "type" => "success",
                "title" => "Başarılı",
                "text" => "İşlem Başarılı"
            ];
        }else{
            $alert = [
                "type" => "error",
                "title" => "Hata",
                "text" => "İşlem Başarısız"
            ];
        }

        return response()->json($alert);
    }
}
