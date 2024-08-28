<?php


namespace Admin\Http\Controllers;


use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Validator;
class ArticleController extends Controller
{
    public function getArticles()
    {
        $data = Article::orderBy('publish_date','desc')->paginate(20);

        return response()->json(['articles' => $data->items(), 'pagination' => $this->getPaginationObject($data)]);
    }


    private function getPaginationObject($data = [])
    {
        $data = new Collection($data->toArray());
        return $data->except('data');
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'answers' => 'required',
            'questions' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect('admin/article/create')
                ->withErrors($validator,'post')
                ->withInput();
        }

        Article::create($request->all());
        return redirect('admin/article');
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'title' => 'required|max:255',
            'answers' => 'required',
            'questions' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator,'post')->withInput();
        }

        $article = Article::where('id',$request->id)->first();

        if($article){
            $article->update($request->except('id'));
            return redirect('admin/article');
        }

        return back();

    }

    public function editView($article_id){
        $article = Article::where('id',$article_id)->first();
        return view('admin-views.faq.edit',compact('article'));
    }

    public function delete($article_id){
        $article = Article::where('id',$article_id)->first();
        if($article){
            $article->delete();
        }
        return response()->json(['success' => true]);

    }
}