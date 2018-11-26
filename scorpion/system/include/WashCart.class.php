<?php
	/**
 	* 功能说明：<水果购物车类>
 	* ============================================================================
 	* 版权所有：山西蒲公英生活商贸有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
 	* 开发团队：蒲公英技术部
	*/
	if(!defined('RYPDINC')) exit("Request Error!!!");

	class WashCart {
		private static $instance = null;
		private $items = array();
		final public function __construct()
		{

		}

		final protected function __clone()
		{

		}

		// 获取实例
		protected static function getInstance()
		{
			
			if(!(self::$instance instanceof self))
			{
				self::$instance = new self();
			}

			return self::$instance;
		}

		//把购物车的单例对象放到session里
		public static function getCart()
		{
			if(!isset($_SESSION["wash_web_cart"]) || !($_SESSION["wash_web_cart"] instanceof self))
			{
				$_SESSION["wash_web_cart"] = self::getInstance();
			}

			return $_SESSION["wash_web_cart"];

		}

		/*
         * 功能：判断某水果是否存在
		 */
		public function hasItem($id)
		{
			foreach($this->items as $key=>$value)
			{
               if($value->id==$id)
               {
                  return $key;
               }          
			}
			return false;
		}

		/*
         * 功能：判断某商品是否重复
		 */
		public function hasSameItem($model)
		{

			if($this->items)
			{

				foreach($this->items as $key=>$value)
				{

					if(
	                    $value->uniid == $model->dto->uniid && 
	                    $value->id == $model->dto->id )
					{
						$value->number += 1;
						$value->total_price = $value->number * $value->price;

						return true;
						break;
					}
				}
			}

			return false;
		}


		/*
		 * 功能：修改购物车中的商品数量
		 * 参数：
		 * $cartid 购物车id
		 * $number 修改数量
		 */
		public function modifyNumber($id,$number = 1)
		{
			  $key=$this->hasItem($id);   
			  if($key!==false)
			  {
			  	  $this->items[$key]->number = $number;
			      $this->items[$key]->total_price = $number * $this->items[$key]->price;     
			  }			 
			  return $this->items;
		}


		/*
		 * 功能：添加商品
		 * 参数：购物车类
		 */
		public function addItem($model, $key = null)
		{
			//如果存在，累加数量
			if($this->hasSameItem($model))
			{
				return;
			}

			$item = null;
			//校区id
			$item->uniid= $model->dto->uniid;
			//id
			$item->id= $model->dto->id;
			//名称 			
			$item->type = $model->dto->type;

			//商品单价		
			$item->price =  sprintf("%.1f",substr(sprintf("%.2f", $model->dto->price), 0, -1)); 
            
            //图片
            $item->pic=$model->dto->pic;

			//水果数量	
			$item->number = 1;
			//水果价格小计	
			$item->total_price= sprintf("%.1f",substr(sprintf("%.2f", $model->dto->price), 0, -1));// $model->fruit_total_price;

			if($key)
			{
				$this->items[$key] = $item;
			}
			else
			{
				$this->items[] = $item;
			}

		}

		//删除一条
		public function delItem($id)
		{
            
            $key=$this->hasItem($id);

			if($key!==false)
			{
				unset($this->items[$key]);			
			}

		}

		//商品数量加1
		public function incNumber($id)
		{
            $key=$this->hasItem($id); 
			
			$this->items[$key]->number += 1;
			//价格小计
			$this->items[$key]->total_price = $this->items[$key]->number * $this->items[$key]->price;
			
		}

		//商品数量减1
		public function decNumber($id)
		{
			$key=$this->hasItem($id);

			if($key!==false)
			{
				$this->items[$key]->number -= 1;
				$this->items[$key]->total_price = $this->items[$key]->number * $this->items[$key]->price;

				if($this->items[$key]->number < 1)
				{
					$this->items[$key]->number = 1;
					$this->items[$key]->total_price = $this->items[$key]->price;
				}
			}
		}

		/*
    	 * 功能：清空购物车
		 */
		public function clear()
		{
			$this->items = array();
		}
		
		
		//获取购物车条目数
		public function getTotalCartItem()
		{
			return count($this->item);
		}


		//获取商品总数
		public function getTotalNumber()
		{
			
			if($this->items)
			{
				$sum = 0;
				foreach($this->items as $value)
				{
					$sum += $value->number;
				}
				return $sum;
			}
			return 0;
		}


		//获取单条价格小计
		public function getItemTotalPrice($id)
		{
			$key=$this->hasItem($id);
			if($key!==false)
			{
				return $this->items[$key]->total_price;
			}
			return 0;
		}


		//获取总金额
		public function getTotalPrice()
		{
			if($this->items)
			{
				$total = 0.0;
				foreach ($this->items as $key => $value) 
				{
					$total+=$value->total_price;	
				}
			    return $total;			
			}

			return 0;
		}



		//获取购物车所有商品
		public function getAllCart()
		{
			return $this->items;
		}

	}

?>		