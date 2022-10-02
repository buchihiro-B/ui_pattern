<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/* 認証を使う */
use Auth;
/* 必要なモデルをuseする */
use App\Models\Recipe;

class RecipeController extends Controller
{
    /**
	 * バリデーションのルール
	 */
	protected $validationRules = [
		"name" => ["required", "string"],
		"url" => ["nullable", "url"],
		"description" => ["nullable","string"]
	];

   function __construct(){
		$this->middleware('auth');
	}

   function show(Request $request, $id){
		$recipe = Recipe::find($id);
		if(!$recipe){
			return back()->withError("このレシピは編集できません");
		}
		return view("recipe.recipe_detail",["recipe" => $recipe]);
	}

	function form(Request $request, $id){
		$recipe = Recipe::find($id);
		if(!$recipe){
			return back()->withError("このレシピは編集できません");
		}
		
		//自分以外を編集
		if(false == $this->isEditable($recipe)){
			return back()->withError("このレシピは編集できません");
		}
		return view("recipe.recipe_edit_form",["recipe" => $recipe]);
	}

	function update(Request $request, $id){
		
		$recipe = Recipe::find($id);
		if(!$recipe){
			return back()->withError("このレシピは編集できません");
		}
		//自分以外を編集
		if(false == $this->isEditable($recipe)){
			return back()->withError("このレシピは編集できません");
		}
		//入力値の受け取り
		$validatedData = $request->validate($this->validationRules);
		//更新
		$recipe->update($validatedData);
		
		return redirect()->action(__CLASS__ . "@show",[
			"id" => $id
		])->withStatus("更新しました");
	}

	/**
	 * 編集可能かどうか
	 */
	private function isEditable(Recipe $recipe) : bool{
		return $recipe->user_id == Auth::id();
	}
}
