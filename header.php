<?php
  $userid = $_SESSION['id'];
  $username = $_SESSION['username'];
  $result = mysqli_query($link, "SELECT id FROM users WHERE username = '$username'");
  if (mysqli_num_rows($result) == 0) {
    session_destroy();
    echo "<script>window.location = 'login.php'</script>";
    exit;
  } 
?>
<html>
  <head>
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>">
  </head>
  <body>
    <div id="noInternet">
      <div>
        <h1>No Internet</h1>
        <h2>Please check your internet connection.</h2>
        <h2>Try:</h2>
        <h2>Checking the network cables, modem, and router</h2>
        <h2>Reconnecting to Wi-Fi</h2>
      </div>
    </div>
    <?php
      $result = mysqli_query($link, "SELECT COUNT(*) FROM `notifications` WHERE userid=$userid");
      $row = mysqli_fetch_row($result)[0];
      
      $results = mysqli_query($link, "SELECT COUNT(*) FROM `tickets` WHERE userid=$userid");
      $rows = mysqli_fetch_row($results)[0];
    ?>
    <ul id="desktopul">
      <li><a class="active" href="home.php">Home Page (Messages)</a></li>
      <li><a href="profile.php?id=<?php echo $_SESSION['id']; ?>">My Account (<?php echo $_SESSION['username']?>)</a></li>
      <li><a href="notifications.php?id=<?php echo $_SESSION['id']; ?>">Notifications (<?php echo $row; ?>)</a></li>
      <li><a href="search.php">Search</a></li>
      <li><a href="contact.php">Create Ticket</a></li>
      <?php if ($_SESSION['admin'] != 0) echo '<li><a href="admin.php">Admin</a></li>'; ?>
      <?php if ($_SESSION['founder'] != 0) echo '<li><a href="logs.php">Logs</a></li>'; ?>
      <li><a href="mytickets.php">My Tickets (<?php echo $rows; ?>)</a></li>
      <li style="float:right"><a class="active" href="logout.php">Logout</a></li>
    </ul>
    <div id="phone-nav">
      <img src="logos/menu.svg" id="show-options" height="30" width="30" />
      <span id="site-name">Live Chat</span>
    </div>
    <div id="modal"></div>
    <div class="modal-content">
      <img src="logos/close.svg" alt="Close" srcset="" id="close">
      <ul id="phone-menu">
        <li><a href="home.php" id="btns">Home Page</a></li>
        <li><a href="notifications.php?id=<?php echo $_SESSION['id']; ?>" id="btns">Notifications (<?php echo $row; ?>)</a></li>
        <li><a href="profile.php?id=<?php echo $_SESSION['id']; ?>" id="btns">My Account (<?php echo $_SESSION['username']?>)</a></li>
        <li><a href="search.php" id="btns">Search</a></li>
        <li><a href="contact.php" id="btns">Create Ticket</a></li>
        <?php if ($_SESSION['admin'] != 0) echo '<li><a href="admin.php" id="btns">Admin</a></li>'; ?>
        <?php if ($_SESSION['founder'] != 0) echo '<li><a href="logs.php" id="btns">Logs</a></li>'; ?>
        <li><a href="mytickets.php" id="btns">My Tickets (<?php echo $rows; ?>)</a></li>
        <li><a href="logout.php" id="btns">Logout</a></li>
      </ul>
    </div>
    <script>
      console.log('Initially ' + (window.navigator.onLine ? 'on' : 'off') + 'line');
      let internet = document.getElementById("noInternet");
      window.addEventListener('online', function() {
        internet.style.transform = "scale(0)";
        window.location.reload(false);
      });
      window.addEventListener('offline', function() {
        internet.style.transform = "scale(1)";
      });
      let modalContent = document.getElementsByClassName("modal-content")[0];
      let btn = document.getElementById("show-options");
      let span = document.getElementById("close");
      btn.onclick = function() {
          modalContent.style = "transform: scaleX(1)";
        modal.style = "display: inline";
      }
      span.onclick = function() {
        modalContent.style = "transform: scaleX(0)";
        modal.style = "display: none";
      }
    </script>
  </body>
</html>
