<?php

namespace OpenSteam\Persistence\Downloader;

class RangeDownloader extends Downloader {

	public static function download(\steam_document $document) {
		//check if document stored in filesystem
		//  if not stored in filesystem - create tmp-file
		$persistence = $document->getPersistence();
		$persistenceType = $document->getPersistenceType();
		$file = PATH_TEMP . $document->get_id();
		if ($persistenceType === PERSISTENCE_DATABASE) {
			$content = $persistence->load($document);
			file_put_contents($file, $content);
		} else {
			$file = $persistence->get_file_path($document);
		}

		$document->send_custom_header("R");

		if (function_exists("http_send_content_disposition")) {
			// use pecl http extension
			http_send_content_disposition($document->get_name(), true);
			http_send_content_type($document->get_mimetype());
			//http_throttle(0.1, 1024);
			http_send_file($file);
			exit;
		}

		header("Content-Type: " . $document->get_mimetype());

		$fp = @fopen($file, 'rb');

		$size = filesize($file); // File size
		$length = $size; // Content length
		$start = 0; // Start byte
		$end = $size - 1; // End byte
		// Now that we've gotten so far without errors we send the accept range header
		/* At the moment we only support single ranges.
	                 * Multiple ranges requires some more work to ensure it works correctly
	                 * and comply with the spesifications: http://www.w3.org/Protocols/rfc2616/rfc2616-sec19.html#sec19.2
	                 *
	                 * Multirange support annouces itself with:
	                 * header('Accept-Ranges: bytes');
	                 *
	                 * Multirange content must be sent with multipart/byteranges mediatype,
	                 * (mediatype = mimetype)
	                 * as well as a boundry header to indicate the various chunks of data.
*/
		//header("Accept-Ranges: 0-$length");
		header('Accept-Ranges: bytes');
		// multipart/byteranges
		// http://www.w3.org/Protocols/rfc2616/rfc2616-sec19.html#sec19.2
		if (isset($_SERVER['HTTP_RANGE'])) {
			//error_log($_SERVER['HTTP_RANGE']);
			$c_start = $start;
			$c_end = $end;
			// Extract the range string
			list($range_type, $range_data) = explode('=', $_SERVER['HTTP_RANGE'], 2);
			// Make sure the client hasn't sent us a multibyte range
			if ($range_type != 'bytes') {

				// (?) Shoud this be issued here, or should the first
				// range be used? Or should the header be ignored and
				// we output the whole content?
				//error_log('HTTP/1.1 416 Requested Range Not Satisfiable');
				header('HTTP/1.1 416 Requested Range Not Satisfiable');
				header("Content-Range: bytes $start-$end/$size");
				// (?) Echo some info to the client?
				exit;
			}
			// Use only first range set as  multiple ranges are currently not
			// supported.
			$ranges = explode(',', $range_data, 2);
			$range = $ranges[0];
			// If the range starts with an '-' we start from the beginning.
			// If not, we forward the file pointer and make sure to get the end byte
			// if spesified.
			if ($range{0} == '-') {
				// The n-number of the last bytes is requested
				$c_start = $size - substr($range, 1);
			} else {

				$range = explode('-', $range);
				$c_start = $range[0];
				$c_end = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
			}
			/* Check the range and make sure it's treated according to the specs.
				                         * http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html
			*/
			// End bytes can not be larger than $end.
			$c_end = ($c_end > $end) ? $end : $c_end;
			// Validate the requested range and return an error if it's not correct.
			if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
				//error_log('HTTP/1.1 416 Requested Range Not Satisfiable');
				header('HTTP/1.1 416 Requested Range Not Satisfiable');
				header("Content-Range: bytes $start-$end/$size");
				// (?) Echo some info to the client?
				exit;
			}
			$start = $c_start;
			$end = $c_end;
			$length = $end - $start + 1; // Calculate new content length
			fseek($fp, $start);
			header('HTTP/1.1 206 Partial Content');
		}
		// Notify the client the byte range we'll be outputting
		header("Content-Range: bytes $start-$end/$size");
		header("Content-Length: $length");

		// Start buffered download
		$buffer = 1024;
		$rate = DOWNLOAD_RANGE_SPEEDLIMIT; // spead limit in kB
		if ($rate > 0) {
			$buffer *= $rate;
		}
		set_time_limit(0); // Reset time limit for big files

		while (!feof($fp) && ($p = ftell($fp)) <= $end) {
			$timeStart = microtime(true);

			if ($p + $buffer > $end) {

				// In case we're only outputtin a chunk, make sure we don't
				// read past the length
				$buffer = $end - $p + 1;
			}

			echo fread($fp, $buffer);
			flush(); // Free up memory. Otherwise large files will trigger PHP's memory limit.

			$wait = (microtime(true) - $timeStart) * 1000000;
			// if speedlimit is defined, make sure to only send specified bytes per second
			$sleeptime = 1000000 - $wait;
			if (($rate > 0) && ($sleeptime > 0)) {
				usleep($sleeptime);
			}
		}

		fclose($fp);
	}

	protected static function prepare_header(\steam_document $document, $params = array()) {}
}
