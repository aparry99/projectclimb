$(document).ready(function () {
  // usage of browser cookies to pass JS > PHP
  function createCookie(name, value, days) {
    var expires;
    if (days) {
      var date = new Date();
      date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
      expires = "; expires=" + date.toGMTString();
    } else {
      expires = "";
    }
    document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
  }

  var loggedIn = localStorage.getItem("loggedIn");
  //   console.log("loggedIn = " + loggedIn);

  $("#storeLoginBtn").on("click", function () {
    $(".storeContainer").toggle();
    $(".credentialsContainer").toggle();
    $(this).text(function (i, text) {
      return text === " Login" ? " Back to store" : " Login";
    });
  });

  $("#basket").on("click", function () {
    if ($("#basket").hasClass("disabled")) {
      console.log("has disabled");
      alert("You must be logged in to do that.");
    } else {
      console.log("does not have disabled");
    }
  });

  $("#storeLogoutBtn").on("click", function () {
    loggedIn = "false";
    localStorage.setItem("loggedIn", loggedIn);
    $("#logoutBtn").hide();
    $(".storeContainer").show();
    $(".credentialsContainer").hide();
  });

  // loggedIn = current user state
  if (loggedIn == "true") {
    $(".credentialsContainer").hide();
    $("#storeLoginBtn").hide();
	$(".storeContainer").fadeIn(1000);
	$("#basket").fadeIn(1000);
    $("#storeLogoutBtn").fadeIn(1000);
    console.log("1 hiding container because of formID");
  } else if (loggedIn == "false") {
    $("#basket").hide();
    $(".credentialsContainer").show();
    $("#storeLoginBtn").fadeIn(1000);
    $(".storeContainer").hide();
    $("#logoutBtn").hide();
    console.log("2 hiding container because of formID");
  }

  // formID = last form, or null on reload
  var formID = localStorage.getItem("formID");
  //   console.log("formID = " + formID);
  if (formID == "login-form") {
    $(".storeContainer").hide();
    $(".credentialsContainer").show();
    $(".loginContainer").show();
    $(".registerContainer").hide();
  } else if (formID == "registration-form") {
    $(".storeContainer").hide();
    $(".credentialsContainer").show();
    $(".loginContainer").hide();
    $(".registerContainer").show();
  }

  $("#loginBtn").on("click", function () {
    $(".registerContainer").hide();
    $(".loginContainer").fadeIn(1000);
  });

  $("#registerBtn").on("click", function () {
    $(".loginContainer").hide();
    $(".registerContainer").fadeIn(1000);
  });

  //   console.log(products);
  //   console.log(products.length);

  // loop over array for number of objects, append element with array information
  // this is initially pulled via PHP, then json_encoded
  for (i = 0; i < products.length; i++) {
    // total elements added
    var total_element = $(".element").length - 1;

    // last <div> with element class id
    var lastid = $(".element:last").attr("id");
    var split_id = lastid.split("_");
    var nextindex = Number(split_id[1]) + 1;

    var max = products.length;
    // check total elements
    if (total_element < max) {
      // create container after last element
      $(".element:last").after(
        "<div style='background-color: #1b1b1b; margin: 10px;' class='element' id='product_" +
          nextindex +
          "'>"
      );
    }

    let productAppend =
      "<div class='card storeCard' id='product_" +
      nextindex +
      "'</div>" +
      "<div class='view zoom overlay'>" +
      "<img id='productImage' class='img-fluid w-100' src='" +
      products[i].product_image +
      "' alt='Sample'>" +
      "<a href='#!'>" +
      "<div class='mask'>" +
      "<img id='productImage' class='img-fluid w-100' src='" +
      products[i].product_image +
      "'>" +
      "<div class='mask rgba-black-slight'></div>" +
      "</div>" +
      "</a>" +
      "</div>" +
      "<div class='card-body text-center'>" +
      "<h5 id='productTitle_" +
      nextindex +
      "'>" +
      products[i].product_name +
      "</h5>" +
      "<p id='productCategory_" +
      nextindex +
      "' class='small text-muted text-uppercase mb-2'>" +
      products[i].product_category +
      "</p>" +
      "<hr>" +
      "<h6 class='mb-3'>" +
      "<span id='productPrice_" +
      nextindex +
      "' class='text-grey'>£" +
      products[i].product_price +
      "</span>" +
      "</h6>" +
      "<button class='btn btn-primary my-cart-btn' data-id='" +
      nextindex +
      "' data-name='" +
      products[i].product_name +
      "' data-price='" +
      products[i].product_price +
      "' data-quantity='1' data-image='" +
      products[i].product_image +
      "'btn-sm mr-1 mb-2'>" +
      "<i class='fas fa-shopping-cart pr-2'></i>Add to cart" +
      "</button>" +
      "<button type='button' id='detailsBtn' class='btn btn-light btn-sm mr-1 mb-2' data-toggle='tooltip' data-animation='true' data-html='true' data-placement='top' title='" +
      products[i].product_description +
      "'>" +
      "<i class='fas fa-info-circle pr-2'></i>Hover for details" +
      "</button>" +
      "</div>" +
      "</div>";

    // append element
    $("#product_" + nextindex).append(productAppend);
  }

  $(function () {
    var goToCartIcon = function ($addTocartBtn) {
      var $cartIcon = $(".my-cart-icon");
      var $image = $(
        '<img width="45px" height="45px" src="' +
          $addTocartBtn.data("image") +
          '"/>'
      ).css({ position: "fixed", "z-index": "999" });
      $addTocartBtn.prepend($image);
      var position = $cartIcon.position();
      $image.animate(
        {
          top: position.top,
          left: position.left,
        },
        500,
        "linear",
        function () {
          $image.remove();
        }
      );
    };

    $(".my-cart-btn").myCart({
      classCartIcon: "my-cart-icon",
      classCartBadge: "my-cart-badge",
      affixCartIcon: true,
      checkoutCart: function (products, totalPrice, totalQuantity) {
        console.log("products = " + products);
        let cartData = JSON.stringify(products);
        let orders = JSON.stringify({
          user_id: 12,
          total: totalPrice,
          order_date: new Date(),
          timestamp: new Date().getMilliseconds(),
        });

        createCookie("order_details", cartData, 0);
        createCookie("orders", orders, 0);

        console.log("cartData = " + cartData);
        console.log("orders = " + orders);
        $.ajax({
          url: "includes/cart.php",
          type: "POST",
          data: {},
          cache: false,
        });
      },
      clickOnAddToCart: function ($addTocart) {
        goToCartIcon($addTocart);
      },
    });
  });

  $("#cart").on("click", function () {});

  $(".my-cart-btn").on("click", function (e) {
    let productName = $(this).closest(".card-body")[0].children;
    let productPrice = $(this).closest(".card-body")[0].children[3].children[0]
      .innerHTML;
    // console.log(productName);
  });

  function render(toRender) {
    for (i = 0; i < toRender.length; i++) {
      // total elements added
      var total_element = $(".element").length - 1;

      // last <div> with element class id
      var lastid = $(".element:last").attr("id");
      var split_id = lastid.split("_");
      var nextindex = Number(split_id[1]) + 1;

      var max = toRender.length;
      // check total elements
      if (total_element < max) {
        // create container after last element
        $(".element:last").after(
          "<div style='background-color: #1b1b1b; margin: 10px;' class='element' id='product_" +
            nextindex +
            "'>"
        );
      }

      let productAppend =
        "<div class='card storeCard' id='product_" +
        nextindex +
        "'</div>" +
        "<div class='view zoom overlay'>" +
        "<img id='productImage' class='img-fluid w-100' src='" +
        toRender[i].product_image +
        "' alt='Sample'>" +
        "<a href='#!'>" +
        "<div class='mask'>" +
        "<img id='productImage' class='img-fluid w-100' src='" +
        toRender[i].product_image +
        "'>" +
        "<div class='mask rgba-black-slight'></div>" +
        "</div>" +
        "</a>" +
        "</div>" +
        "<div class='card-body text-center'>" +
        "<h5 id='productTitle_" +
        nextindex +
        "'>" +
        toRender[i].product_name +
        "</h5>" +
        "<p id='productCategory_" +
        nextindex +
        "' class='small text-muted text-uppercase mb-2'>" +
        toRender[i].product_category +
        "</p>" +
        "<hr>" +
        "<h6 class='mb-3'>" +
        "<span id='productPrice_" +
        nextindex +
        "' class='text-grey'>£" +
        toRender[i].product_price +
        "</span>" +
        "</h6>" +
        "<button class='btn btn-primary my-cart-btn' data-id='" +
        nextindex +
        "' data-name='" +
        toRender[i].product_name +
        "' data-price='" +
        toRender[i].product_price +
        "' data-quantity='1' data-image='" +
        toRender[i].product_image +
        "'btn-sm mr-1 mb-2'>" +
        "<i class='fas fa-shopping-cart pr-2'></i>Add to cart" +
        "</button>" +
        "<button type='button' id='detailsBtn' class='btn btn-light btn-sm mr-1 mb-2' data-toggle='tooltip' data-animation='true' data-html='true' data-placement='top' title='" +
        toRender[i].product_description +
        "'>" +
        "<i class='fas fa-info-circle pr-2'></i>Hover for details" +
        "</button>" +
        "</div>" +
        "</div>";

      // append element
      $("#product_" + nextindex).append(productAppend);
    }
  }

  $(".form-check-input").on("click", function () {
    let checkedBrands = $(".filterBody").find("input:checkbox:checked");
    let brands = [];
    for (let i = 0; i < checkedBrands.length; i++) {
      brands.push(checkedBrands[i].name);
    }

    let checkedCategory = $(
      "input[name=category]:checked",
      "#categoryForm"
    ).val();

    const filteredProducts = products.filter((product) => {
      // two solutions to filtering product, essentially the same - just one is one-liner.
      // 1

      // if(brands.length > 0) {
      // 	if (brands.indexOf(product.product_brand) > -1) {
      // 		if(checkedCategory === "all" || checkedCategory === product.product_category) {
      // 			return true;
      // 		}
      // 	}
      // } else {
      // 	if(checkedCategory === "all" || checkedCategory === product.product_category) {
      // 		return true;
      // 	}
      // }

      // 2
      return brands.length > 0
        ? brands.indexOf(product.product_brand) > -1
          ? checkedCategory === "all" ||
            checkedCategory === product.product_category
            ? true
            : false
          : false
        : checkedCategory === "all" ||
          checkedCategory === product.product_category
        ? true
        : false;
    });

    $(".element").remove();
    $(".productContainer").append('<div class="element" id="product_0"></div>');
    render(filteredProducts);
  });
});
