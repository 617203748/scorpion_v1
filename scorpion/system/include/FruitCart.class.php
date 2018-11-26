<?php
	/**
 	* 功能说明：<水果购物车类>
 	* ============================================================================
	* 版权所有：山西蒲公英生活商贸有限公司，并保留所有权利。
	* ----------------------------------------------------------------------------
	* 开发团队：蒲公英技术部
	* ----------------------------------------------------------------------------
	* 日期：2015.09.01
	*/
	if(!defined('RYPDINC')) exit("Request Error!!!");

	class FruitCart {
		private static $instance = null;
		private $items = array();
        private $payItems=array();
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
			if(!isset($_SESSION["fruit_web_cart"]) || !($_SESSION["fruit_web_cart"] instanceof self))
			{
				$_SESSION["fruit_web_cart"] = self::getInstance();
			}

			return $_SESSION["fruit_web_cart"];

		}

		/*
         * 功能：判断某水果是否存在
		 */
		public function hasItem($fruitid)
		{
			foreach($this->items as $key=>$value)
			{
               if($value->fruitid==$fruitid)
               {
                  return $key;
               }          
			}
			return false;
		}

		/*
         * 功能：判断某水果是否重复
		 */
		public function hasSameItem($model)
		{

			if($this->items)
			{

				foreach($this->items as $key=>$value)
				{

					if(
	                    $value->uniid == $model->dto->uniid && 
	                    $value->fruitid == $model->dto->fruitid )
					{
						$value->fruit_number += $model->dto->fruit_number;
						$value->fruit_total_price = $value->fruit_number * $value->fruit_price;

						$value->fruit_number = $value->fruit_number;
						$value->fruit_total_price = $value->fruit_total_price;

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
		 * $number 修改数量
		 */
		public function modifyNumber($fruitid,$number = 1)
		{
			  $key=$this->hasItem($fruitid);   
			  if($key!==false)
			  {
			  	  $this->items[$key]->fruit_number = $number;
			      $this->items[$key]->fruit_total_price = $this->items[$key]->fruit_number * $this->items[$key]->fruit_price;     
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
			$item->uniid=Tool::getCookie('sc_id');
			//水果id
			$item->fruitid=$model->dto->fruitid;
			//水果名称 			
			$item->fruitname = $model->dto->fruitname;
			//水果图片
			$item->fruitpic = $model->dto->fruitpic;
			//商品单价		
			$item->fruit_price =sprintf("%.1f",substr(sprintf("%.2f", $model->dto->fruit_price), 0, -1)); 
			//水果数量	
			$item->fruit_number = $model->dto->fruit_number;
			//水果单位
			$item->unitname = $model->dto->unitname;
			//水果价格小计	
			$item->fruit_total_price= sprintf("%.1f",substr(sprintf("%.2f",  $model->dto->fruit_total_price), 0, -1));
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
		public function delItem($fruitid)
		{
            
            $key=$this->hasItem($fruitid);

			if($key!==false)
			{
				unset($this->items[$key]);			
			}

		}

		//商品数量加1
		public function incNumber($fruitid)
		{
			$key=$this->hasItem($fruitid); 
			if($key!==false)
			{
				$this->items[$key]->fruit_number += 1;
				//价格小计
				$this->items[$key]->fruit_total_price = $this->items[$key]->fruit_number * $this->items[$key]->fruit_price;
			
			}		
			
		}

		//商品数量减1
		public function decNumber($fruitid)
		{
			
			$key=$this->hasItem($fruitid);
			if($key!==false)
			{
					
				$this->items[$key]->fruit_number -= 1;
				$this->items[$key]->fruit_total_price = $this->items[$key]->fruit_number * $this->items[$key]->fruit_price;

				if($this->items[$key]->fruit_number < 1)
				{
					$this->items[$key]->fruit_number = 1;
					$this->items[$key]->fruit_total_price = $this->items[$key]->fruit_price;
				}
				
			}

			//Tool::check($this->items);
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
			return count($this->items);
		}

		//获取商品总数
		public function getTotalNumber()
		{
			
			if($this->items)
			{
				$sum = 0;
				foreach($this->items as $value)
				{
					$sum += $value->fruit_number;
				}
				return $sum;
			}
			return 0;
		}

		//获取单条价格小计
		public function getItemTotalPrice($fruitid)
		{
			$key=$this->hasItem($fruitid);
			if($key!==false)
			{
				return $this->items[$key]->fruit_total_price;
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
					$total+=$value->fruit_total_price;	
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

		//获取需要结算的商品  并返回
		public function getPayCart($idstr='')
		{
		   if($idstr)   //$idStr=27$16$3$    水果id
		   {
		   	   //每次要重新向这个数组里边放要结算的商品
		   	   unset($this->payItems);
	           $arr=explode('$',$idstr);

	           foreach($arr as $idStr)
	           {
	           	  if($idStr)
	           	  {
		       	  	  $fruitid=$idStr;

		          	  $key=$this->hasItem($fruitid);
		       
					  if($key!==false)
					  {
						 $this->payItems[$key]=$this->items[$key];
					  }
	           	   }
	           }
		   }
		   
           return $this->payItems;
		}


        //获取要结算的商品的总价
		public function getTotalPayPrice()
		{

            //获取要结算的水果的总价
            if($this->payItems)
			{
			    $total = 0.0;
				foreach ($this->payItems as $key => $value) 
				{
					$total+=$value->fruit_total_price;	
				}
			    return $total;       
				
			}
			
			return 0;
		}

		//获取要结算的商品的数量
		public function getTotalPayNum()
		{

            //获取要结算的水果的总价
            if($this->payItems)
			{
			    $num = 0;
				foreach ($this->payItems as $key => $value) 
				{
					$num+=$value->fruit_number;	
				}
			    return $num;       
				
			}
			
			return 0;
		}
		
	}

?>		