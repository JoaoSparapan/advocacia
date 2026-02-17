<?php


class ExceptionAlert
{
    private $message='';
    private $time=0;

    function __construct(string $message='', int $time=2000) {
        $this->message = $message;
        $this->time= $time;
    }
    public function alerts(string $type='success', string $title='Sucesso', string $path="../../js/swal/sweetalert2.all.min.js")
    {
        return '<!DOCTYPE html><html><head><link rel="stylesheet" href="../../styles/css/swal/sweetalert2.min.css"></head>
        <script type="text/javascript" src="'.$path.'"></script>
        <script type="text/javascript">
        window.onload = function() {
            Swal.fire({
                icon: "'.$type.'",
                title: "'.$title.'",
                text: "'.$this->message.'",
                showConfirmButton: false,
                timer: '.$this->time.'
            })
        }
        </script>
    </div></html>';

    }
    public function alertNewProv(string $type='warning', string $title='Alerta', string $textCancelButton='Ok')
    {
        return '
        <script type="text/javascript" src="./js/swal/sweetalert2.all.min.js"></script>
        <script type="text/javascript">
        window.onload = function() {
            
            Swal.fire({
                icon: "'.$type.'",
                title: "'.$title.'",
                text: "'.$this->message.'",
                showConfirmButton: false,
                // timer: '.$this->time.',
                showConfirmButton: true,
                confirmButtonText: "'.$textCancelButton.'",
                
            }).then((result)=>{
                
            })
        }
        </script>';

    }
public function test(string $requestUri)
{
return $requestUri;
}
}

// $op = new ExceptionAlert("Há novas solicitações de cadastro a serem avaliadas", 1000);
// echo $op->alertNewUser();