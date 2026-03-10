#Test
Description: New functions in PHP 8.1
Parser: 8.1
Min: 8.1
EveryLine: true

<?php
array_is_list();
imagecreatefromavif();
imageavif();
pcntl_rfork();
fsync();
fdatasync();
sodium_crypto_stream_xchacha20();
sodium_crypto_stream_xchacha20_keygen();
sodium_crypto_stream_xchacha20_xor();
sodium_crypto_core_ristretto255_add();
sodium_crypto_core_ristretto255_from_hash();
sodium_crypto_core_ristretto255_is_valid_point();
sodium_crypto_core_ristretto255_random();
sodium_crypto_core_ristretto255_scalar_add();
sodium_crypto_core_ristretto255_scalar_complement();
sodium_crypto_core_ristretto255_scalar_invert();
sodium_crypto_core_ristretto255_scalar_mul();
sodium_crypto_core_ristretto255_scalar_negate();
sodium_crypto_core_ristretto255_scalar_random();
sodium_crypto_core_ristretto255_scalar_reduce();
sodium_crypto_core_ristretto255_scalar_sub();
sodium_crypto_core_ristretto255_sub();
sodium_crypto_scalarmult_ristretto255();
sodium_crypto_scalarmult_ristretto255_base();
?>