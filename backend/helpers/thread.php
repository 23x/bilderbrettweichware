<?php   

    /*
    don't call methods prefixed with "_" if you don't know what you're doing.
    They may look simple, but they'll fuck your shit up if not properly wrapped
    in a transaction and store the right elements after calling them.
    equally, don't mess with methods calling "_"-methods. (But feel free to call them)
    */
    
    
    function createThread($boardUrl, $subject, $comment, $user, $ip){
        R::begin();
        try {
            $board = boardByURL($boardUrl);
            $user = checkSetDefaultPoster($user, $board);
            $thread = R::dispense(TTHREAD::TABLE);
            R::store($thread);
            $post = _dispensePost($board, $subject, $comment, $user, $ip);
            $post[TPOST::ISOP] = true;
            R::store($post);
            array_push($thread[TTHREAD::POSTS], $post);
            array_push($board[TBOARD::THREADS], $thread);
            R::store($board);
            R::commit();
        } catch(Exception $e) {
            R::rollback();
        }
        
    }
    
    function createPost($threadid, $subject, $comment, $user, $ip, $files){
        R::begin();
        try {
            $thread = R::load(TTHREAD::TABLE, $threadid);
            $board = $thread[TBOARD::TABLE];
            $user = checkSetDefaultPoster($user, $board);
            $post = _dispensePost($board, $subject, $comment, $user, $ip);
            $post = _appendFilesToPost($post, $files);
            R::store($post);
            array_push($thread[TTHREAD::POSTS], $post);
            R::store($thread);
            R::store($board);
            R::commit();
        } catch(Exception $e) {
            R::rollback();
        }
        
    }
       
    function checkSetDefaultPoster($user, $board) {
        if(empty($user)){
                $user = $board[TBOARD::DEFAULTPOSTER];
        }
        return $user;
    }
    
    function postAsJSONable($post){
        $jsonable =  propertiesAsArray($post,
                        TPOST::POSTNUMBER,
                        TPOST::SUBJECT,
                        TPOST::COMMENT,
                        TPOST::USER,
                        TPOST::TIMESTAMP,
                        TPOST::ISOP
            );
        $jsonable['files'] = array();
        foreach($post[TPOST::FILES] as $file) {
             $jsonable['files'][] = propertiesAsArray($file,
                                    TFILE::PATH,
                                    TFILE::ORIGNAME
                );
        }
        return $jsonable;
    }
    
    function postFromNumber($boardUrl, $postNumber){
        //this is ugly
        $r = R::getAll("SELECT * FROM post
                        INNER JOIN thread on post.thread_id = thread.id 
                        INNER JOIN board on thread.board_id = board.id
                        WHERE board.urlname= ?
                        AND post.postnumber = ?", array($boardUrl, $postNumber));
        $records = R::convertToBeans(TPOST::TABLE, $r);
        foreach($records as $post) {
            return $post;
        }
        return null;
    }
    
    function _appendFilesToPost($post, $cFiles) {
        foreach($cFiles as $file) {
            $dbFile = R::dispense(TFILE::TABLE);
            $dbFile[TFILE::ORIGNAME] = $file['origname'];
            $dbFile[TFILE::PATH] = $file['filename'];
            $dbFile[TFILE::THUMBPATH] = $file['thumbpath'];
            $dbFile[TFILE::MIME] = $file['type'];
            R::store($dbFile);
            array_push($post[TPOST::FILES], $dbFile);
        }
        return $post;
    }
    
    function _dispensePost($board, $subject, $comment, $user, $ip) {
        $post = R::dispense(TPOST::TABLE);
        $post[TPOST::TIMESTAMP] = time();
        $post[TPOST::SUBJECT] = $subject;
        $post[TPOST::ISOP] = false;
        $post[TPOST::COMMENT] = $comment;
        $post[TPOST::USER] = $user;
        $post[TPOST::IP] = $ip;
        $post[TPOST::POSTNUMBER] = _getPostNumber($board);
        return $post;
    }
    
    function _getPostNumber($board) {
        $id = ($board[TBOARD::POSTCOUNT])+1;
        $board[TBOARD::POSTCOUNT] = $id;
        return $id;
    }
?>
