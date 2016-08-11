<?php

namespace App\Rules;

use Kata\Cart\Item;
use Kata\Cart\Rule;

class DiscountQuantity extends Rule
{

	protected $targetQuantity = 1;
	protected $discountQuantity = false;
	protected $discountAmount = false;
	protected $numDiscount = 0;

	public function __construct($productId = false)
	{
		
		parent::__construct($productId);
		$this->setup();
	}

	protected function setup()
	{
		$local = $this;
		$this->assert(
			function($rule, $item, $context) use ($local) {
				$items = $context->container()->cart()->items();
				$cnt = 0;
				foreach ($items as $item)
				{
					if ($rule->applies($item))
					{
						$cnt+= $item->quantity();
					}
				}

				$discounts = floor($cnt / $local->getTargetQuantity());
				$local->setNumDiscount( $discounts );
				$rule->disable();
				return $discounts > 0;
			}
		);
		
		$this->success( 
			function($rule, $item, $context) use ($local) {
				$discounts = $local->getNumDiscount();
				
				$discountItem = new Item();
				$discountItem->setProductDescription('Korting : '.$item->description())
					->setQuantity($discounts)
					->setUnitDescription('stuk');
				if($local->getDiscountQuantity()){
					$discountItem->setUnitPrice( -1 * $item->price());
				}
				elseif($local->getDiscountAmount()){
					$discountItem->setUnitPrice( -1 * $local->getDiscountAmount());
				}
				else{
					return;
				}
					
				$context->container()->cart()->addItem($discountItem);
					
			}
		);
	}
	
	/**
	 * 
	 * @param type $quantity
	 * @return DiscountQuantity
	 */
	public function setNumDiscount($quantity){
		$this->numDiscount = $quantity;
		return $this;
	}

	/**
	 * 
	 * @param type $quantity
	 * @return DiscountQuantity
	 */
	public function setTargetQuantity($quantity)
	{
		$this->targetQuantity = $quantity;
		return $this;
	}

	/**
	 * 
	 * @param type $quantity
	 * @return DiscountQuantity
	 */
	public function setDiscountQuantity($quantity)
	{
		$this->discountQuantity = $quantity;
		return $this;
	}
	
	public function setDiscountAmount($amount){
		$this->discountAmount = $amount;
		return $this;
	}

	public function getTargetQuantity()
	{
		return $this->targetQuantity;
	}

	public function getDiscountQuantity()
	{
		return $this->discountQuantity;
	}

	public function getDiscountAmount()
	{
		return $this->discountAmount;
	}
	
	public function getNumDiscount(){
		return $this->numDiscount;
	}

}
