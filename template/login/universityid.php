{% extends "base.php" %}

{% block title %} Login {% endblock %}

{% block type %} Login {% endblock %}

{% block body %}
<div class="row offset-5">
    <div class="card card-container mt-lg-5">

        <form class="firstTime">
            <input type="password" id="inputID" size="25" class="form-control" name="university" placeholder="Please enter your University ID" required autofocus>
            <button class="btn btn-lg btn-primary btn-block btn-signin">Submit</button>
        </form>

    </div><!-- /card-container -->


</div><!-- /container -->

{% endblock %}

{% block javascript %}
<script>
    $('.firstTime').submit(function(e)
    {
        e.preventDefault();
        $.ajax({
            url:"{{url(getToken())}}/updateUniversityId",
            type:"POST",
            data:$(this).serialize(),
            success:function(data)
            {
                let obj = JSON.parse(data);
                if (obj.status===200) {
                    location.reload();
                } else {
                    bootbox.alert(obj.msg);
                }
            }
        })
    });

</script>
{% endblock %}