<?php

class Thumbnail_Helper {

	public static function createThumbnail(steam_document $document, $content, $mime, $thumbnail_path, $width, $height) {
		$type = MimetypeHelper::get_instance()->getExtension($mime);

		$path = THUMBNAIL_PATH . $document->get_id() . "_" . $width . "x" . $height . "." . $type;
		
		if ($width == false) {
			$width = $height;
		}
		if ($height == false) {
			$height = $width;
		}
		
		$f = $path . ".temp";
		$fh = fopen($f, "w+");
		fwrite($fh, $content);
		fclose($fh);
		
		$imgInfo = getimagesize($f);
		$src_width = $imgInfo[0];
		$src_height = $imgInfo[1];

		//use correct aspect-ratio
		$nWidth = $width;
		$nHeight = $src_height * ($width / $src_width);
		
		//scale to match bounds
		if($nHeight > $height){
			$scale = $height / $nHeight;
			$nHeight = $height;
			
			$nWidth = $nWidth * $scale;
		}
		if($nWidth > $width){
			$scale = $width / $nWidth;
			$nWidth = $width;
			
			$nHeight = $nHeight * $scale;
		}
		
		$nWidth = round($nWidth);
		$nHeight = round($nHeight);

		$imgFunc = '';


		if ($mime == 'image/gif' OR $type == 'gif') {
			$img = ImageCreateFromGIF($f);
			$imgFunc = 'ImageGIF';
			$transparent_index = ImageColorTransparent($img);
			if ($transparent_index != (-1))
				$transparent_color = ImageColorsForIndex($img, $transparent_index);
		}
		else if ($mime == 'image/jpeg' OR $mime == 'image/pjpeg' OR $type == 'jpg') {
			$img = ImageCreateFromJPEG($f);
			$imgFunc = 'ImageJPEG';
		} else if ($mime == 'image/png' OR $type == 'png') {
			$img = ImageCreateFromPNG($f);
			ImageAlphaBlending($img, false);
			ImageSaveAlpha($img, true);

			$imgFunc = 'ImagePNG';
		} else {
			die("ERROR - no image found");
		}

		$img_resized = ImageCreateTrueColor($nWidth, $nHeight);
		if ($type == 'png') {

			ImageAlphaBlending($img_resized, false);
			ImageSaveAlpha($img_resized, true);
			$transparent = imagecolorallocatealpha($img_resized, 255, 255, 255, 127);
			imagefilledrectangle($img_resized, 0, 0, $nWidth, $nHeight, $transparent);
		}
		if (!empty($transparent_color)) {
			$transparent_new = ImageColorAllocate($img_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
			$transparent_new_index = ImageColorTransparent($img_resized, $transparent_new);
			ImageFill($img_resized, 0, 0, $transparent_new_index);
		}
		if (ImageCopyResized($img_resized, $img, 0, 0, 0, 0, $nWidth, $nHeight, $src_width, $src_height)) {
			ImageDestroy($img);
			$img = $img_resized;
		}

		$imgFunc($img, $path);
		ImageDestroy($img);
		unlink($f);
		return $path;
	}

}