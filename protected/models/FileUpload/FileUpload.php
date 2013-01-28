<?php
class FileUpload
{
	public static function createFolder($path) {
		if(!file_exists($path)) {
			self::createFolder(dirname($path));
			mkdir($path,0777);
		}
	}
	
	public static function uploadImages($name, $savePath='uploadfile/', $namePrefix = 'temp', $resizeArray = array(), $quality=80) {
		$uploadImages = CUploadedFile::getInstancesByName($name);
		foreach($uploadImages as $uploadImage) {
			$ext = $uploadImage->extensionName; //上传文件的扩展名
			if(strtolower($ext) !== 'jpg') {
				continue;
			}
			$filename = $namePrefix . '.' . $ext;
            self::createFolder($savePath);
			$saveFileName = $savePath . '/' . $filename; //数据库文件名
			self::saveImage($uploadImage,$saveFileName);
		}
	}

	public static function uploadImage($name, $savePath='uploadfile/', $namePrefix = 'temp' ,  $resizeArray = array(), $quality=80) {
        $uploadImage = CUploadedFile::getInstanceByName($name);
		if(empty($uploadImage)) {
			return;
		}
		$ext = $uploadImage->extensionName; //上传文件的扩展名
		$filename = $namePrefix . '.' . $ext;
		self::createFolder($savePath);
		$saveFileName = $savePath . '/' . $filename; //数据库文件名
		self::saveImage($uploadImage,$saveFileName);
    }

	public static function saveImage($uploadImage, $saveFileName='uploadfile/1.tmp', $resizeArray = array(), $quality=80) {
		if (is_object($uploadImage) && get_class($uploadImage) === 'CUploadedFile') {
			$shop_imgurl = $saveFileName; //数据库文件名
			$thumb_array = array();
			if (!empty($resizeArray) && is_array($resizeArray)) {
				$im = NULL;
				$imagetype = strtolower($ext);
				$tpn = $uploadImage->getTempName();
				if ($imagetype == 'gif') {
					$im = imagecreatefromgif($tpn);
				} else if ($imagetype == 'jpg') {
					$im = imagecreatefromjpeg($tpn);
				} else if ($imagetype == 'png') {
					$im = imagecreatefrompng($tpn);
				}
				foreach ($resizeArray as $k => $v) {
					if ((isset($v['mw']) && is_numeric($v['mw'])) || (isset($v['mh']) && is_numeric($v['mh']))) {
						$mw = isset($v['mw']) ? $v['mw'] : 0;
						$mh = isset($v['mh']) ? $v['mh'] : 0;
						$thumb_file_name = $savePath . $k . '_' . $filename;
						self::resizeImage($im, $mw, $mh, $thumb_file_name, $ext, $quality);
						$thumb_array[$k] = $thumb_file_name;
					}
				}
			}
			if ($uploadImage->saveAs($shop_imgurl)) {
				return array('image' => $shop_imgurl, 'thumb' => $thumb_array);
			} else {
				return array();
			}
		} else {
			return array();
		}
	}

	public static function resizeImage($im, $maxwidth, $maxheight, $name, $filetype, $quality=100) {
		$pic_width = imagesx($im);
		$pic_height = imagesy($im);

		if (($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight)) {
			if ($maxwidth && $pic_width > $maxwidth) {
				$widthratio = $maxwidth / $pic_width;
				$resizewidth_tag = true;
			}

			if ($maxheight && $pic_height > $maxheight) {
				$heightratio = $maxheight / $pic_height;
				$resizeheight_tag = true;
			}

			if ($resizewidth_tag && $resizeheight_tag) {
				if ($widthratio < $heightratio)
					$ratio = $widthratio;
				else
					$ratio = $heightratio;
			}

			if ($resizewidth_tag && !$resizeheight_tag)
				$ratio = $widthratio;
			if ($resizeheight_tag && !$resizewidth_tag)
				$ratio = $heightratio;

			$newwidth = $pic_width * $ratio;
			$newheight = $pic_height * $ratio;

			if (function_exists("imagecopyresampled")) {
				$newim = imagecreatetruecolor($newwidth, $newheight);
				imagecopyresampled($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $pic_width, $pic_height);
			} else {
				$newim = imagecreate($newwidth, $newheight);
				imagecopyresized($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $pic_width, $pic_height);
			}

			imagejpeg($newim, $name, $quality);
			imagedestroy($newim);
		} else {
			$name = $name . $filetype;
			imagejpeg($im, $name, $quality);
		}
	}
} 
