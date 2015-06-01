<?php
    //lol, f3 and php don't manage to get file uploads remotely right.
    //let's do it ourselves *stopping myself from making more fun of PHP*
    function verifyIncommingFiles($board) {
        $files = sane_file_array($_FILES);
        $acceptedFiles=array();
        if(!isset($files['file'])){
            return array();
        }
        foreach($files['file'] as $file) {
            
            if(isUploadError($file)) {
                continue;
            }
            
            //apparently the file type in $file['type']...
            //is just what the browser told the server...
            //still holding back....
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $filemime = $finfo->file($file['tmp_name']);
            
            $allowedMimes = allowedMimetypesByBoard($board);
            $mimeOK=false;
            foreach($allowedMimes as $aMime) {
                if($aMime[TFILETYPE::MIME] === $filemime){
                    $mimeOK = $aMime;
                    break;
                }
            }
            if(!$mimeOK) {
                continue;
            }
            $file['forced_fileending'] = $aMime[TFILETYPE::FILEENDING];
            
            //TODO make configurable
            if($file['size'] > (2 * 1024 * 1024)) { // 2MB
                continue;
            }
            
            $acceptedFiles[] = $file;
        }
        return $acceptedFiles;
 
    }
    
    function commitUploadedFiles($acceptedFiles, $repository) {
        $commitedFiles=array();
        foreach($acceptedFiles as $file) {
            $newFilename = sha1_file($file['tmp_name']).'.'.$file['forced_fileending'];
            $movePath = $repository.$newFilename;
            if(move_uploaded_file($file['tmp_name'], $movePath)) {
                //$commitedFiles[]=array('filename' => $newFilename, 'path' => $movePath, 'origname' => $file['name'], 'type' => $file[TFILE::MIME]);
                $commitedFiles[]=array('filename' => $newFilename, 'path' => $movePath, 'origname' => $file['name'], 'type' => $file['type']);
            }
        }
        return $commitedFiles;
    }
    
    function isUploadError($file) {
        //return (!isset($file['error']) || is_array($file['error']));
        return !!($file['error']);
    }
    
    function createThumbnail($file, $thumbpath) {
        //try to create our own thumbnail if png/jpg/gif
        $im = null;
        if($file['type']=="image/png") {
            $im = imagecreatefrompng($file[TFILE::PATH]);
        }
        if($file['type']=="image/jpg" || $file['type']=="image/jpeg") {
            $im = imagecreatefromjpeg($file[TFILE::PATH]);
        }
        if($file['type']=="image/gif") {
            $im = imagecreatefromgif($file[TFILE::PATH]);
        }

        if(!empty($im)) {
            $n_width = 128; $n_height = 128;
            $width=imagesx($im);              // Original picture widthis stored
            $height=imagesy($im);             // Original picture heightis stored
            $newimage=imagecreatetruecolor($n_width ,$n_height);
            //make it rectangular, don't care if we cut off parts in the thumb
            if($width > $height) {
                $width = $height;
            } else {
                $height = $width;
            }
            imagecopyresized($newimage,$im,0,0,0,0,$n_width,$n_height,$width,$height);
            imagejpeg($newimage, $thumbpath);
        }
    }
    
    
    //Thanks! Stackoverflow. This makes it almost bearable.
    function sane_file_array($files) {
      $result = array();
      $name = array();
      $type = array();
      $tmp_name = array();
      $error = array();
      $size = array();
      foreach($files as $field => $data) {
        foreach($data as $key => $val) {
          $result[$field] = array();
          if(!is_array($val)) {
            $result[$field] = $data;
          } else {
            $res = array();
            files_flip($res, array(), $data);
            $result[$field] += $res;
          }
        }
      }

      return $result;
    }

    function array_merge_recursive2($paArray1, $paArray2) {
      if (!is_array($paArray1) or !is_array($paArray2)) { return $paArray2; }
      foreach ($paArray2 AS $sKey2 => $sValue2) {
        $paArray1[$sKey2] = array_merge_recursive2(@$paArray1[$sKey2], $sValue2);
      }
      return $paArray1;
    }

    function files_flip(&$result, $keys, $value) {
      if(is_array($value)) {
        foreach($value as $k => $v) {
          $newkeys = $keys;
          array_push($newkeys, $k);
          files_flip($result, $newkeys, $v);
        }
      } else {
        $res = $value;
        // Move the innermost key to the outer spot
        $first = array_shift($keys);
        array_push($keys, $first);
        foreach(array_reverse($keys) as $k) {
          // You might think we'd say $res[$k] = $res, but $res starts out not as an array
          $res = array($k => $res);     
        }

        $result = array_merge_recursive2($result, $res);
      }
    }
?>
