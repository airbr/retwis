<?
include("retwis.php");

# Form sanity checks
if (!gt("username") || !gt("password"))
    goback("You need to enter both username and password to login.");

# The form is ok, check if the username is available
$username = gt("username");
$rawpassword = gt("password");
$r = redisLink();
$userid = $r->hget("users",$username);
if (!$userid)
    goback("Wrong username or password");
$salt = $r->hget("user:$userid","salt");
$realpassword = $r->hget("user:$userid","password");

if ($realpassword != hash('sha512', $rawpassword.$salt))
    goback("Wrong useranme or password");

# Username / password OK, set the cookie and redirect to index.php
$authsecret = $r->hget("user:$userid","auth");
setcookie("auth",$authsecret,time()+3600*24*365);
header("Location: index.php");
?>
