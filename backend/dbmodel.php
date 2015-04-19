<?php
    class COMMONS {
        const ID = "id";
    }
    
    class TBOARD {
        const TABLE = "board";
        const URLNAME = "urlname";
        const NAME = "name";
        const DEFAULTPOSTER = "defaultposter";
        const THREADS = "xownThreadList";
        const POSTCOUNT = "postcount";
    }
    
    class TTHREAD {
        const TABLE = "thread";
        const POSTS = "xownPostList";
    }
    
    class TPOST {
        const TABLE = "post";
        const TIMESTAMP = "timestamp";
        const SUBJECT = "subject";
        const ISOP = "isop";
        const COMMENT = "comment";
        const USER = "user";
        const IP = "ip";
        const POSTNUMBER = "postnumber";
    }
    
    
    
    function dbExists($o){
        return (!empty($o) && $o[COMMONS::ID] > 0);
    }
?>
