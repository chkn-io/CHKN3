<?php
/**
 * CHKN Framework PHP
 * Copyright 2015 Powered by Percian Joseph C. Borja
 * Created May 12, 2015
 * Settings Page
 *
 * Class global_helper
 * This class calls all the other helper and libraries requested by the user
 * It includes process_model,Mailer,sms_helper,encrypt_helper,upload_helper,download_helper,pdf_helper,excel_helper
 */
class global_helper {
    private $encrypt;
    private $upload;
    private $download;
    private $default;
	function __construct(){
        if(ENCRYPTION == 1){
            $this->encrypt = new encrypt_helper;
        }

        if(UPLOAD == 1){
            $this->upload = new upload_helper;
        }
        if(DOWNLOAD == 1){
            $this->download = new download_helper;
        }

        if(DEFAULTS == 1){
          $this->default = new defaults;
        }
	}

    public function upload($file_location,$image,$image_name){
        if(UPLOAD == 1){
          $this->upload->upload($file_location,$image,$image_name);
        }else{
            echo "UPLOAD is disabled";
        }
	}

    public function encrypt($value = ''){
        if(ENCRYPTION == 1){
            $response = $this->encrypt->encrypt($value);
            return $response;
        }else{
            echo "ENCRYPTION is disabled";
            exit;
        }
        
    }

    public function decrypt($value = ''){
        if(ENCRYPTION == 1){
            $response = $this->encrypt->decrypt($value);
            return $response;
        }else{
            echo "ENCRYPTION is disabled";
            exit;
        }
    }

    public function download($filename,$file_location){
        if(DOWNLOAD == 1){
           $this->download->download($filename,$file_location);
        }else{
            echo "DOWNLOAD is disabled";
            exit;
        }
    }

    public function defaults(){
        if(DEFAULTS == 1){
            return $this->default->start();
        }else{
            echo "DEFAULT is disabled";
            exit;
        }
    }
}