<?php
    function badRequest($errors) {
        header('HTTP/1.1 400 You suck', true, 400);
        $body=array();
        $body['status']="NOPE";
        $body['errors']=$errors;
        die(json_encode($body, JSON_NUMERIC_CHECK));
    }
    
    function restOK($optData=array()){
        header('HTTP/1.1 200 nice request m8', true, 200);
        $body=array();
        $body['status']="OK";
        $body['data']=$optData;
        echo json_encode($body, JSON_NUMERIC_CHECK);
    }
    
    function propertiesAsArray(){
        $f = func_get_args();
        $o = array_shift($f);
        $r = array();
        foreach($f as $k){
            $r[$k] = $o[$k];
        }
        return $r;
    }
    
    //sigh, so basically php doesn't allow us to easily read multipart
    //data, so instead of getting some JSON directly we'll always
    //have to wrap it in data=, because we need JSON+files, not form
    //data+files. This is the easiest way to work around PHP beeing dumb.
    function getPostData() {
        return json_decode($_POST['data'], true);
    }
?>
