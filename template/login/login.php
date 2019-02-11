{% extends "base.php" %}

{% block title %} Login {% endblock %}

{% block type %} Login {% endblock %}

{% block body %}
<div class="row offset-6">
    <div class="card card-container">
        <!-- <img class="profile-img-card" src="//lh3.googleusercontent.com/-6V8xOA6M7BA/AAAAAAAAAAI/AAAAAAAAAAA/rzlHcD0KYwo/photo.jpg?sz=120" alt="" /> -->
        <img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" />
        <p id="profile-name" class="profile-name-card"></p>
        <form class="form-signin">
            <span id="reauth-email" class="reauth-email"></span>
            <input type="text" id="inputEmail" class="form-control" name="username" placeholder="username" required autofocus>
            <input type="password" id="inputPassword" class="form-control" name="password" placeholder="Password" required>
            <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Sign in</button>
        </form><!-- /form -->
        <p></p>
        <form class="form-signin">
            <span id="reauth-email" class="reauth-email"></span>
            <input type="text" id="inputEmail" size="25" class="form-control" name="univID" placeholder="Please enter your University ID" required autofocus>
            <p></p>
            <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Submit</button>
        </form>
        <!--<a href="#" class="forgot-password">
            Forgot the password?
        </a>-->
    </div><!-- /card-container -->


</div><!-- /container -->

{% endblock %}

{% block javascript %}

<script>
    $('.form-signin').submit(function(e)
    {
        e.preventDefault();
        $.ajax({
            url:"{{url('signin')}}",
            data:$(this).serialize(),
            type:"POST",
            success:function(data)
            {
                let obj = JSON.parse(data);

                if(obj.status===200)
                {
                    if (obj.msg.length===0) {
                        bootbox.alert("No role has been assigned to your account yet. Contact your administrator");
                    } else {
                        bootbox.dialog({
                            message: obj.msg,
                            size:"large",
                            title: "Select a link",
                            closeButton:false,
                            buttons: {
                            }
                        });
                    }

                } else {
                    bootbox.alert(obj.msg);
                }
            }
        })
    });
</script>

{% endblock %}

