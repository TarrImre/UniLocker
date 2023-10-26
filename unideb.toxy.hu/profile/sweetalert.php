<script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<?php
function successMsg($title, $text)
{
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
function errorMsg($title, $text)
{
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

function questionMsg($title,$text, $url)
{
    echo '<script type="text/javascript">
            Swal.fire({
            title: "'.$title.'",
            text: "'.$text.'",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#FE9D9E",
            cancelButtonColor: "#3498db",
            allowOutsideClick: false,
            confirmButtonText: "Igen",
            cancelButtonText: "Nem"
            }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "'.$url.'";
                window.history.replaceState( null, null, window.location.href );
            }
            else{
                if ( window.history.replaceState ) {
                    window.history.replaceState( null, null, window.location.href );
                  }
            }
            })
            </script>';
}
?>