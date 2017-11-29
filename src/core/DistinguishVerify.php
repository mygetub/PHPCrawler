<?php  


	/**
	* 
	*/
	class DistinguishVerify
	{
		//图片路径
		private $ImagePath;
		//图片尺寸
		private $ImageSize;
		//图片标识符
		private $ImageInfo;

		public function __construct($Image){
			/*
			 *取得图片路径和图片尺寸
			 */
			$this->ImagePath = $Image;
			$this->ImageSize = getimagesize($Image);
		}
		
		/*
		 *获取图像标识符，保存到ImageInfo，只能处理bmp,png,jpg图片
		 *ImageCreateFromBmp是我自己定义的函数，最后会给出
		 */
		function getInfo(){
		    $filetype = substr($this->ImagePath,-3);
		    if($filetype == 'bmp'){
		        $this->ImageInfo = $this->ImageCreateFromBmp($this->ImagePath);
		    }elseif($filetype == 'jpg'){
		        $this->ImageInfo = imagecreatefromjpeg($this->ImagePath);    
		    }elseif($filetype == 'png'){
		        $this->ImageInfo = imagecreatefrompng($this->ImagePath);    
		    }
		}

		/*获取图片RGB信息*/
		function getRgb(){
		    $rgbArray = array();
		    $res = $this->ImageInfo;
		    $size = $this->ImageSize;
		    $wid = $size['0'];
		    $hid = $size['1'];
		    for($i=0; $i < $hid; ++$i){
		        for($j=0; $j < $wid; ++$j){
		            $rgb = imagecolorat($res,$j,$i);
		            $rgbArray[$i][$j] = imagecolorsforindex($res, $rgb);
		        }
		    }
		    return $rgbArray;
		}
		/*
		 *获取灰度信息
		 */
		function getGray(){
		    $grayArray = array();
		    $size = $this->ImageSize;
		    $rgbarray = $this->getRgb();
		    $wid = $size['0'];
		    $hid = $size['1'];
		    for($i=0; $i < $hid; ++$i){
		        for($j=0; $j < $wid; ++$j){
		            $grayArray[$i][$j] = (299*$rgbarray[$i][$j]['red']+587*$rgbarray[$i][$j]['green']+144*$rgbarray[$i][$j]['blue'])/1000;
		        }
		    }
		    return $grayArray;
		}
	}