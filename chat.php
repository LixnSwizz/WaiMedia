<?php
  require 'autoloader/autoload.inc.php';
  session_start();
  SessionCookie::keep_user_loggedin();
  $home = new Home();

  $id = $_GET['id'] ?? '';
  $username = $_GET['username'] ?? '';
  if (!empty($id) && !empty($username)) {
    SessionCookie::other_user_session($id, $username);
  }


  $home->get_chat_messages((int)$_SESSION['other_user_id']);

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $input_msg = $_POST['input-msg'];
    if (!$home->message_validation($input_msg, (int)$_SESSION['other_user_id'])) {
      die('Failed');
    }
  }

 ?>
 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>Chat</title>
   </head>
   <body>
     <h3><a href='index.php'>Home</a></h3>
     <div class="container">
       <div class="name">
         <?php echo $_SESSION['other_user_username']; ?>
       </div>
       <div class="cat-message">
         <?php echo $home->empty_message; ?>
         <?php
            if (empty($home->empty_message)) {
              while ($row = $home->chat->fetch()) {
                if ($_SESSION['main_user_id'] == $row['user_idfk_from']) {
                  echo $row['text_msg'].' '.$row['time'].'<br>';
                } else {
                  echo $row['text_msg'].' '.$row['time'].'<br>';
                }
              }
            }
          ?>
       </div>
       <div class="input-box">
         <form class="input" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
           <textarea name="input-msg" rows="2" cols="24"></textarea>
           <input type="submit" name="submit" value="Send">
         </form>
       </div>
     </div>
   </body>
 </html>
