<?php
namespace Web\Http\Controllers;

use App\Models\DefaultSetting;
use Illuminate\Http\Request;
use Validator;
use Mail;
use App\Models\Article;
class FaqController extends Controller
{
    public function index(){
       $articles = Article::get();
       return view('faqs',compact('articles'));
    }
}