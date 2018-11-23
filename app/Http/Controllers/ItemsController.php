<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Item;
class itemsController extends Controller
{
    public function create()
    {
        $keyword=request()->keyword; //フォームから送信されてくる検索ワードの受け取り
        $items=[]; //$itemsを空の配列として初期化
        if($keyword){
            $client=new \RakutenRws_Client(); //クライアントの作成
            $client->setApplicationId(env('RAKUTEN_APPLICATION_ID')); //アプリIDの取得
            $rws_response=$client->execute('IchibaItemSearch',[
                'keyword'=>$keyword,
                'imageFlag'=>1, //画像があるもの
                'hits'=>20, //20件ずつ表示
                ]);
                //itemモデルのインスタンスを作成するが保存はしない。
                //保存は自分のモノリストに追加登録したときのみ行う
                foreach($rws_response->getData()['Items']as $rws_item){
                    $item=new Item();
                    $item->code=$rws_item['Item']['itemCode'];
                    $item->name=$rws_item['Item']['itemName'];
                    $item->url=$rws_item['Item']['itemUrl'];
                    $item->image_url=str_replace('?_ex=128x128','',$rws_item['Item']['mediumImageUrls'][0]['imageUrl']);
                    //str_replace は文字列を置き換える。第三引数から第一引数を見つけ出して、第二引数に置換する関数
                    $items[]=$item;
                }
        }
        return view('items.create',[
            'keyword'=>$keyword,
            'items'=>$items,
        ]);
    }   
}
