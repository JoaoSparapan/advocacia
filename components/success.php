<link rel="stylesheet" href="../styles/css/swal/sweetalert2.min.css">
<div class="error">

    <?php
        $msg='Sucesso';
        $time=2000;
        if(isset($_GET['message'])){
            $msg = $_GET['message'];
            
        }
        if(isset($_GET['time'])){
            $time = $_GET['time'];
        }

    ?>
    <script type="text/javascript" src="../js/swal/sweetalert2.all.min.js"></script>
    <script type="text/javascript">
    window.onload = function() {
        var v = "<?php echo $msg; ?>";
        var t = "<?php echo $time; ?>";
        Swal.fire({
            icon: 'success',
            title: 'Sucesso',
            text: v,
            showConfirmButton: false,
            timer: t
        })
    }
    </script>
</div>