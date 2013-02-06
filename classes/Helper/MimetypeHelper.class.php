<?php

class MimetypeHelper {

    protected $source = "http://svn.apache.org/repos/asf/httpd/httpd/branches/1.3.x/conf/mime.types";
    public $target;
    public $mimetype_table;
    
    private static $instance;

    /**
     *
     * @return MimetypeHelper 
     */
    public static function get_instance(){
        if(!self::$instance){
            self::$instance = new MimetypeHelper(MIMETYPE_STORAGE_PATH);
        }
        return self::$instance;
    }

    private function __construct($target){
        $this->target = $target . "parsed_mime_types.php";
        if(!file_exists($this->target )){
            $this->writeToFile();
        }

        include($this->target);
        $this->mimetype_table = $mime_types;
    }

    public function getMimeType($extension){
        $file_ext = substr(strrchr($extension,'.'),1);
        if($file_ext){
            $extension = $file_ext;
        }
        
        if(isset($this->mimetype_table[$extension])){
            return $this->mimetype_table[$extension];
        } else {
            return "application/octet-stream";
            //throw new Exception('Unknown mimetype');
        }
    }
	
	public function getExtension($mime){
		foreach($this->mimetype_table as $key => $value){
			if($value == $mime){
				return $key;
			}
		}
		return false;
	}

    protected function generateUpToDateMimeArray(){
        $url = $this->source;
        $s=array();
        foreach(@explode("\n",@file_get_contents($url))as $x){
            if(isset($x[0])&&$x[0]!=='#'&&preg_match_all('#([^\s]+)#',$x,$out)&&isset($out[1])&&($c=count($out[1]))>1){
                for($i=1;$i<$c;$i++)
                    $s[$out[1][$i]]=$out[1][0];
            }
        }
        return $s;
    }

    protected function writeToFile(){

        $mime_types = $this->generateUpToDateMimeArray();

        $output = '<?php $mime_types = array(';
        foreach($mime_types as $key => $value){
            $output .= "'$key' => '$value', \n";
        }
        $output .= ");";

        file_put_contents($this->target, $output);
        return $this->target;
    }

}