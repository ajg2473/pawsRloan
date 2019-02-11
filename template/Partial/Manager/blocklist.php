<div class="card-body">
    {% include "Shared/errors.php" %}
    <h5 class="card-title">Category</h5>
    <p class="card-text">This section allows you to manage all lending categories. You will be able to add, edit or delete a category
        from the lending system</p>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Date Added</th>
            <th scope="col">Action</th>

        </tr>
        </thead>
        <tbody>
            {% include "Partial/Manager/blocklistList.php" %}
        </tbody>
    </table>

    {% if manager is defined %}
    <a href="#" class="btn btn-primary addblocklist">Add user to blocklist</a>
    {% endif %}
</div>

<script>
    $('.addblocklist').click(function (e)
    {
        bootbox.prompt({
            title:"username",
            closeButton:false,
            callback:function(result)
            {
                //do not include null stuff
                if(result!==null)
                {
                    //do not allow empty
                    if(result.length<=0)
                    {
                        bootbox.alert("Username cannot be empty");
                        return false;
                    }

                    $.ajax({
                        url:path + "addBlocklist",
                        type:"POST",
                        data:"username="+result,
                        success : function(data)
                        {
                            let obj = JSON.parse(data);
                            if(obj.status===200)
                            {
                                $('.blocklistSection').html(obj.msg);
                            }
                            else {
                                bootbox.alert(obj.msg);
                            }
                        }

                    })
                }
            }
        })
    })
</script>