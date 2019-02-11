<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/tutor.css')}}">
    {% block additionalCSS %} {% endblock %}

    <!-- Optional JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

    <title> {% block title %}PAWS R LOAN{% endblock %} </title>

</head>

<body>
<div class="fixed-top d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow bg-rit">
    <h3 class="my-0 mr-md-auto font-weight-normal" style="color:white">PAWS R LOAN-{% block type %}{% endblock %}</h3>
    {% if not hasRole('anonymous') %}
    <a href="https://shibboleth.main.ad.rit.edu/logout.html"  class="btn btn-default logout" style="background-color:white !important; color:orange">Logout</a>
    <!--
    <a href="{{url(getToken()~'/staff/index')}}"  class="btn btn-default logout" style="background-color:white !important; color:orange">Staff</a>
    <a href="{{url(getToken()~'/administrator/index')}}"  class="btn btn-default logout" style="background-color:white !important; color:orange">Admin</a>
    <a href="{{url(getToken()~'/borrower/index')}}"  class="btn btn-default logout" style="background-color:white !important; color:orange">Borrower</a>
    <a href="{{url(getToken()~'/manager/index')}}"  class="btn btn-default logout" style="background-color:white !important; color:orange">Manager</a> -->
    {% endif %}
    {%block logout %} {%endblock %}
</div>
<br/>
<br/>
<br/>

<div class="container-fluid mt-2">
    <div class="row">
        <div class="modal fade" id="loading" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Please wait while we process your request</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!--<img class="img img-responsive" src="{{asset('images/loading.gif')}}"/>-->
                            <img src="{{asset('images/loading.gif')}}" width="150" height="150" class="rounded mx-auto d-block" alt="...">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    {%block body %} <h1> I am body body</h1> {% endblock %}

</div>

<footer></footer>

<!--<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="{{asset('js/bootstrap.js')}}"></script>
<script src="{{asset('js/bootbox.min.js')}}"></script>

<script>

    $('a[data-toggle="tab"]').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    $('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
        var id = $(e.target).attr("href");
        localStorage.setItem('selectedTab', id)
    });

    var selectedTab = localStorage.getItem('selectedTab');
    if (selectedTab != null) {
        $('a[data-toggle="tab"][href="' + selectedTab + '"]').tab('show');
    }

</script>
<!--
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
-->
{% block javascript %}{% endblock %}
</body>
</html>