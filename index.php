<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="description" content="Project Climb bouldering store">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="css\style.css">
  <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>
  <!-- FA -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
  <!-- GFonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
  <!-- MDB + BS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

  <title>Project Climb</title>
</head>
<link rel="icon" type="image/png" href="icon.png">
<!-- getUserState on page load -->

<body onload="getUserState()">
  <script src="https://code.jquery.com/jquery-2.2.3.min.js" integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>

  <?php
  session_start();
  session_regenerate_id();
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL & ~E_NOTICE);

  require_once('includes/autoloader.php');
  require_once('includes/db.php');
  // require_once('includes/main.php');

  // $Product = new Product($Conn);
  // $Products = $Product->getAllProducts();
  // $products_json = json_encode((array)$Products);


  $Product = new Product($Conn);
  $products = $Product->getAllProducts();
  //weird issue with text encoding text datatype to json
  //using varchar(8000) instead, maybe json_encode($array, JSON_HEX_QUOT); ????
  // $products_json = json_encode((array)$products);
  $products_json = json_encode((array)$products);
  // return php $result as array for use in JS.
  ?>

  <script type="text/javascript">
    var products = <?php echo $products_json ?>;
  </script>

  <nav class="navbar navbar-expand-md navbar-light bg-nav" style="box-shadow:none">
    <div class="container">

      <a class="navbar-brand" href=".">
        <img src="assets/logo-inverted.png" height="60px">
      </a>

      <ul class="navbar-nav ml-auto d-block d-md-none">
        <li class="nav-item">
          <a class="btn btn-link" href="#"><i class="bx bxs-cart icon-single"></i> <span class="badge badge-danger"></span></a>
        </li>
      </ul>

      <div class="collapse navbar-collapse" style="margin-left: 600px;">
        <!-- <form class="form-inline mx-auto">
          <input id="searchInput" class="form-control" type="search" placeholder="Search for products..." aria-label="Search" style="width: 420px">
          <button id="searchBtn" class="btn btn-success" type="submit"><span class="iconify" data-icon="bx-bx-search" data-inline="false"></span></button>
        </form> -->

        <ul class="navbar-nav">
          <li class="nav-item my-cart-icon" id="basket">
            <a class="btn btn-link" href="#"><span class="iconify" data-icon="bx-bxs-cart" data-inline="false" id="cart"></span>
              <span class="badge badge-danger my-cart-badge">3</span></a>
          </li>
          <li class="nav-item">
            <form action="includes/logout.php">
              <button id="storeLogoutBtn" style="display: none; margin-top:10px;" class="btn btn-danger storeLogoutBtn" href="#">Logout</button>
            </form>
          </li>
          <li class="nav-item">
            <button id="storeOrdersBtn" style="display: none; margin-top:10px;" class="btn btn-primary storeOrdersBtn" href="#">Orders</button>
          </li>
          <li class="nav-item">
            <button id="storeLoginBtn" style="margin-top:10px;" class="btn btn-primary storeLoginBtn" href="#">Back to store</button>
          </li>
        </ul>
      </div>

    </div>
  </nav>

  <!-- PHP Register/Login form validation -->
  <?php
  $User = new User($Conn);

  if ($_POST) {
    if ($_POST['register']) {
      if (!$_POST['email']) {
        $error = "Email not set.";
      } else if (!$_POST['password']) {
        $error = "Password not set.";
      } else if (!$_POST['firstname']) {
        $error = "First name not set.";
      } else if (!$_POST['lastname']) {
        $error = "Last name not set.";
      } else if (!$_POST['address1']) {
        $error = "Address line 1 not set.";
      } else if (!$_POST['address2']) {
        $error = "Address line 2 not set.";
      } else if (!$_POST['city']) {
        $error = "City not set.";
      } else if (!$_POST['postcode']) {
        $error = "Postcode not set.";
      } else if (!$_POST['country']) {
        $error = "Country not set.";
      } else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
      } else if (strlen($_POST['password']) < 8) {
        $error = "Password does not meet length requirement of 8 characters.";
      }

      if ($error) {
  ?>
        <script type="text/javascript">
          console.log("Register failed, showing container");
        </script>
        <?php
        echo $error;
        echo "<style>.credentialsContainer{display:block;}</style>";
      } else {
        //register
        try {
          $attempt = $User->createUser($_POST);
        } catch (PDOException $e) {
          $error = "User already exists in database.";
        }


        if ($attempt) {
          $success = "Account has been created. Please login.";
        ?>
          <script type="text/javascript">
            $(".storeContainer").hide();
            $(".registerContainer").hide();
            $(".credentialsContainer").show();
            $(".loginContainer").show();
          </script>
        <?php
        } else {
          $error = "An error occured, please try again later.";
        }
      }
    } else if ($_POST['login']) {
      if (!$_POST['email']) {
        $error = "Email not set.";
      } else if (!$_POST['password']) {
        $error = "Password not set.";
      } else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
      } else if (strlen($_POST['password']) < 8) {
        $error = "Password does not meet length requirement of 8 characters.";
      }

      if ($error) {
        ?>
        <script type="text/javascript">
          $(".storeContainer").hide();
          $(".credentialsContainer").show();
        </script>
  <?php
        //don't really need to do anything?
      } else {
        //login
        $user_data = $User->loginUser($_POST);
        // $current_user = $_POST['email'];
        $_SESSION['current_user'] = $_POST['email'];

        if ($user_data) {
          $_SESSION['is_logged_in'] = true;
          $_SESSION['user_data'] = $user_data;


          $success = "Logged in successfully.";

          echo "<meta http-equiv='refresh' content='0'>";
        } else {
          $_SESSION['is_logged_in'] = false;
          $error = "Incorrect login credentials.";
        }
      }
    }
  }

  ?>

  <!-- <script type="text/javascript">
    var current_user = "?php echo $current_user ?>";
    console.log(current_user);
  </script> -->

  <script>
    var current_user = "<?php echo $_SESSION['current_user']; ?>";
    console.log(current_user);
    localStorage.setItem("current_user", current_user);
    // getUserState resets formID localStorage, while setting whether user is currently loggedIn.
    // localStorage used alongside PHP due to PHP only being run on page load, making passing of variables difficult.
    function getUserState() {
      var loggedIn = "<?php echo $_SESSION['is_logged_in'] == 'yes' ? 'true' : 'false'; ?>";
      // console.log("getUserState() = " + loggedIn);
      localStorage.setItem("loggedIn", loggedIn);
      window.localStorage.removeItem("formID");
    }

    // on form submit, grab $this formID.
    // used for storing current form in localStorage for reference
    $(document).delegate('form', 'submit', function(event) {
      var $form = $(this);
      var formID = $form.attr('id');
      localStorage.setItem("formID", formID);
    });
  </script>

  <div class="credentialsContainer" style="display: none;">
    <div class="row">
      <div class="col-md-4 offset-md-4">
        <h3 class="text-center mt-4 mt-1" style="margin-bottom: 20px;">Please login to use our services.</h4>
          <div class="registerContainer store">
            <div class="card">
              <header class="card-header">
                <h4 class="card-title mt-2">Register</h4>
              </header>
              <article class="card-body">
                <form id="registration-form" method="post" action="">
                  <div class="form-row">
                    <div class="col form-group">
                      <label>First name</label>
                      <input type="text" class="form-control" placeholder="" id="reg_firstname" name="firstname">
                    </div>
                    <div class="col form-group">
                      <label>Last name</label>
                      <input type="text" class="form-control" placeholder="" id="reg_lastname" name="lastname">
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Email address</label>
                    <input type="email" class="form-control" placeholder="" id="reg_email" name="email">
                    <small class="form-text text-muted">We'll never share your email with anyone else.</small>
                  </div>
                  <div class="form-group">
                    <label>Address Line 1</label>
                    <input type="text" class="form-control" placeholder="" id="reg_address1" name="address1">
                  </div>

                  <div class="form-row">
                    <div class="col form-group">
                      <label>Address Line 2</label>
                      <input type="text" class="form-control" placeholder="" id="reg_address2" name="address2">
                    </div>
                    <div class="col form-group">
                      <label>Postcode</label>
                      <input type="text" class="form-control" placeholder="" id="reg_postcode" name="postcode">
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label>City</label>
                      <input type="text" class="form-control" id="reg_city" name="city">
                    </div>
                    <div class="form-group col-md-6">
                      <label>Country</label>
                      <select id="inputState" class="form-control" id="reg_country" name="country">
                        <option> Choose...</option>
                        <option>Uzbekistan</option>
                        <option>Russia</option>
                        <option selected="">United Kingdom</option>
                        <option>India</option>
                        <option>Afganistan</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Create password</label>
                    <input class="form-control" type="password" id="reg_password" name="password">
                  </div>
                  <div class="form-group">
                    <button type="submit" id="register" class="btn btn-primary btn-block" name="register" value="1"> Register </button>
                  </div>
                  <p class="phpCallback text-danger text-center"><?php echo $error; ?></p>
                  <p id="loginSuccess" class="phpCallback text-success text-center"><?php echo $success; ?></p>
                </form>
              </article>
              <div class="border-top card-body text-center">Have an account? <a href="#" id="loginBtn">Log In</a></div>
            </div>
          </div>

          <div class="loginContainer store" style="display: none;">
            <div class="card">
              <header class="card-header">
                <h4 class="card-title text-center mb-1 mt-1">Login</h4>
              </header>
              <article class="card-body border-top">
                <form id="login-form" method="post" action="">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                      </div>
                      <input type="email" class="form-control" id="login_email" name="email">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                      </div>
                      <input type="password" class="form-control" id="login_password" name="password">
                    </div>
                  </div>
                  <button type="submit" id="login" class="btn btn-primary btn-block" name="login" value="1">Login</button>
                  <p class="phpCallback text-danger text-center"><?php echo $error; ?></p>
                  <p id="loginSuccess" class="phpCallback text-success text-center"><?php echo $success; ?></p>
                  </p>
                </form>
              </article>
              <div class="border-top card-body text-center">Need to register? <a href="#" id="registerBtn" style="color: #007bff">Register here</a></div>
            </div>
          </div>
      </div>
    </div>
  </div>


  <div class="storeContainer">
    <div class="row">
      <div class="productContainer">
        <!-- Category Filter -->
        <div id="filter" class="card">
          <article class="card-group-item">
            <header class="card-header">
              <h6 class="title">Brands</h6>
            </header>
            <div class="card-body filterBody">
              <form>
                <label class="form-check">
                  <input class="form-check-input" name="Scarpa" type="checkbox" value="">
                  <span class="form-check-label">
                    Scarpa
                  </span>
                </label>
                <label class="form-check">
                  <input class="form-check-input" name="La Sportiva" type="checkbox" value="">
                  <span class="form-check-label">
                    La Sportiva
                  </span>
                </label>
                <label class="form-check">
                  <input class="form-check-input" name="Five Ten" type="checkbox" value="">
                  <span class="form-check-label">
                    Five Ten
                  </span>
                </label>
                <label class="form-check">
                  <input class="form-check-input" name="Black Diamond" type="checkbox" value="">
                  <span class="form-check-label">
                    Black Diamond
                  </span>
                </label>
                <label class="form-check">
                  <input class="form-check-input" name="Tenaya" type="checkbox" value="">
                  <span class="form-check-label">
                    Tenaya
                  </span>
                </label>
                <label class="form-check">
                  <input class="form-check-input" name="Beastmaker" type="checkbox" value="">
                  <span class="form-check-label">
                    Beastmaker
                  </span>
                </label>
                <label class="form-check">
                  <input class="form-check-input" name="Friction Labs" type="checkbox" value="">
                  <span class="form-check-label">
                    Friction Labs
                  </span>
                </label>
                <label class="form-check">
                  <input class="form-check-input" name="GripMonkeys" type="checkbox" value="">
                  <span class="form-check-label">
                    GripMonkeys
                  </span>
                </label>
                <label class="form-check">
                  <input class="form-check-input" name="Metolius" type="checkbox" value="">
                  <span class="form-check-label">
                    Metolius
                  </span>
                </label>
                <label class="form-check">
                  <input class="form-check-input" name="Psychi" type="checkbox" value="">
                  <span class="form-check-label">
                    Psychi
                  </span>
                </label>
              </form>
            </div>
          </article>

          <article class="card-group-item">
            <header class="card-header">
              <h6 class="title">Category </h6>
            </header>
            <div class="card-body">
              <form id="categoryForm">
                <label class="form-check">
                  <input class="form-check-input" type="radio" name="category" value="all" checked>
                  <span class="form-check-label">
                    All products
                  </span>
                </label>
                <label class="form-check">
                  <input class="form-check-input" type="radio" name="category" value="shoes">
                  <span class="form-check-label">
                    Climbing Shoes
                  </span>
                </label>
                <label class="form-check">
                  <input class="form-check-input" type="radio" name="category" value="chalk">
                  <span class="form-check-label">
                    Chalk & Accessories
                  </span>
                </label>
                <label class="form-check">
                  <input class="form-check-input" type="radio" name="category" value="hangboards">
                  <span class="form-check-label">
                    Hangboards
                  </span>
                </label>
              </form>
            </div>
          </article>
          <button id="filterBtn" class="btn btn-success" type="submit"><span class="iconify" data-icon="bx-bx-search" data-inline="false"></span></button>
        </div>
        <!-- end of filter -->

        <!-- Product elements are appended here \/ -->
        <div class="element" id="product_0"></div>

      </div>


    </div>
  </div>
  <script src="js/main.js"></script>
  <script src="js/jquery.mycart.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/js/mdb.min.js"></script>


</body>

</html>