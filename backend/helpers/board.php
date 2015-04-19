<?php
    function validateBoardName($board){
        return ($board === preg_replace('/[^a-z0-9]/','',$board));
    }
    
    function boardExists($urlname){
        $board = boardByURL($urlname);
        return (!empty($board) && $board[COMMONS::ID] > 0);
        
    }
    
    function boardByURL($urlname){
         return R::findOne(TBOARD::TABLE, TBOARD::URLNAME.' = ? ', array($urlname));
    }
        
    function createBoard($urlname, $name, $defaultposter) {
        $board = R::dispense(TBOARD::TABLE);
        $board[TBOARD::URLNAME] = $urlname;
        $board[TBOARD::NAME] = $name;
        $board[TBOARD::DEFAULTPOSTER] = $defaultposter;
        R::store($board);
    }
?>
