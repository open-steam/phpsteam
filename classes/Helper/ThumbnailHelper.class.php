<?php

class ThumbnailHelper {
	public static function createThumbnail(steam_document $document, &$content, $mime, $thumbnail_path, $width, $height) {
		if ($mime === 'application/octet-stream') {
			$mime = MimetypeHelper::get_instance()->getMimeType($document->get_name());
		}
		if ($mime !== 'image/gif' && $mime !== 'image/jpeg' && $mime !== 'image/jpg' && $mime != 'image/png') {
			self::renderPlaceholderImage("defektes Bild", 'ccc', '555', $width, $height);
			die;
		}

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

		$imgInfo = @getimagesize($f);
		$src_width = $imgInfo[0];
		$src_height = $imgInfo[1];

		if (empty($src_width) || empty($src_height)) {
			self::renderPlaceholderImage("defektes Bild", 'ccc', '555', $width, $height);
			die;
		}

		//use correct aspect-ratio
		$nWidth = $width;
		$nHeight = $src_height * ($width / $src_width);

		//scale to match bounds
		if ($nHeight > $height) {
			$scale = $height / $nHeight;
			$nHeight = $height;

			$nWidth = $nWidth * $scale;
		}
		if ($nWidth > $width) {
			$scale = $width / $nWidth;
			$nWidth = $width;

			$nHeight = $nHeight * $scale;
		}

		$nWidth = round($nWidth);
		$nHeight = round($nHeight);

		$imgFunc = '';

		$exifImageType = exif_imagetype($f);

		if ($exifImageType === IMAGETYPE_GIF) {
			$img = ImageCreateFromGIF($f);
			if (!$img) {
				self::renderPlaceholderImage("defektes Bild", 'ccc', '555', $width, $height);
				unlink($f);
				die;
			}
			$imgFunc = 'ImageGIF';
			$transparent_index = ImageColorTransparent($img);
			if ($transparent_index != (-1)) {
				$transparent_color = ImageColorsForIndex($img, $transparent_index);
			}

		} elseif ($exifImageType === IMAGETYPE_JPEG) {
			$img = ImageCreateFromJPEG($f);
			if (!$img) {
				self::renderPlaceholderImage("defektes Bild", 'ccc', '555', $width, $height);
				unlink($f);
				die;
			}
			$imgFunc = 'ImageJPEG';
		} elseif ($exifImageType === IMAGETYPE_PNG) {
			$img = ImageCreateFromPNG($f);
			if (!$img) {
				self::renderPlaceholderImage("defektes Bild", 'ccc', '555', $width, $height);
				unlink($f);
				die;
			}
			ImageAlphaBlending($img, false);
			ImageSaveAlpha($img, true);

			$imgFunc = 'ImagePNG';
		} else {
			self::renderPlaceholderImage("defektes Bild", 'ccc', '555', $width, $height);
			unlink($f);
			die;
		}
                
                if (defined('PHOTOALBUM_ROTATE_IMAGES') && PHOTOALBUM_ROTATE_IMAGES) {
                        $exif = exif_read_data($f);
                        if (!empty($exif['Orientation'])) {

                                switch ($exif['Orientation']) {
                                        case 3:
                                                $img = imagerotate($img, 180, 0);
                                        break;
                                        
                                        case 6:
                                                $img = imagerotate($img, -90, 0);
                                        break;

                                        case 8:
                                                $img = imagerotate($img, 90, 0);
                                        break;
                                }
                        }
                }

		$img_resized = ImageCreateTrueColor($nWidth, $nHeight);
		if ($exifImageType === IMAGETYPE_PNG) {

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
		if (imagecopyresampled($img_resized, $img, 0, 0, 0, 0, $nWidth, $nHeight, $src_width, $src_height)) {
			ImageDestroy($img);
			$img = $img_resized;
		}

		$imgFunc($img, $path);
		ImageDestroy($img);
		unlink($f);

		return $path;
	}

	public static function renderPlaceholderImage($message = 'Platzhalter', $bgColor = 'ccc', $fgColor = '555', $width = 100, $height = 100) {
		header("Content-type: image/png");

		$image = imagecreate($width, $height);

		$bg = self::hex2rgb($bgColor);
		$setbg = imagecolorallocate($image, $bg['r'], $bg['g'], $bg['b']);

		$fg = self::hex2rgb($fgColor);
		$setfg = imagecolorallocate($image, $fg['r'], $fg['g'], $fg['b']);

		$text = $message;

		$fontsize = 4;
		$fontwidth = imagefontwidth($fontsize);
		$fontheight = imagefontheight($fontsize);
		$length = strlen($text);
		$textwidth = $length * $fontwidth;
		if ($textwidth > $width) {
			$text = "X";
			$length = strlen($text);
			$textwidth = $length * $fontwidth;
		}
		$xpos = (imagesx($image) - $textwidth) / 2;
		$ypos = (imagesy($image) - $fontheight) / 2;

		imagestring($image, $fontsize, $xpos, $ypos, $text, $setfg);

		imagepng($image);
	}

	public static function hex2rgb($colour) {
		$colour = preg_replace("/[^abcdef0-9]/i", "", $colour);
		if (strlen($colour) == 6) {
			list($r, $g, $b) = str_split($colour, 2);

			return Array("r" => hexdec($r), "g" => hexdec($g), "b" => hexdec($b));
		} elseif (strlen($colour) == 3) {
			list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);

			return Array("r" => hexdec($r), "g" => hexdec($g), "b" => hexdec($b));
		}

		return false;
	}

}
