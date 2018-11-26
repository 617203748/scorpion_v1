<?php
	/**
 	* 功能说明：<购物车类>
 	* ============================================================================
 	* 版权所有：山西蒲公英生活商贸有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
 	* 开发团队：蒲公英技术部
	*/
	if(!defined('RYPDINC')) exit("Request Error!!!");

	class TakeoutCart {
		private static $instance = null;
		private $items = array();
        private $payItems=array();
		final protected function __construct()
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

		// 把购物车的单例对象放到session里
		public static function getCart()
		{
			if(!isset($_SESSION["takeout_web_cart"]) || !($_SESSION["takeout_web_cart"] instanceof self))
			{
				$_SESSION["takeout_web_cart"] = self::getInstance();
			}

			return $_SESSION["takeout_web_cart"];
		}

		/*
         * 功能：判断某商品是否存在
		 */
		public function hasItem($cartid,$goodsid)
		{
      
			foreach($this->items as $key=>$value)
			{
               if($value->comid==$cartid && $value->goodsid==$goodsid)
               {
                  return $key;
               }          
			}
			return false;
		}
		/*
         * 功能：判断某商品是否存在
		 */
        public function hasItemIder($cartid,$goodsid,$tasteid)
		{
        
			foreach($this->items as $key=>$value )
			{
               if($value->comid==$cartid && $value->goodsid==$goodsid && $value->tasteid==$tasteid)
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
                //若商品重复就将添加的商品累加 
				foreach($this->items as $key=>$value)
				{
					if(
                        $value->uniid == $model->dto->uniid && 
                        $value->comid == $model->dto->comid &&
						$value->goodscode == $model->dto->goodscode&&
						$value->tastename == $model->dto->tastename 
					  )
					{
						$value->goods_number += $model->dto->goods_number;
						
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
		 * $cartid 购物车id
		 * $number 修改数量
		 */
		public function modifyNumber($cartid,$goodsid,$number = 1,$tasteid)
		{
			  
			  $key=$this->hasItemIder($cartid,$goodsid,$tasteid);
			  if($key !== false)
			  {
			  	  $this->items[$key]->goods_number = $number;
			      // $this->items[$key]->goods_total_integral = $number * $this->items[$key]->goods_integral;
			      $this->items[$key]->goods_total_price = $number * $this->items[$key]->goods_price;
			      // $this->items[$key]->goods_total_price = sprintf("%.1f",substr(sprintf("%.2f", $goods_total_price), 0, -1));
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
			//商品id
	        $item->goodsid=$model->dto->goodsid;
			//校区id
			$item->uniid= $model->dto->uniid;
			//商家id
			$item->comid= $model->dto->comid;
			//商家名称
			$item->comname=$model->dto->comname;
			//单价
			$item->saleprice=$model->dto->saleprice;
			//商品货号
			$item->goodscode = $model->dto->goodscode;

			//商品名称 			
			$item->goodsname = $model->dto->goodsname;
			//商品口味
			$item->tastename = $model->dto->tastename;
			//口味id
		    $item->tasteid= $model->dto->tasteid;
            //商品数量
            $item->goods_number = $model->dto->goods_number;
			//商家免运费下限金额
			$item->freefreight = $model->dto->freefreight;
			//运费
			$item->freight=$model->dto->freight;
			//运费提醒
			$item->freightclock=$model->dto->freightclock;
			//满减活动 条件金额
			$item->full=$model->dto->full;
            //满减活动 优惠金额
			$item->minus=$model->dto->minus;
			//配送时间
			$item->avgdeliverytime=$model->dto->avgdeliverytime;

			//商品单价		
			$item->goods_price = $model->dto->saleprice; 
			//商品积分
			$item->goods_integral = $model->dto->goodsinte;
			
			//商品价格小计sprintf("%.2f", $model->dto->goods_total_price);
			$item->goods_total_price= $model->dto->goods_total_price;
			
            
			//购物类型,例1团购,2.特卖	
			//$item->cart_type= $model->cart_type;
		
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
		public function delItem($cartid,$goodsid,$tasteid)
		{
            
            $key=$this->hasItemIder($cartid,$goodsid,$tasteid);
            
			if($key!==false)
			{
				unset($this->items[$key]);
			}

		}

		//商品数量加1
		public function incNumber($cart_id)
		{
			if($this->hasItem($cart_id,$goodsid))
			{
				$this->items[$cart_id]->goods_number += 1;
				//积分小计
				$this->items[$cart_id]->goods_total_integral = $this->items[$cart_id]->goods_number * $this->items[$cart_id]->goods_integral;
				//价格小计
				$this->items[$cart_id]->goods_total_price = $this->items[$cart_id]->goods_number * $this->items[$cart_id]->goods_price;
			}
		}

		//商品数量减1
		public function decNumber($cart_id)
		{
			if($this->hasItem($cart_id,$goodscode))
			{
				$this->items[$cart_id]->goods_number -= 1;
				$this->items[$cart_id]->goods_total_integral = $this->items[$cart_id]->goods_number * $this->items[$cart_id]->goods_integral;
				$this->items[$cart_id]->goods_total_price = $this->items[$cart_id]->goods_number * $this->items[$cart_id]->goods_price;

				if($this->items[$cart_id]->goods_number < 1)
				{
					$this->items[$cart_id]->goods_number = 1;
					$this->items[$cart_id]->goods_total_integral = $this->items[$cart_id]->goods_integral;
					$this->items[$cart_id]->goods_total_price = $this->items[$cart_id]->goods_price;
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
			return count($this->items);
		}
        //获取单个商品总数
        public function getGoodsCount($cartid,$goodsid,$tasteid)
        {
        	
             $key=$this->hasItemIder($cartid,$goodsid,$tasteid);
             
             if($key!==false)
			{ 

				$sum = $this->items[$key]->goods_number;
		

				return $sum;
				
			}
			return 0;
        }	
        //获取单个商品价格
        public function getGoodsTotalPrice($cartid,$goodsid,$tasteid)
		{
			$key=$this->hasItemIder($cartid,$goodsid,$tasteid);
			if($key!==false)
			{
				return $this->getGoodsCount($cartid,$goodsid,$tasteid)*$this->items[$key]->saleprice;
			}
			return 0;
           
		}
		//获取商品总数
		public function getTotalNumber()
		{
			if($this->items)
			{
				
				$sum = 0;
				foreach($this->items as $value)
				{
					$sum += $value->goods_number;
				}

				return $sum;
			}
			return 0;
		}

		

		//获取单条价格小计
		public function getItemTotalPrice($cartid,$goodsid,$tasteid)
		{
			$key=$this->hasItemIder($cartid,$goodsid,$tasteid);
			if($key!==false)
			{
				return $this->getGoodsCount($cartid,$goodsid,$tasteid)*$this->items[$key]->saleprice;
			}
			return 0;
            

		}

		//获取商品名称
		public function getGoodsName($cartid,$goodsid)
		{
			$key=$this->hasItem($cartid,$goodsid);
			if($key!==false)
			{
				return $this->items[$key]->goodsname;
			}
		}

		//获取商品口味
		public function getGoodsTaste($cartid,$goodsid)
		{
			$key=$this->hasItem($cartid,$goodsid);
			if($key!==false)
			{
				return $this->items[$key]->tastename;
			}
		}


		//获取单条积分小计
		public function getItemTotalIntegral($cartid,$goodsid)
		{
			$key=$this->hasItem($cartid,$goodsid);
			if($key!==false)
			{

				return $this->items[$key]->goods_total_integral;
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
					  $total += $value->goods_price * $value->goods_number;
				}
				return $total;
			}
			else
			{
				return 0;
			}
		}


		//获取购物车所有商品
		public function getAllCart()
		{
			return $this->items;
		}

		//添加需要结算的商品  并返回
		public function getPayCart($idstr='')
		{
		   //Tool::check($this->items);
		   if($idstr)   //$idStr=5,27$1,16$1,3$    商家及商品id
		   {
		   	   //每次要重新向这个数组里边放要结算的商品
		   	   unset($this->payItems);
	           $arr=explode('$',$idstr);

	           foreach($arr as $idStr)
	           {
	           	  if($idStr)
	           	  {
	           	  	  $idArr=explode(',',$idStr);

		       	  	  $cartid=$idArr[0];
		           	  $goodsid=$idArr[1];
		           	  $tasteid=$idArr[2];
		          	  $key=$this->hasItemIder($cartid,$goodsid,$tasteid);
		            
					  if($key!==false)
					  {
						 $this->payItems[$key]=$this->items[$key];
					  }
	           	   }
	           }

		   }
		   if(isset($this->payItems))
		   {
		   	  return $this->payItems;
		   }
		  
		}


        //把同一商家的要结算的商品放在一个数组   
        public function getPayGoodsCateByComid()
        {
        	
			if($this->payItems)
			{
				 
				   $cartArr=array();  
				   $cartComId=array();
				   foreach($this->items as $goods)
				   {
					   if($goods)
					   {
						  $comid=$goods->comid;
						  $cartArr[$comid][]=$goods; 
						  $cartComId[]=$goods->comid;
					   }        
				   }

				   return $cartArr;

			}
            return 0;
        }

        //获取要结算的商品的总价(不包含运费和优惠)
		public function getTotalPayPrice($cartid='')
		{
			
            //获取要结算的某一商家的商品的总价
            if($this->payItems)
			{
				if($cartid)
				{
                      
		                $cartArr=$this->getPayGoodsCateByComid();
                       
		                $total = 0.0; 
		                $totalprice = 0.0;
                        $price = 0.0;
                       // var_dump($cartid);
                        //var_dump($cartArr[$cartid]);
						foreach ($cartArr[$cartid] as $key => $value) 
						{
						 	
							$total += $value->goods_price  * $value->goods_number;
							
						}
						                        
					    return $total;   
				}
				else
				{
					
					$total = 0.0; 
	                $totalprice = 0.0;
                    $price = 0.0;
					
					foreach ($this->payItems as $key => $value) 
					{

					   $total += $value->goods_price * $value->goods_number;
						
					}
					
					
				    return $total;
				}
			}
			
			return 0;
		}

		//算出商家加上运费的金额
		function getTotalPriceComs($cartid='')
		{
			
			$total=0.0;
			$total=$this->getTotalPayPrice($cartid) + $this->freefreight($cartid);
			return $total;
		}

		//获取每个商家默认抵扣金额下的应付金额
		public function getCompanyPrice($cartid='')
		{
			$total=$this->getTotalPayPrice($cartid);
			return $total;
		}

		
		
		// //获取该会员最后应付的金额
		public function getTotalDiscount($cartComId)
		{
			/* 获取当前页面所有的商家id 
             * 传到这
             * 循环id  加出总价
			 */
			  if($cartComId)
			  {
			  	
				$total = 0.0;
				$price = 0.0;

				foreach($cartComId as $comid)
				{	 
                   $price += $this->getTotalPriceComs($comid) - $this->minusMoney($comid); 
				}
			    return  $price;
              }

			return 0;
            
		}

		public function getMinus($cartComId)
		{
			/* 获取当前页面所有的商家id 
             * 传到这
             * 循环id  加出总价
			 */
	         
			  if($cartComId)
			  {
			  
				$price = 0.0;
				foreach($cartComId as $comid)
				{	 
					//var_dump($comid);
                   $price +=  $this->minusMoney($comid);
                   
				}
			    return  $price;
              }

			return 0;
            
		}

        

		//查看运费是多少
		public function freefreight($comid)
		{
			$cartArr=$this->getPayGoodsCateByComid();
			
			$total = 0.0;
		   foreach ($cartArr[$comid] as $key => $value)
		   {
		   	   if($value->freight != 0)
               {
				     if($this->getTotalPayPrice($comid) >= $value->freefreight  )
				     {
				     	 return 0;
				     }
				     else
				     {				     	
				     	 return $value->freight;
				     }
               }
               else
               {
               	   return 0;
               }
		
           
		  }
		}
        
		//查看满减活动优惠是多少
		public function minusMoney($comid)
		{

			if($comid)
			{
			   $cartArr=$this->getPayGoodsCateByComid();
			   foreach ($cartArr[$comid] as $key => $value)
			   {
			   	   
				     if($value->full <= $this->getTotalPriceComs($comid))
				     {
				     	 return $value->minus;
				     }
				     else
				     {				     	
				     	return 0;
				     }
                        
			   }

			
			}


		}
	}

?>