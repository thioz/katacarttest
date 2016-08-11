<?php
namespace App\Rules\Asserts;

use Kata\Cart\Rule\Assert;

class MinimumQuantity extends Assert
{
	protected $productId;
	protected $quantity;
	
	public function setRules($productId, $quantity){
		$this->productId = $productId;
		$this->quantity = $quantity;
		return $this;
	}
	
	protected function call($rule, $item, $context)
	{
		$items = $context->container()->cart()->items();
		$cnt = $this->getItemcountByProductId($items, $this->productId);
 
		if($cnt >= $this->quantity){
			return true;
		}
		return false;
	}
	
	protected function getItemcountByProductId($items, $productId){
		$cnt = 0;
		foreach($items as $cartitem){
			if($cartitem->productId() == $productId){
				$cnt+=$cartitem->quantity();
			}
		}
		return $cnt;
	}
	
}