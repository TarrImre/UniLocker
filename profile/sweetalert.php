<script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<?php
    function successMsg($title, $text){
    echo "<script type='text/javascript'>   
        $(document).ready(function() {
            Swal.fire({
                icon: 'success',
                title: '$title',
                text: '$text',
                timer: 2000,
                allowOutsideClick: false,
                showCancelButton: false,
                showConfirmButton: false
            })
        });   
     </script>";
    }
    function errorMsg($title, $text){
        echo "<script type='text/javascript'>   
            $(document).ready(function() {
                Swal.fire({
                    icon: 'error',
                    title: '$title',
                    text: '$text',
                    timer: 2000,
                    allowOutsideClick: false,
                    showCancelButton: false,
                    showConfirmButton: false
                })
            });   
         </script>";
        }
?>
