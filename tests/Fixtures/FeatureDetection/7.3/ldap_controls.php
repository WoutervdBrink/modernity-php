#Test
Description: LDAP controls were added in PHP 7.3.
Parser: 7.3
Min: 7.3
EveryLine: true

<?php
ldap_add($ldap, $dn, $entry, $controls);
ldap_mod_replace($ldap, $dn, $entry, $controls);
ldap_mod_add($ldap, $dn, $entry, $controls);
ldap_mod_del($ldap, $dn, $entry, $controls);
ldap_rename($ldap, $dn, $new_rdn, $new_parent, $delete_old_rdn, $controls);
ldap_compare($ldap, $dn, $attribute, $value, $controls);
ldap_delete($ldap, $dn, $controls);
ldap_modify_batch($ldap, $dn, $info, $controls);
ldap_search($ldap, $base, $filter, $attributes, $attributes_only, $sizelimit, $timelimit, $deref, $controls);
ldap_list($ldap, $base, $filter, $attributes, $attributes_only, $sizelimit, $timelimit, $deref, $controls);
ldap_read($ldap, $base, $filter, $attributes, $attributes_only, $sizelimit, $timelimit, $deref, $controls);
ldap_parse_result($ldap, $result, $error_code, $matched_dn, $error_message, $referrals, $controls);
?>
