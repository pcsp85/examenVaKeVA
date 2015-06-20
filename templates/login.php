<?php if(!defined('examenTemplate')) die('Acceso negado'); ?>
<script>
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      testAPI();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Registrate/Ingresa ' +
        'a traves de Facebook.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '1432307407092953',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.2' // use version 2.2
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/es_MX/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI() {
    //console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      //console.log('Successful login for: ' + response.name);
      document.getElementById('status').innerHTML =
        'Bienvenido ' + response.name + '!';
        response.action = 'login';
        $.post('ajax.php', response, function (data){
        	data = JSON.parse(data);
        	if(data.result == 'success'){
        		location.reload(true);
        	}else if(data.result == 'error'){
        		document.getElementById('status').innerHTML += data.messages;
        	}
        });
    });
  }
</script>

<!--
  Below we include the Login Button social plugin. This button uses
  the JavaScript SDK to present a graphical Login button that triggers
  the FB.login() function when clicked.
-->
<div class="login text-center">
    <?php if(count($E->errors)>0): ?>
        <div class="alert alert-warning">
            <ul>
                <?php foreach($E->errors as $e): ?><li><?=$e;?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php if(count($E->messages)>0): ?>
        <div class="alert alert-success">
            <ul>
                <?php foreach($E->messages as $m): ?> <li><?=$m;?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

	<h2> <i class="fa fa-list-alt"></i> Examen Técnico</h2>
	<fb:login-button scope="public_profile,email" onlogin="checkLoginState();"> Iniciar Sesión
	</fb:login-button>

	<div id="status">
	</div>
</div>