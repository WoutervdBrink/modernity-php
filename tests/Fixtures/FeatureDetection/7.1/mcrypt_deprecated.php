#Test
Description: The mcrypt extension is deprecated in PHP 7.1
Parser: 7.1
Max: 7.0
EveryLine: true

<?php
mcrypt_create_iv();
mcrypt_decrypt();
mcrypt_enc_get_algorithms_name();
mcrypt_enc_get_block_size();
mcrypt_enc_get_iv_size();
mcrypt_enc_get_key_size();
mcrypt_enc_get_modes_name();
mcrypt_enc_get_supported_key_sizes();
mcrypt_enc_is_block_algorithm();
mcrypt_enc_is_block_algorithm_mode();
mcrypt_enc_is_block_mode();
mcrypt_enc_self_test();
mcrypt_encrypt();
mcrypt_generic();
mcrypt_generic_deinit();
mcrypt_generic_init();
mcrypt_get_block_size();
mcrypt_get_cipher_name();
mcrypt_get_iv_size();
mcrypt_get_key_size();
mcrypt_list_algorithms();
mcrypt_list_modes();
mcrypt_module_close();
mcrypt_module_get_algo_block_size();
mcrypt_module_get_algo_key_size();
mcrypt_module_get_supported_key_sizes();
mcrypt_module_is_block_algorithm();
mcrypt_module_is_block_algorithm_mode();
mcrypt_module_is_block_mode();
mcrypt_module_open();
mcrypt_module_self_test();
mdecrypt_generic();
echo MCRYPT_MODE_ECB;
echo MCRYPT_MODE_CBC;
echo MCRYPT_MODE_CFB;
echo MCRYPT_MODE_OFB;
echo MCRYPT_MODE_NOFB;
echo MCRYPT_MODE_STREAM;
echo MCRYPT_ENCRYPT;
echo MCRYPT_DECRYPT;
echo MCRYPT_DEV_RANDOM;
echo MCRYPT_DEV_URANDOM;
echo MCRYPT_RAND;
?>