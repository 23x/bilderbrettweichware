<?php   

    /*
    don't call methods prefixed with "_" if you don't know what you're doing.
    They may look simple, but they'll fuck your shit up if not properly wrapped
    in a transaction and store the right elements after calling them.
    */
    
    
    function createThread($boardUrl, $subject, $comment, $user, $ip){
        R::begin();
        try {
            $board = boardByURL($boardUrl);
            if(empty($user)){
                $user = $board[TBOARD::DEFAULTPOSTER];
            }
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
    
    function createPost($threadid, $subject, $comment, $user, $ip){
        R::begin();
        try {
            $thread = R::load(TTHREAD::TABLE, $threadid);
            $board = $thread[TBOARD::TABLE];
            if(empty($user)){
                $user = $board[TBOARD::DEFAULTPOSTER];
            }
            $post = _dispensePost($board, $subject, $comment, $user, $ip);
            R::store($post);
            array_push($thread[TTHREAD::POSTS], $post);
            R::store($thread);
            R::store($board);
            R::commit();
        } catch(Exception $e) {
            R::rollback();
        }
        
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
