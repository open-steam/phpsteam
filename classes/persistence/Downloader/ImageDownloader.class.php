<?php

namespace OpenSteam\Persistence\Downloader;

class ImageDownloader extends Downloader
{

/*    public function init_document()
    {
        //init document
        if ($this->identifier_type === "id") {
            $this->document = steam_factory::get_object($this->steam_connector->get_id(), (int)$this->identifier);
        }
        else {
            if ($this->identifier_type === "name") {
                $this->document = $this->steam_connector
                    ->predefined_command(
                    $this->steam_connector
                        ->get_module("icons"), "get_icon_by_name", array((string)$this->identifier), 0
                );
            }
        }
        return $this->document;
    }*/

    public static function download(\steam_document $document, $width = 0, $height = 0, $cache = true)
    {
        //check if thumbnail for object already exists
		$mime = $document->get_attribute(DOC_MIME_TYPE);
		if(empty($mime)){
			$mime = \MimetypeHelper::get_instance()->getMimeType($document->get_name());
		}
		$ext = \MimetypeHelper::get_instance()->getExtension($mime);
        $thumbnail_path = THUMBNAIL_PATH . $document->get_id() . "_" . $width . "x" . $height . "." . $ext;
		$thumbnail_exists = file_exists($thumbnail_path);
		
        if(!$cache || !$thumbnail_exists){
            $content = $document->get_content();
            $thumbnail_path = \ThumbnailHelper::createThumbnail(
                $document, $content, $mime, $thumbnail_path, $width, $height);
        }

        $filesize = filesize($thumbnail_path);

        self::prepare_header($document, array("filesize" => $filesize, "cache" => $cache, "mimetype" => $mime));
        @ob_flush();
        readfile($thumbnail_path);
    }

    protected static function prepare_header(\steam_document $document, $params = array())
    {
        header('Content-Description: File Transfer;');
        if($params["cache"]){
            //cache 1 week
            header('Cache-Control: max-age=604800;');
        } else {
            header('Cache-Control: no-cache;');
        }

        header('Pragma: public;');

        header("Last-Modified: {$document->get_attribute(DOC_LAST_MODIFIED)};");
        header("Content-Type: {$params["mimetype"]};");
        header("Content-Length: {$params["filesize"]};");
    }
}