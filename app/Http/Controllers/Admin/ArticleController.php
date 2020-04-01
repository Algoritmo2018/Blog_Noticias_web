<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
 use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateArticle;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreUpdateArticle;

class ArticleController extends Controller
{

    public function create(StoreUpdateArticle $request)
    {

        $imagePath = $request->file('image')->store('public/img/article');

        $image = new Article([
            'title' => $request->get('title'),
            'caption' => $request->get('caption'),
            'category_id' => $request->get('category_id'),
            'content' => $request->get('content'),
            'image_path' => $request->file('image')->hashName(),
        ]);
        $image->save();

        return redirect()->route('cadastrar_artigo');
    }

    public function EditarArtigo(Category $category, string $id, Article $articles)
    {
         if (!$articles = $articles->where('id', $id)->first()) {
             return back();
         }
        $categories = $category->all();
        return view('admin/editar_artigo', compact('categories', 'articles'));
    }

    public function UpdateArticle(UpdateArticle $request, Category $category, string $id, Article $articles)
    {

        if (!$articles = $articles->find($id)) {
            return back();
        }

        $dados = $request->all();

        if(!empty($dados['imagenew'])){



//Apaga a imagem antiga
            File::delete('storage/img/article/'.$dados['image']);

            //Faz o upload da imagem inserida na input file
            $imagePath = $request->file('imagenew')->store('public/img/article');

//Faz o update do artigo
            $articles->title = $dados['title'];
            $articles->caption = $dados['caption'];
            $articles->category_id = $dados['category_id'];
            $articles->content = $dados['content'];
            $articles->image_path = $request->file('imagenew')->hashName();
           $articles->save();
        }
        else{
//Faz o update do artigo
             $articles->title = $dados['title'];
             $articles->caption = $dados['caption'];
             $articles->category_id = $dados['category_id'];
             $articles->content = $dados['content'];
             $articles->image_path = $dados['image'];
            $articles->save();
        }
        return redirect()->route('homeadm');
    }


    public function Destroy(Request $request, Article $articles, string $id)
    {




        // Traz o id da imagem e verifica si o mesmo está cadastrado no banco de dados

        if (!$articles = $articles->find($id)) {
            return back();
        }
        // Delecta a imagem no banco de dados
        $articles->delete();

        // Delecta a imagem x que esta na pasta storage/app/public/img/slides/
        File::delete('storage/img/article/' . $request->input('image'));

        return redirect()->route('homeadm');
    }
}
