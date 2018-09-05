<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

class Dropbox{
  public $url='https://content.dropboxapi.com/2/files/';
  public $action='upload';

  function upload($post_fields = null, $headers = null) {
      $ch = curl_init();

      //Increase the timeout limit for large files
      $timeout = 60;

      curl_setopt($ch, CURLOPT_URL, $this->url.$this->action);
      if ($post_fields && !empty($post_fields)) {
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
          curl_setopt( $ch , CURLOPT_RETURNTRANSFER , 1 );
      }


      if ($headers && !empty($headers)) {
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      }

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

      $data = curl_exec($ch);

      if (curl_errno($ch)) {
          echo 'Error:' . curl_error($ch);
      }

      curl_close($ch);
      return $data;

  }
}

//Optional
header('Content-Type: application/json');


$Db= new Dropbox();

//Your Access token #change
$auth='<YOUR ACCESS TOKEN HERE>';

//Your app name #change
$appNAme='<YOUR APP NAME HERE>';

$fileName='file.txt';

//Your local file path #change
$filePath='resource/'.$fileName;

//Your Dropbox upload path #change
$dropBoxPath='/'.$appNAme.'/BackupFolder/SubFolder/'.$fileName;

//Header paramaters #for others:https://www.dropbox.com/developers/documentation/http/documentation#files-upload
$headers = [
            'Authorization:Bearer '.$auth,
            'Content-Type:application/octet-stream',
            'Dropbox-API-Arg:{"path":"'.$dropBoxPath.'","autorename":true,"strict_conflict":true,"mode":{".tag":"add"}}'
          ];

//Post your file binary format
$post_fields = file_get_contents($filePath);

$uploadResponse=$Db->upload($post_fields,$headers);

print_r($uploadResponse);

 ?>
