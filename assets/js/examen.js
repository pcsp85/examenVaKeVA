/* Script par Examen Técnico */
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
        $.post(home + '/ajax.php', response, function (data){
        	data = JSON.parse(data);
        	if(data.result == 'success'){
        		if(isLogedin==0){
					isLogedin = 1;
					location.reload(true);
      			FB.api('/me/picture', function(response){
        				var img = document.createElement('img');
        				img.src = response.data.url;
        				var f = new FormData();
        				f.append('action','imageProfile');
        				f.append('image', img);
        				$.ajax({
        					url: home + '/ajax.php',
        					data: f,
        					cache: false,
        					contentType: false,
        					processData: false,
        					type: 'POST',
        					success: function (data){
        						console.log(data);
        					}
        				});
	        			     				
        			});
        		}
        	}else if(data.result == 'error'){
        		document.getElementById('status').innerHTML += data.messages;
        	}
        });
    });
  }

	$('a.logout').click(function (e){
		e.preventDefault();
		FB.logout(function (response){
			$.get(home + '/ajax.php',{action:'logout'}, function(data){
				location.reload(true);
			});
		})
	});

$(document).ready(function (){
	$('form[name="numbers"]').submit(function (e){
		e.preventDefault();
		var f = $(this), r = $(this).parent();
		r.find('.alert').detach();
		$.post(home + '/ajax.php', {action:'addNumber',number:f.find('input[name="number"]').val()}, function (data){
			data = JSON.parse(data);
			if(data.result=='success'){
				r.append('<div class="alert alert-success">'+data.message+'</div>');
				$.post(home + '/ajax.php',{action: 'getNumbers'}, function (data){
					$('table.cifras tbody').html($(data).find('tbody').html());
				});
			}else if(data.result=='error'){
				r.append('<div class="alert alert-warning">'+data.message+'</div>');
			}
		});
	});

	$('.cifras thead th i').click(function (){
		var params = {
			action: 'getNumbers',
			orderby: $(this).attr('data-orderby'),
			order: $(this).attr('data-order')
		};
		$.post(home + '/ajax.php',params, function (data){
			$('table.cifras tbody').html($(data).find('tbody').html());
		});
	});

});