<?php
class Pagination{



    function __construct(){

    }

    public function createPagination($page="1",$users=[],$url="users", $params=""){

            $div = intval(sizeof($users)/10);
            $pagination="";
            if(intval($page)<=1){
                $pagination.='
                    <li class="disabled"><a href="./'.$url.'"><i class="fa-solid fa-angle-left"></i></a></li>
                ';
            }else{
                $pagination.='
                    <li class=""><a href="'.$url.'?pagination_number='.(intval($page)-1)."&".$params.'"><i class="fa-solid fa-angle-left"></i></a></li>
                ';
            }
            if($div+1<=5){
                
                for($i=1;$i<=$div+1;$i++){
                    $pagination.= '
                    <li class="'.($i==intval($page) ? "active" : "waves-effect" ).'">
                        <a href="'.$url.'?pagination_number='.(intval($i))."&".$params.'">'.$i.'</a>
                    </li>
                    
                    ';
                }
            }else{
                $plus=2;
                if($page>1){
                    $pagination.= '
                    <li class="'.(1==intval($page) ? "active" : "waves-effect" ).'">
                        <a href="'.$url.'?pagination_number='.(intval(1))."&".$params.'">1</a>
                    </li>
                    <li class="disabled">
                        ...&nbsp;
                    </li>
                    ';
                    
                }
                // if($page!=$div+1){
                    if($page-1 > 1){
                        $pagination.= '
                    <li class="waves-effect">
                        <a href="'.$url.'?pagination_number='.(intval($page-1))."&".$params.'">'.($page-1).'</a>
                    </li>
                    
                    ';
                    }
                    
                    // if($page!=$div+1){
                        $pagination.= '
                    <li class="active">
                        <a href="'.$url.'?pagination_number='.(intval($page))."&".$params.'">'.$page.'</a>
                    </li>
                    
                    ';
                    // }
                    if($page+1 <= $div+1){
                    $pagination.= '
                    <li class="waves-effect">
                        <a href="'.$url.'?pagination_number='.(intval($page+1))."&".$params.'">'.($page+1).'</a>
                    </li>
                    
                    ';
                    }
                    
                    
                    

                    if($page < $div){
                        $pagination.= '
                    <li class="disabled">
                        &nbsp;...
                    </li>
                    <li class="'.($page==intval($div+1) ? "active" : "waves-effect" ).'">
                        <a href="'.$url.'?pagination_number='.(intval($div+1))."&".$params.'">'.intval($div+1).'</a>
                    </li>
                    
                    ';
                    }
                // }else{
                    
                //     $pagination.= '
                //     <li class="waves-effect">
                //         <a href="'.$url.'?pagination_number='.(intval($page-3))."&".$params.'">'.($page-3).'</a>
                //     </li>
                    
                //     ';
                //     $pagination.= '
                //     <li class="waves-effect">
                //         <a href="'.$url.'?pagination_number='.(intval($page-2))."&".$params.'">'.($page-2).'</a>
                //     </li>
                    
                //     ';
                //     $pagination.= '
                //     <li class="waves-effect">
                //         <a href="'.$url.'?pagination_number='.(intval($page-1))."&".$params.'">'.($page-1).'</a>
                //     </li>
                    
                //     ';
                //     $pagination.= '
                    
                //     <li class="'.($page==intval($div+1) ? "active" : "waves-effect" ).'">
                //         <a href="'.$url.'?pagination_number='.(intval($div+1))."&".$params.'">'.intval($div+1).'</a>
                //     </li>
                    
                //     ';
                // }
                
            }

            if(intval($page)*10 >= sizeof($users)){
                $pagination.='
                    <li class="disabled"><a href="./'.$url.'?pagination_number='.(intval($page))."&".$params.'"><i class="fa-solid fa-angle-right"></i></a></li>
                ';
            }else{
                $pagination.='
                    <li class=""><a href="'.$url.'?pagination_number='.(intval($page)+1)."&".$params.'"><i class="fa-solid fa-angle-right"></i></a></li>
                ';
            }


        return $pagination;


    }
}