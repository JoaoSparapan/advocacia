<?php

try{

    session_start();
    $_SESSION['isOpenModal']=0;
    session_destroy();
    echo "true";
}catch(Exception $e){
    echo ""+$e;
}