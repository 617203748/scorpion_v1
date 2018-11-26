<?php
	/**
 	* 功能说明：<购物车类>
 	* ============================================================================
 	* 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
 	* 开发团队：太原锐意鹏达科技有限公司
	*/
	if(!defined('RYPDINC')) exit("Request Error!!!");

	class Cart {
		private static $instance = null;
		private $items = array();
		
		final protected function __construct(){
			
		}
		final protected function __clone(){}

		// 获取实例
		protected static function getInstance(){
			if(!(self::$instance instanceof self)){
				self::$instance = new self();
			}

			return self::$instance;
		}

		// 把购物车的单例对象放到session里
		public static function getCart(){
			if(!isset($_SESSION["lglp_shop_web_cart"]) || !($_SESSION["lglp_shop_web_cart"] instanceof self)){
				
				$_SESSION["lglp_shop_web_cart"] = self::getInstance();
			}
			
			return $_SESSION["lglp_shop_web_cart"];
		}

		/*
         * 功能：判断某商品是否存在
		 */
		public function hasItem($cart_id){
			return array_key_exists($cart_id, $this->items);
		}

		/*
         * 功能：判断某商品是否重复
		 */
		public function hasSameItem($model){
			if($this->items){

				foreach($this->items as $key=>$value){
					if($value->goods_id == $model->goods_id && 
						$value->goods_price == $model->goods_price && 
						$value->specification_name == $model->specification_name && 
						$value->color_name == $model->color_name &&
						$value->store_id == $model->store_id){

						$value->goods_number += $model->goods_number;
						$value->goods_total_integral = $value->goods_number * $value->goods_integral;
						$value->goods_total_price = $value->goods_number * $value->goods_price;


						$this->items[$key]->goods_number = $value->goods_number;
						$this->items[$key]->goods_total_integral = $value->goods_total_integral;
						$this->items[$key]->goods_total_price = $value->goods_total_price;

						return true;
						break;
					}
				}
			}

			return false;
		}

		/*
		 * 功能：修改购笺车中的商品数量
		 * 参数：
		 * $cart_id 购物车id
		 * $number 修改数量
		 */
		public function modifyNumber($cart_id, $number = 1){
			
			$this->items[$cart_id]->goods_number = $number;
			$this->items[$cart_id]->goods_total_integral = $number * $this->items[$cart_id]->goods_integral;
			$this->items[$cart_id]->goods_total_price = $number * $this->items[$cart_id]->goods_price;
		}

		/*
		 * 功能：添加商品
		 * 参数：购物车类
		 */
		public function addItem($model, $key = null){
			// 如果存在，累加数量
			if($this->hasSameItem($model)){
				return;
			}

			$item = null;
			//校区id
			$item->store_id= $model->store_id;
			//商品id
			$item->goods_id= $model->goods_id;
			//商品货号
			$item->goods_code = $model->goods_code;
			//商品名称 			
			$item->goods_name = $model->goods_name;
			//商品型号名			
			$item->specification_name = $model->specification_name;
			//商品颜色名	
			$item->color_name= $model->color_name;
			//商品单价		
			$item->goods_price =sprintf("%.1f",substr(sprintf("%.2f", $model->goods_price), 0, -1)); // $model->goods_price;
			//商品积分
			$item->goods_integral = $model->goods_integral;
			//商品略缩图		
			$item->small_pic= $model->small_pic;
			//商品数量	
			$item->goods_number = $model->goods_number;
			//商品积分小计
			$item->goods_total_integral= $model->goods_total_integral;
			//商品价格小计	
			$item->goods_total_price=sprintf("%.1f",substr(sprintf("%.2f",  $model->goods_total_price), 0, -1));// $model->goods_total_price;
			//是否是套件
			$item->is_external= $model->is_external;
			//购物类型,例1团购,2.特卖	
			$item->cart_type= $model->cart_type;
		
			if($key){
				$this->items[$key] = $item;
			}
			else{
				$this->items[] = $item;
			}
		}

		//删除一条
		public function delItem($cart_id){
			if($this->hasItem($cart_id)){
				unset($this->items[$cart_id]);
			}
		}

		//商品数量加1
		public function incNumber($cart_id){
			if($this->hasItem($cart_id)){
				$this->items[$cart_id]->goods_number += 1;
				//积分小计
				$this->items[$cart_id]->goods_total_integral = $this->items[$cart_id]->goods_number * $this->items[$cart_id]->goods_integral;
				//价格小计
				$this->items[$cart_id]->goods_total_price = $this->items[$cart_id]->goods_number * $this->items[$cart_id]->goods_price;
			}
		}

		//商品数量减1
		public function decNumber($cart_id){
			if($this->hasItem($cart_id)){
				$this->items[$cart_id]->goods_number -= 1;
				$this->items[$cart_id]->goods_total_integral = $this->items[$cart_id]->goods_number * $this->items[$cart_id]->goods_integral;
				$this->items[$cart_id]->goods_total_price = $this->items[$cart_id]->goods_number * $this->items[$cart_id]->goods_price;

				if($this->items[$cart_id]->goods_number < 1){
					$this->items[$cart_id]->goods_number = 1;
					$this->items[$cart_id]->goods_total_integral = $this->items[$cart_id]->goods_integral;
					$this->items[$cart_id]->goods_total_price = $this->items[$cart_id]->goods_price;
				}
				
			}
		}

		/*
    	 * 功能：清空购物车
		 */
		public function clear(){
			$this->items = array();
		}

		
		//获取购物车条目数
		public function getTotalCartItem(){
			return count($this->items);
		}

		//获取商品总数
		public function getTotalNumber(){
			if($this->items){
				
				$sum = 0;
				foreach($this->items as $value){
					$sum += $value->goods_number;
				}

				return $sum;
			}

			return 0;
		}

		//获取单条价格小计
		public function getItemTotalPrice($cart_id){
			if($this->hasItem($cart_id)){
				return $this->items[$cart_id]->goods_total_price;
			}

			return 0;
		}

		//获取单条积分小计
		public function getItemTotalIntegral($cart_id){
			if($this->hasItem($cart_id)){
				return $this->items[$cart_id]->goods_total_integral;
			}

			return 0;
		}

		//获取总金额
		public function getTotalPrice(){
			if($this->items){
				$total = 0.0;
				foreach ($this->items as $key => $value) {
					$total += $value->goods_price * $value->goods_number;
				}
				return sprintf("%.1f",substr(sprintf("%.2f", $total), 0, -1)); 
				//return $total;
			}

			return 0;
		}

		//获取总积分
		public function getTotalIntegral(){
			if($this->items){
				$total = 0.0;
				foreach ($this->items as $key => $value) {
					$total += $value->goods_integral * $value->goods_number;
				}
				return $total;
			}

			return 0;
		}

		//获取购物车所有商品
		public function getAllCart(){
			return $this->items;
		}
	}

?>