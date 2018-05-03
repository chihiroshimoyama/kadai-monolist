<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function items()
    {
        return $this->belongsToMany(Item::class)->withPivot('type')->withTimestamps();
    }
    
    public function want_items()
    {
        return $this->items()->where('type', 'want');
    }
    
    public function want($itemId)
    {
        //既にWantしているかの確認
        $exist = $this->is_wanting($itemId);
        
        if ($exist) {
            //既にWantしていれば何もしない
            return false;
        } else {
            //未WantであればWantする
            $this->items()->attach($itemId, ['type' => 'want']);
            return true;
        }
    }
    
    public function dont_want($itemId)
    {
        //既にWantしているか確認
        $exist = $this->is_wanting($itemId);
        
        if ($exist) {
            //既にWantしていればWantを外す
            \DB::delete("DELETE FROM item_user WHERE user_id = ? AND item_id = ? AND type = 'want'", [\Auth::user()->id, $itemId]);
        } else {
            //　未Wantであれば何もしない
            return false;
        }
    }
    
    public function is_wanting($itemIdOrCode)
    {
        if (is_numeric($itemIdOrCode)) {
            $item_id_exists = $this->want_items()->where('item_id', $itemIdOrCode)->exists();
            return $item_id_exists;
        } else {
            $item_code_exists = $this->want_items()->where('code', $itemIdOrCode)->exists();
            return $item_code_exists;
        }
    }
    
    public function have_items()
    {
        return $this->items()->where('type', 'have');
    }
    
    public function have($itemId)
    {
        //既にHaveしているかの確認
        $exist = $this->is_having($itemId);
        
        if($exist) {
            //既にHaveしていれば何もしない
            return false;
        } else {
            //未HaveであればHaveする
            $this->items()->attach($itemId, ['type' => 'have']);
            return true;
        }
    }
    
    public function dont_have($itemId)
    {
        //既にHaveしているかの確認
        $exist = $this->is_having($itemId);
        
        if ($exist) {
            //既にHaveしていればHaveを外す
            \DB::delete("DELETE FROM item_user WHERE user_id = ? AND item_id = ? AND type = 'have'", [\Auth::user()->id, $itemId]);
        } else {
            //未Haveであれば何もしない
            return false;
        }
    }
    
    public function is_having($itemIdOrCode)
    {
        if (is_numeric($itemIdOrCode)) {
            $item_id_exists = $this->have_items()->where('item_id', $itemIdOrCode)->exists();
            return $item_id_exists;
        } else {
            $item_code_exists = $this->have_items()->where('code', $itemIdOrCode)->exists();
            return $item_code_exists;
        }
    }
}
