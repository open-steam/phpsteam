<?php

class ThumbnailDownload extends DocumentDownload
{

    public $width;
    public $height;
    public $identifier_type;
    public $identifier;
    public $allow_cache = true;
    protected $filesize;

    public $steam_connector;

    public function __construct()
    {
        $this->persistence = new SteamPersistence();
    }

    public function init_document()
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
    }

    public function download()
    {
        //check if thumbnail for object already exists
		$mime = $this->document->get_attribute(DOC_MIME_TYPE);
		if(empty($mime)){
			$mime = MimetypeHelper::get_instance()->getMimeType($document->get_name());
		}
		$ext = MimetypeHelper::get_instance()->getExtension($mime);
        $thumbnail_path = THUMBNAIL_PATH . $this->identifier . "_" . $this->width . "x" . $this->height . "." . $ext;
		$thumbnail_exists = file_exists($thumbnail_path);
		
        if(!$this->allow_cache || !$thumbnail_exists){
            $content = $this->document->get_content();
            $thumbnail_path = Thumbnail_Helper::createThumbnail(
                $this->document, $content, $mime, $thumbnail_path, $this->width, $this->height);
        }

        $this->filesize = filesize($thumbnail_path);

        $this->prepare_header();
        @ob_flush();
        readfile($thumbnail_path);
        //@ob_flush();
        exit;
        //$this->stream($thumbnail_path);
    }


    public function set_dimensions($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    protected function prepare_header()
    {
        header('Content-Description: File Transfer;');
        if($this->allow_cache){
            //cache 1 week
            header('Cache-Control: max-age=604800;');
        } else {
            header('Cache-Control: no-cache;');
        }

        header('Pragma: public;');

        header("Last-Modified: {$this->document->attributes['lastmodified']};");
        header("Content-Type: {$this->document->attributes['mimetype']};");
        header("Content-Length: {$this->filesize};");
        //header( "Content-Disposition: attachment; filename=\"" . $this->document->attributes['name'] . "\"");
    }
}