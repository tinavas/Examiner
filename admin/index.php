<?php
require '../inc/PasswordHash.php';
$t_hasher = new PasswordHash(8, FALSE);
include('../config.php');
header("Content-Type: text/html; charset=utf-8");

if (isset($_REQUEST["uname"])) {
    $uname = $_REQUEST["uname"];
    $pass = $_REQUEST["pass"];
    $db = mysql_connect(_DBHOST, _DBUSER, _DBPASS);
    mysql_select_db(_DBNAME, $db);
    $check_security=mysql_query("SELECT * FROM settings WHERE admin_id='$uname'", $db);
    $st=mysql_fetch_row($check_security);
    $stored_hash=$st[2];
    $check = $t_hasher->CheckPassword($pass, $stored_hash);
    $hash = $t_hasher->HashPassword('admin');
    if ($check) {
        $rec = mysql_fetch_row($check_security);
        header('Location: index');
        setcookie('examiner', 'examiner', time() + 36000, '/');
    } else
        header('Location: index?wrong');
    include('../main.php');
} else { //if not trying to login
    include('admin_config.php');

    if (!isset($_COOKIE['examiner'])) { //if admin is not logged in

        echo ('
            <article>
            <form method="POST" action="index" onSubmit="return CheckForm(this);">
            <div class="content login box">
	        <h1 class="title">' . _ADMIN_SYSTEM_ALERT . '</h1>
	        ');
	        if (isset($_REQUEST['wrong'])) {
                echo ('<div class="error"><span data-icon="w" aria-hidden="true"></span> ' . _ADMIN_SYSTEM_ALERT_WRONG_PREVIOUS_U_P . '</div>');
            }
        echo ('
	        <div class="label '. $align .'">' . _ADMIN_USERNAME . ':</div>
	        <input type="text" name="uname" dir="ltr">

	        <div class="label '. $align .'">' . _ADMIN_PASSWORD . ':</div>
	        <input type="password" name="pass" dir="ltr">

            <div class="button_wrap left clearfix">
	        <input  class="button good" type="submit" value="' . _ADMIN_ENTER . '" name="B1">
            </div>

            </div>
            </form>
            </article>
        ');
    } else {
        echo ('
            <article id="grid_wrap">
			<nav class="grid admin">
			<div id="pad" class="content clearfix">
			<ul>
                <li><a href="add_test"><div data-icon="a" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_INDEX_ADD_EXAM . '</div></a></li>
                <li><a href="all_tests"><div data-icon="t" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_EDIT_EXAMS . '</div></a></li>
                <li><a href="default"><div data-icon="d" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_DEFINE_DEFAULT . '</div></a></li>
                <li><a href="users"><div data-icon="u" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_ADD_EDIT_USER . '</div></a></li>
                <li><a href="charts"><div data-icon="c" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_CHARTS . '</div></a></li>
                <li><a href="settings"><div data-icon="s" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_SETTINGS . '</div></a></li>
            </ul>
            </div>
			</nav>
            </article>
        ');
    }
}
?>

<?php include('../footer.php');
echo ('
        <script language="JavaScript">
            function CheckForm(formID) {
                if (formID.uname.value == "") { alert("' . _ADMIN_ENTER_USERNAME . '");
                formID.uname.focus(); return false; }
                if (formID.pass.value == "") { alert("' . _ADMIN_ENTER_PASSWORD . '");
                formID.pass.focus(); return false; }
                return true;
            }
		</script>
		');

include('../footer_end.php');?>