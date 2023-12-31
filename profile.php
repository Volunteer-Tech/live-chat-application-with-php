<?php
  session_start();
  require 'config/config.php';

  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php?redirect_link=profile.php");
    exit;
  } else if (empty($_GET['id'])) {
    header('Location: index.php');
    exit();
  } else {
    $id = $_GET['id'];

    $queryString = "SELECT * FROM users WHERE id='$id' ORDER BY username DESC LIMIT 1"; 
    $result = mysqli_query($link, $queryString);
    $row = mysqli_fetch_assoc($result);

    $user_id = $row['id'];
    $user_name = $row['username'];
    $email = $row['email'];
    $created_at = $row['created_at'];
    $email = $row['email'];
    $bio = $row['bio'];
    $file = $row['file'];
    $admin = $row['admin'];
    $send_message = $row['send_message'];
    $founder = $row['founder'];
    $banned = $row['banned'];
    $ip = $row['ip'];
    $last_ip = $row['last_ip'];
    $logged = $row['logged'];
    $verified = $row['verified'];
    $invite_link = $row['invite_link'];
    $invites = $row['invites'];

    $err_message = "";
    if (!empty($_GET['err_message'])) {
      $err_message = $_GET['err_message'];
    }
  }
?>
<!DOCTYPE html>
<html> 
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
    <title><?php echo $user_name; ?>'s profile</title>
    <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/profile.css?v=<?php echo time(); ?>">
    <script>
      function showImg() {
        let picture = document.getElementById("profileIMG");
        picture.style.transform = "scale(1)";
      }
      document.onkeydown = function(evt) {
        evt = evt || window.event;
        if (evt.keyCode == 27) {
          unshowImg();
        }
      };
      function unshowImg() {
        let picture = document.getElementById("profileIMG");
        picture.style.transform = "scale(0)";
      }
    </script>
  </head>
  <body>
    <?php require_once("header.php"); ?>
    <h2 id="err_message"><?php echo $err_message; ?></h2>
    <div class="wrapper">
      <?php
        $bio = wordwrap($bio, 25, "\n", true);
        $bio = nl2br($bio, false);
      ?>
      <div id="user-interface">
        <div id="profileIMG">
          <div>
            <div id="topRight">
              <span>Press <b>esc</b> or click this button.</span>
              <img src="logos/close.svg" alt="Close" srcset="" id="closeImg" onClick='unshowImg();'>
            </div>
            <h1><?php echo $user_name . "'s Profile Picture" ?></h1>
            <img src='<?php echo $file; ?>' alt="Profile Photo" id="pictureFullScreen" />
            <p style="margin: 0;"><a href="<?php echo $file; ?>" style="text-decoration: underline;" download="profile_picture_<?php echo $user_name; ?>.jpg">Download image</a></p>
          </div>
        </div>
        <?php
          echo "
            <div id='img_div'>
              <img id='image-x' src='$file' alt='Profile Photo' onClick='showImg();' />
              <br>
              <div id='nameOnline'>
              <h1>$user_name</h1>
          ";
              if ($logged == 1) {
                echo "<span id='online'>Online</span>";
              } else if ($logged == 0) {
                echo "<span id='offline'>Offline</span>";
              }
          echo "
              </div>
              <p id='bio'>
          ";
          if ($bio == "") {
            echo '';
          } else {
            echo $bio;
          }
          echo "
            </p>
          ";
          if ($_SESSION['id'] == $user_id) {
            echo "<div id='two-changes'><a href='change-photo.php' id='button-user'><p>change photo</p></a><a href='change-name.php' id='button-user'><p>change name</p></a><p id='button-invite'>copy invite link</p></div>";
          }
          echo "</div>";
        ?>
        <div id="roles">
          <?php
            if ($admin == 1) {
              echo ' <a href="admins.php"><p id="admin">Admin Chat</p></a>';
            }
            if ($founder == 1) {
              echo ' <a href="founders.php"><p id="founder">Founder Chat</p></a>';
            }
            if ($banned == 1) {
              echo ' <a href="banned.php"><p id="banned">Banned</p></a>';
            }
            if ($verified == 1) {
              echo ' <a href="verified.php"><p id="verified">Verified</p></a>';
            }
            if ($verified == 0) {
              echo ' <a href="verified.php"><p id="verified" class="not-verified">Not Verified</p></a>';
            }
            echo ' <a href="invites.php?id='.$user_id.'"><p id="invited">'.$invites.' invites</p></a>';
          ?>
        </div>
      </div>
      <div id="user-settings">
        <h2>Profile Details</h2>
        <?php
          if ($_SESSION['id'] == $user_id) {
            echo "
              <div class='title-of-div'>
                <div class='title-text'>
                  Bio
            ";
            if ($bio != "") {
              echo "<a href='delete-bio.php' id='button-user'>delete bio</a>";
            }
            echo "
              <a href='change-bio.php' id='button-user'>change</a>
                </div>
              </div>
            ";
          }
        ?>
        <div class="title-of-div">
          <div class="title-text">Account ID</div> 
          <div class="content-text">#<?php echo $user_id; ?></div>
        </div>
        <?php
          if ($_SESSION['founder'] == 1 && $_SESSION['id'] != $user_id) {
            if ($admin == 0) {
              echo "
              <div class='title-of-div'>
                <div class='title-text'>Admin <a href='actions.php?id=$user_id&action=make_admin' id='button-user'>make admin</a></div>
                  <div class='content-text'>
                    No
                  </div>
              </div>
              ";
            } else if ($admin == 1) {
              echo "
              <div class='title-of-div'>
                <div class='title-text'>Admin <a href='actions.php?id=$user_id&action=remove_admin' id='button-user'>remove admin</a></div>
                  <div class='content-text'>
                    Yes
                  </div>
              </div>
              ";
            }
          }
          if ($_SESSION['admin'] == 1 && $_SESSION['id'] != $user_id) {
            if ($banned == 0) {
              echo "
                <div class='title-of-div'>
                  <div class='title-text'>Banned <a href='actions.php?action=ban&id=$user_id' id='button-user'>ban user</a></div>
                    <div class='content-text'>
                      No
                    </div>
                </div>
              ";
            } else if ($banned == 1) {
              echo "
                <div class='title-of-div'>
                  <div class='title-text'>Banned <a href='actions.php?action=unban&id=$user_id' id='button-user'>unban user</a></div>
                    <div class='content-text'>
                      Yes
                    </div>
                </div>
              ";
            }
          }
        ?>
        <?php 
          if ($_SESSION['admin'] == 1) {
            $result = mysqli_query($link, "SELECT COUNT(*) FROM `notifications` WHERE userid=$user_id");
            $user_notifications = mysqli_fetch_row($result)[0];
            $notifications_button = "";
            if ($user_notifications > 0) {
              $notifications_button = "<a href='notifications.php?id=$user_id' id='button-user'>show</a> <a href='actions.php?action=delete_nofitications&id=$user_id' id='button-user'>delete</a>";
            }
            echo "
              <div class='title-of-div'>
                <div class='title-text'>Notifications $notifications_button</div>
                <div class='content-text'>$user_notifications notifications</div>
              </div>
            ";
          }
        ?>
        <div class="title-of-div">
          <div class="title-text">Account has been created at</div>
          <div class="content-text">
            <?php 
              echo $created_at; 
              if ($_SESSION['founder'] == 1) {
                echo " (account created with IP: $ip)";
              }
            ?>
          </div>
        </div>
        <?php
          if ($_SESSION['id'] == $user_id) {
        ?>
        <div class="title-of-div">
          <div class="title-text">Send messages using Enter <?php echo "<a href='actions.php?action=set_send_message&send_message=$send_message&userid=$user_id' id='button-user'>change</a>"; ?></div>
          <div class="content-text">
            <?php
              if ($send_message == 1) {
                echo "Yes";
              } else {
                echo "No";
              }
            ?>
          </div>
        </div>
        <?php
          }
          if ($_SESSION['founder'] == 1 || $_SESSION['id'] == $user_id) {
            echo "
              <div class='title-of-div'>
              <div class='title-text'>Last IP</div>
                <div class='content-text'>
                  $last_ip
                </div>
              </div>
            ";
          }
        ?>
        <?php 
          if ($_SESSION['id'] == $user_id) {
            echo "
              <div class='title-of-div'>
                <div class='title-text'>
                  Password <a href='reset-password.php' id='button-user'>change</a>
                </div>
                <div class='content-text'>********</div> 
              </div>
            ";
          }
        ?>
        <div class="title-of-div">
          <div class="title-text">Email
            <?php
              if ($_SESSION['id'] != $user_id) {
                echo ' <a href="mailto:<?php echo $email; ?>" id="button-user">send an email</a>';
              }
              if ($_SESSION['id'] == $user_id) {
                echo ' <a href="change-email.php" id="button-user">change</a>';
              }
            ?>
          </div>
          <div class="content-text">
            <?php 
              echo $email;
            ?> 
          </div> 
        </div>
        <div class="title-of-div">
          <div class="title-text">
            Verified Account
            <?php
              if ($_SESSION['id'] == $user_id && $verified == 0) {
                echo ' <a href="verify-account.php" id="button-user">verify</a>';
              }
            ?>
          </div>
          <div class="content-text">
            <?php 
              if ($verified == 0) {
                echo "The account is not verified";
              } else {
                echo "The account is verified";
              }
            ?> 
          </div> 
        </div>
      </div>
    </div>
    <script>
      let buttonInvite = document.querySelector("#button-invite");
      buttonInvite.addEventListener("click", () => {
        let invite_link = "<?php echo "https://$_SERVER[SERVER_NAME]/register.php?invite_link=$invite_link"; ?>";
        navigator.clipboard.writeText(invite_link).then(() => {
          alert("The link has been copied to the clipboard!");
        });
      });
      setTimeout(() => {
        let p = document.getElementById("err_message");
        
        if (p.innerHTML != "") {
          p.innerHTML = "";
        }
      }, 5000);
    </script>
  </body>
</html>
