#Test
Description: stream_socket_enable_crypto() allows the crypto_type parameter to be optional since PHP 5.6.
Parser: 5.6
Min: 5.6

<?php
stream_socket_enable_crypto(1, 2);
?>

#Test
Description: stream_socket_enable_crypto() allows the crypto_type parameter to be optional since PHP 5.6.
Parser: 5.6

<?php
stream_socket_enable_crypto(1, 2, 3);
?>
