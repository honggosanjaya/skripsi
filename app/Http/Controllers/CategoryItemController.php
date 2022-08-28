<?php

namespace App\Http\Controllers;

use App\Models\CategoryItem;
use Illuminate\Http\Request;

class CategoryItemController extends Controller
{
  public function categoryIndex(){
    $categories = CategoryItem::paginate(10);
    return view('supervisor.categoryitem.index',[
        'categories' => $categories            
    ]);
  }

  public function categorySearch(){
    $categories =  CategoryItem::where(strtolower('nama'),'like','%'.request('cari').'%')->paginate(10);
   
    return view('supervisor.categoryitem.index',[
        'categories' => $categories
    ]);
  }

  public function categoryCreate(){
    return view('supervisor.categoryitem.addcategoryitem');
  }

  public function categoryStore(Request $request){
    $request->validate([
        'nama' => 'required|max:255',
        'keterangan' => 'required'            
    ]);

    CategoryItem::create([
        'nama' => $request->nama,
        'keterangan' => $request->keterangan
    ]); 
    
    return redirect('/supervisor/category')->with('addCategorySuccess','Tambah Category Item Berhasil');
  }

  public function categoryEdit(CategoryItem $category){
    return view('supervisor.categoryitem.editcategoryitem',[
      'category' => $category
    ]);
  }

  public function categoryUpdate(Request $request, CategoryItem $category){
      $rules = $request->validate([
          'nama' => 'required|max:255',
          'keterangan' => 'required'                   
      ]);

      CategoryItem::Where('id', $category->id)
          ->update($rules);

      return redirect('/supervisor/category')->with('updateCategorySuccess','Update Category Item Berhasil');        
  }
}
