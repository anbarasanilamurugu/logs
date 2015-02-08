<?php
include_once 'includes/connect.php';
include_once 'includes/functions.php';
 
sec_session_start();
 
if (login_check($mysqli) == true) {
    header('Location:home.php');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Secure Login: Log In</title>
    </head>
    <body>
        <?php
        if (isset($_GET['error'])) {
            echo '<p class="error">Error Logging In!</p>';
        }
        ?> 
        <form action="includes/process_login.php" method="post" name="login_form">                      
			<table align='center'  style="border-collapse: collapse; padding:10px;" >
				<tr>
					<td>Username: </td>
					<td><input type="text" name="username" /></td>
				</tr>
				<tr>
					<td>Password: </td>
					<td><input type="password" name="password" id="password" /></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" value="Login" /> </td>
				</tr>
			</table>
        </form>
     </body>
</html>