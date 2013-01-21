<?php

abstract class VirusScanner{
    
    /**
     * @return bool - true if clean - else false 
     */
    public abstract function scanFile($filename);
    
    /**
     * @return bool - true if clean - else false 
     */
    public abstract function scanDir($dirname);   
}




class ClamAvScanner extends VirusScanner{
    
    public function scanDir($dirname) {
        $command = sprintf("clamscan -r %s", $filename);
        return $this->executeCommand($command);
    }

    public function scanFile($filename) {
        $command = sprintf("clamscan %s", $filename);        
        return $this->executeCommand($command);
        
    }
     
    private function executeCommand($command){
        $returnCode = -1;
        $out = "";
        exec($command, $out, $returnCode);

        if($returnCode === 0){
            return true;
        } else {
            return false;
        }
    }
}

class SophosScanner extends VirusScanner{
    
    public function scanDir($dirname) {
        throw new Exception('Not implemented yet.');
    }
    public function scanFile($filename) {
        throw new Exception('Not implemented yet.');
    }
}