<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function items()
    {
        return $this->belongsToMany(Item::class)->withPivot('type')->withTimestamps();
    }
    public function want_items()
    {
        return $this->items()->where('type','want');
    }
    public function have_items()
    {
        return $this->items()->where('type','have');
    }
    public function want($itemId)
    {
        $exist=$this->is_wanting($itemId);
        if($exist){
            return false;
        }else{
            $this->items()->attach($itemId,['type'=>'want']);
            return true;
        }
    }
    
    public function have($itemId)
    {
        $exist =$this->is_having($itemId);
        if($exist){
            return false;
        }else{
            $this->items()->attach($itemId,['type'=>'have']);
            return true;
        }
        
    }
    
    public function dont_want($itemId)
    {
        $exist=$this->is_wanting($itemId);
        if($exist){
            \DB::delete("DELETE FROM item_user WHERE user_id = ? AND item_id = ? AND type = 'want'", [$this->id, $itemId]);
        }else{
            return false;
        }
    }
    
    public function dont_have($itemId)
    {
        $exist =$this->is_having($itemId);
        if($exist){
            \DB::delete("DELETE FROM item_user WHERE user_id=? AND item_id=? AND type='have'",[$this->id,$itemId]);
            //DBを直接操作typeカラムがhaveのデータを消す
        }else{
            return false;
        }
    }
    
    
    public function is_wanting($itemIdOrCode)
    {
        if (is_numeric($itemIdOrCode)){
            //is_uumericは文字列を整数化する
            $item_id_exists=$this->want_items()->where('item_id',$itemIdOrCode)->exists();
            //item_idカラムに$itemIdOrCodeがあるかどうか調べる
            return $item_id_exists;
        }else{
            $item_code_exists=$this->want_items()->where('code',$itemIdOrCode)->exists();
            return $item_code_exists;
        }
    }
    
    public function is_having($itemIdOrCode)
    {
        if(is_numeric($itemIdOrCode)){
            $item_id_exists=$this->have_items()->where('item_id',$itemIdOrCode)->exists();
            return $item_id_exists;
        }else{
            $item_code_exists=$this->have_items()->where('code',$itemIdOrCode)->exists();
            return $item_code_exists;
        }
    }
}




