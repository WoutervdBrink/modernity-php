#Test
Description: Removed functions in PHP 8.0.
Parser: 8.0
Max: 7.4
EveryLine: true

<?php
create_function();
each();
read_exif_data();
image2wbmp();
png2wbmp();
jpeg2wbmp();
gmp_random();
imap_header();
ldap_sort();
ldap_control_paged_result();
ldap_control_paged_result_response();
mbregex_encoding();
mbereg();
mberegi();
mbereg_replace();
mberegi_replace();
mbsplit();
mbereg_match();
mbereg_search();
mbereg_search_pos();
mbereg_search_regs();
mbereg_search_init();
mbereg_search_getregs();
mbereg_search_getpos();
mbereg_search_setpos();
oci_internal_debug();
ociinternaldebug();
hebrevc();
convert_cyr_string();
money_format();
ezmlm_hash();
restore_include_path();
get_magic_quotes_gpc();
get_magic_quotes_runtime();
fgetss();
xmlrpc_decode();
xmlrpc_decode_request();
xmlrpc_encode();
xmlrpc_encode_request();
xmlrpc_get_type();
xmlrpc_is_fault();
xmlrpc_parse_method_descriptions();
xmlrpc_server_add_introspection_data();
xmlrpc_server_call_method();
xmlrpc_server_create();
xmlrpc_server_destroy();
xmlrpc_server_register_introspection_callback();
xmlrpc_server_register_method();
xmlrpc_set_type();
gzgetss();
?>
