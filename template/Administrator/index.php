{% extends "base.php" %}

{% block title %} Administrator {% endblock %}

{% block type %} Administrator {% endblock %}

{% block body %}
<div class="card-body">
    <div class="col-md-12">

    <div class = "card text-center">
        <div class="card-header">

        {% include "Shared/errors.php" %}
    <h1 class="card-title">Administrator</h1>
    <p class="card-text">Add/Remove Managers</p>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Action</th>

        </tr>
        </thead>
        <tbody class="managerDiv">
        {% include "Partial/Administrator/managers.php" %}
        </tbody>
    </table>

    {% if administrator is defined %}
    <a href="#" class="btn btn-primary addManager">Add new Manager</a>
    {% endif %}
        </div>
    </div>
    </div>

</div>



{% endblock %}

{% block javascript %}
    <script>

        administrator();

        /**
         * administrator functionality
         */
        function administrator()
        {
            function showError(error)
            {
                bootbox.alert(error);
            }
            let path = "{{url(getToken())}}/administrator/";
            $('.addManager').click(function(e)
            {
                bootbox.prompt({
                    title: "username",
                    inputType:"text",
                    closeButton:false,
                    callback: function(result)
                    {
                        if(result!==null)
                        {
                            if(result.length ===0 )
                            {
                                bootbox.alert("Username cannot be empty");
                                return false;
                            }

                            $.ajax({
                                url:path + "add",
                                type:"POST",
                                data:"username=" + result ,
                                success: function(res)
                                {
                                    let obj = JSON.parse(res);

                                    if(obj.status===200)
                                    {
                                        location.reload();
                                    } else {
                                        showError(obj.msg);
                                    }
                                }

                            });
                        }
                    }

                });
            });
            /**
             * delete manager from table
             */
            $('.deleteManager').click(function(e)
            {
                let username = $(this).attr('target');
                //alert(username);
                $.ajax({
                    url:path + "del",
                    type:"POST",
                    data:"username=" + username,
                    success:function(res1) {
                        let delObj = JSON.parse(res1);

                        if(delObj.status ===200)
                        {
                            location.reload();
                        } else {
                            showError(delObj.msg);
                        }
                    }


                });
            });
        }

    </script>


{% endblock %}

