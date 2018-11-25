<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable=['code','name','url','image_url'];
    public function users()
    //want haveの両方のuserを取得
    {
        return $this->belongsToMany(User::class)->withPivot('type')->withTimeStamps();
    }
    
    public function want_users()
    //wantのみuserを取得する
    {
        return $this->users()->where('type','want');
        //typeがwantのuserを絞り込む
        
    }
    
      public function have_users()
    //haveのみuserを取得する
    {
        return $this->users()->where('type','have');
    }
}
