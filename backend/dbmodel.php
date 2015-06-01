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
        const ALLOWEDFILES = "sharedFiletypeList";
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
        const FILES = "xownFileList";
    }
    
    class TFILE {
        const TABLE = "file";
        const ORIGNAME = "origname";
        const PATH = "path";
        const THUMBPATH = "thumbpath";
        const TYPE = "type";
    }
    
    class TFILETYPE {
        const TABLE = "filetype";
        const MIME = "mime";
        const FILEENDING = "fileending";
    }
    
    function dbExists($o){
        return (!empty($o) && $o[COMMONS::ID] > 0);
    }
?>
