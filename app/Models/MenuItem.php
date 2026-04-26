<?php

namespace App\Models;

use App\BelongsToActivity;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use BelongsToActivity;
    protected $fillable = [
        'name', 
        'description', 
        'image', 
        'price', 
        'cost', 
        'is_vat_exempt', 
        'menu_item_category_id'
    ];

    public function category(){
        return $this->belongsTo(
            MenuItemCategory::class,
            'menu_item_category_id',
            'id'
        );
    }

    public function ingredients(){
        return $this->hasMany(
            Ingredient::class,
            'menu_item_id',
            'id'
        );
    }

    public function customizations(){
        return $this->hasMany(
            MenuItemCustomization::class,
            'menu_item_id',
            'id'
        );
    }

    public function menu_item_discounts(){
        return $this->belongsToMany(
            Discount::class,
            'menu_item_discounts',
            'menu_item_id',
            'discount_id'
        );
    }
    public function itemOrders()
    {
        return $this->hasMany(ItemOrder::class);
    }

}
