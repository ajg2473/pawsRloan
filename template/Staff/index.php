{% extends "base.php" %}

{% block title %} Staff {% endblock %}

{% block type %} Staff {% endblock %}

{% block body %}
<div class="row">
    <div class="col-md-12">
        <div class="card text-center">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link loanPrompt" href="#viewinventory">Loan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab"  href="#checkout">Check Out</a>
                    </li>
                    <!--<li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#checkin">Check In</a>
                    </li>-->
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#overdueitems">OverDue Items</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#viewinventory">View Inventory</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link historyClick" data-toggle="tab" href="#viewhistory">View History</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="checkout">
                    {% include "Staff/checkout.php" %}
                </div>
                <div class="tab-pane fade" id="checkin">
                    {% include "Staff/checkin.php" %}
                </div>
                <div class="tab-pane fade"  id="overdueitems">
                    {% include "Staff/overdueitems.php" %}
                </div>
                <div class="tab-pane fade" id="viewinventory">
                    {% include "Partial/Manager/inventory.php" %}
                </div>
                <div class="tab-pane fade" id="viewhistory">
                    {% include "Staff/viewhistory.php" %}
                </div>
            </div>
        </div>

    </div>
</div>

{% endblock %}

{% block javascript %}

<script>
    let path = "{{url(getToken())}}/staff/";
    let universityId = "";
    let category = "";

    swipe();

    function showCategory()
    {
        $.get("{{url(getToken())}}/manager/categoryForm", function( data )
        {
            bootbox.dialog({
                message: data,
                title: "Select a category",
                closeButton:false,
                buttons: {
                    ok: {
                        label: 'Continue',
                        className: 'btn-primary',
                        callback: function()
                        {
                            category = $( "#selectCategory option:selected" ).val();
                            showAvailable(category);
                        }
                    },
                    cancel:{
                        label: 'Cancel'
                    }
                }
            });
        });
    }

    function checkOutMassive(category)
    {
        $('.checkOutForm').submit(function(e)
        {
            e.preventDefault();
            $.ajax({
                url:path + "massiveCheckout",
                data:$(this).serialize() + "&category="+category +"&university="+universityId,
                type:"POST",
                success:function (data)
                {
                    let obj = JSON.parse(data);
                    if(obj.status===200) {
                        location.reload();
                    } else {
                        bootbox.alert(msg);
                    }
                }
            })
        });

        $('.checkOutForm').submit();
    }
    function showAvailable(category)
    {

        $.ajax({
            url:path + "available",
            data:"category="+category,
            type:"POST",
            success:function(data)
            {
                var obj = JSON.parse(data);

                if (obj.status===200)
                {
                    bootbox.dialog({
                        message: obj.msg,
                        size:"large",
                        title: "Select Item to loan",
                        closeButton:false,
                        buttons: {
                            ok: {
                                label: 'Checkout',
                                className: 'btn-primary',
                                callback: function()
                                {
                                    checkOutMassive(category);
                                }
                            },
                            cancel:{
                                label: 'Cancel'
                            }
                        }
                    });
                } else {
                    bootbox.alert(obj.msg);
                }
            }


        });
    }


    function swipe()
    {
        $(".loanPrompt").click(function(e)
        {
            bootbox.prompt(
                {
                    title: "Swipe student ID",
                    inputType:'password',
                    closeButton:false,
                    callback: function(result)
                    {
                        if(result!==null)
                        {
                            //not empty
                            if(result.length===0)
                            {
                                showError("ID swipe cannot be empty");
                                return false;
                            }
                            else
                            {
                                universityId = result;
                                $.ajax({
                                    url:path + 'swipe',
                                    type:"POST",
                                    data:"universityid="+result,
                                    success:function(data)
                                    {
                                        let obj = JSON.parse(data);
                                        if(obj.status===200)
                                        {
                                            showCategory();
                                        }
                                        else
                                        {
                                            showError(obj.msg,"swipe");
                                        }
                                    }
                                })
                            }
                        }
                    }

                }
            )
        });
    }




    $('.active').click(function (e)
    {
        $.get(path + "checkOut", function (data) {
            $('.checkoutSection').html(data)

        });
    });


    $('.historyClick').click(function (e)
    {
        $.get(path + "history", function (data) {
            $('.historySection').html(data)

        });
    });


    function showError(error,method)
    {
        bootbox.confirm({
            message: error,
            callback:function()
            {
                if(method==="swipe") {
                    $('.loanPrompt').click();
                }


            }
        });
    }

    $('.viewItem').click(function(e)
    {
        let cat = $(this).attr('target');
        let name = $(this).attr('name');
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url:"{{url(getToken())}}/inventory/list",
            data:"category="+cat,
            success: function(data) {
                bootbox.dialog({
                    message:data,
                    title: name+"'s Inventory",
                    closeButton:false,
                    className:"large",
                    buttons: {
                        cancel:{
                            label: 'Close'
                        }
                    }
                });
            }
        });
    });

    $('.returnItem').click(function(e)
    {
        let itemId = $(this).attr("target");
        $.ajax({
            url:path + "checkIn",
            type:"POST",
            data:"item="+itemId,
            success:function (data)
            {
                let obj = JSON.parse(data);
                if( obj.status===200 ) {
                    location.reload();
                }
                else{
                    bootbox.alert(obj.msg);
                }
            }


        });
    });

</script>

{% endblock %}

