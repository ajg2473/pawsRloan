{% extends "base.php" %}

{% block title %} Manager {% endblock %}

{% block type %} Manager {% endblock %}

{% block body %}
<style>
    .dialogWide > .modal-dialog {
        width: 80% !important;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card text-center">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab"  href="#category">Category</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#fee">Fee</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#inventory">Inventory</a>
                    </li>
                    <!--<li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#blocklist">Blocklist</a>
                    </li>-->
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#policy">Policy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#staff">Staff</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade active show" id="category">
                    {% include "Partial/Manager/category.php" %}
                </div>
                <div class="tab-pane fade" id="fee">
                    {% include "Partial/Manager/fee.php" %}
                </div>
                <div class="tab-pane fade" id="staff">
                    {% include "Partial/Manager/staff.php" %}
                </div>
                <div class="tab-pane fade" id="inventory">
                    {% include "Partial/Manager/inventory.php" %}
                </div>
                <div class="tab-pane fade" id="blocklist">
                    {% include "Partial/Manager/blocklist.php" %}
                </div>
                <div class="tab-pane fade" id="policy">
                    {% include "Partial/Manager/policy.php" %}
                </div>
                </div>
            </div>

        </div>
    </div>
</div>






{% endblock %}


{% block javascript %}
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>

<script>

    /**
     * add new policy
     * @type {string}
     */
    let path = "{{url(getToken())}}/manager/";
    manager();

    $('#addPolicy').click(function(e)
    {
        $.get( path+"categoryForm", function( data )
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
                            let category = $( "#selectCategory option:selected" ).val();
                            //promptInventoryForm(category);
                            promptSummerNote(category);
                        }
                    },
                    cancel:{
                        label: 'Cancel'
                    }
                }
            });
        });

    });

    /**
     * submit new catetory
     * @param category
     */
    function submitInventory(category)
    {
        $('.submitInventoryForm').submit(function(e)
        {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: path + "addInventory",
                data: $(this).serialize() + "&category=" + category,
                success: function (data)
                {
                    let obj = JSON.parse(data);

                    if (obj.status==200) {
                        location.reload();
                    } else {
                        bootbox.alert(obj.msg);
                    }
                }
            });

        });

        $('.submitInventoryForm').submit();
    }

    /**
     * insert policy
     * @param category
     * @param msg
     */
    function promptSummerNote(category, msg="")
    {
        bootbox.dialog({
            message:'<div id="summernote"></div>',
            title: "Add A new Policy",
            closeButton:false,
            size:"large",
            buttons: {
                ok: {
                    label: 'Save',
                    className: 'btn-primary',
                    callback: function()
                    {
                        let text = $('#summernote').summernote('code');
                        $.ajax({
                            url:path + "addPolicy",
                            type:"POST",
                            data:"category="+category+"&policy="+text,
                            success:function(data)
                            {
                                var obj = JSON.parse(data);
                                if(obj.status===201) {
                                    location.reload();
                                } else {
                                    bootbox.alert(obj.msg);
                                }

                            }

                        });
                    }
                },
                cancel:{
                    label: 'Cancel'
                }
            }


        });



        if (msg.length > 0) {

            $('#summernote').summernote('code', msg);
        } else
        {
            $('#summernote').summernote();
        }
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
    })

    /**
     * new inventory form
     * @param category
     */
    function promptInventoryForm(category)
    {


        $.ajax({
            type: 'POST',
            url: path + "inventoryForm",
            data:"category="+category,
            success: function(data) {
                let obj = JSON.parse(data);
                if (obj.status===200) {
                    bootbox.dialog({
                        message:obj.msg,
                        title: "Add a new Inventory",
                        closeButton:false,
                        className:"large",
                        buttons: {
                            ok: {
                                label: 'Save',
                                className: 'btn-primary',
                                callback: function()
                                {
                                    submitInventory(category);
                                }
                            },
                            cancel:{
                                label: 'Cancel'
                            }
                        }
                    });
                }

            }
        });
    }

    function decodeEntities(encodedString) {
        var textArea = document.createElement('textarea');
        textArea.innerHTML = encodedString;
        return textArea.value;
    }

    /**
     * display item policy
     */
    $('.loadcategoryPolicy').click(function(e)
    {
        e.preventDefault();
        let cat = $(this).attr('target');
        let name = $(this).attr('name');

        $.get("{{url(getToken())}}/policy/"+cat, function( data )
        {
            data = decodeEntities(data);
            bootbox.dialog({
                title: 'You are viewing the policy for '+name,
                message: data,
                size:"large",
                closeButton:false,
                buttons: {
                    cancel:{
                        label: 'Close'
                    }
                }
            });
        });
    });
    /**
     * edit existing policy
     */
    $('.editPolicy').click(function(e)
    {
        let cat = $(this).attr('target');

        $.get("{{url(getToken())}}/policy/"+cat, function( data )
        {
            data = decodeEntities(data);
            promptSummerNote(cat,data);
            //$('#summernote').summernote('code');
        });
    });
    /**
     * add new inventory
     */
    $('.addInventory').click(function (e)
    {
        $.get( path+"categoryForm", function( data )
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
                            let category = $( "#selectCategory option:selected" ).val();
                            promptInventoryForm(category);
                        }
                    },
                    cancel:{
                        label: 'Cancel'
                    }
                }
            });
        });


    });
    /**
     * delete staff
     */
    $('.deleteStaff').click(function (e)
    {
        let username = $(this).attr('target');

        $.ajax({
            url:path + "removeStaff",
            type:"POST",
            data:"username="+username,
            success : function(data)
            {
                let obj = JSON.parse(data);
                if(obj.status===200)
                {
                    location.reload();
                }
                else {
                    bootbox.alert(obj.msg);
                }
            }

        })
    });
    /**
     * add new staff
     */
    $('.addstaff').click(function(e)
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
                        bootbox.alert("username cannot be empty");
                        return false;
                    }

                    $.ajax({
                        url:path + "addStaff",
                        type:"POST",
                        data:"username="+result,
                        success : function(data)
                        {
                            let obj = JSON.parse(data);
                            if(obj.status===200)
                            {
                                location.reload();
                            }
                            else {
                                bootbox.alert(obj.msg);
                            }
                        }

                    })
                }
            }
        })
    });

    /**
     * add new field in category
     */
    function addField()
    {
        $('.addField').click(function (e)
        {
            e.preventDefault();
            $('.categoryForm').append($('.fields').html());
        })
    }

    /**
     * submit new fee
     */
    function submitFee()
    {
        $('.formFee').submit(function(e)
        {
            e.preventDefault();
            $.ajax({
                url:path + "addFee",
                type:"POST",
                data:$(this).serialize(),
                success : function(data)
                {
                    let obj = JSON.parse(data);
                    if(obj.status===200)
                    {
                        location.reload();
                    }
                    else {
                        bootbox.alert(obj.msg);
                    }
                }

            })
        })

        $('.formFee').submit();
    }

    /**
     * add new fee
     */
    function addFee()
    {
        $('.addFee').click(function(e)
        {
            $.ajax({
                type: 'POST',
                url: path + "loadfeeform",
                success: function(data) {
                    bootbox.dialog({
                        message: data,
                        title: "Add New Fee",
                        closeButton:false,
                        className:"large",
                        buttons: {
                            ok: {
                                label: 'Save',
                                className: 'btn-primary',
                                callback: function()
                                {
                                    submitFee();
                                }
                            },
                            cancel:{
                                label: 'Cancel'
                            }
                        }
                    });
                }
            });
        });
    }

    /**
     * manager functionality
     */
    function manager()
    {
        addFee();
        addField();
        addNewCategory();
    }

    function showError(error)
    {
        bootbox.alert(error);
    }

    /**
     * add new category
     */
    function addNewCategory()
    {
        $('.addCategorySubmit').click(function ()
        {
            $('.categoryForm').submit();
        });

        $('.categoryForm').submit(function(e)
        {
            //alert($(this).serialize());
            e.preventDefault();
            let url = path + "addCategory"
            $.ajax({
                url:url,
                type:"POST",
                data: $(this).serialize()+"&category="+$('#categoryName').val(),
                success:function(msg)
                {
                    var obj = JSON.parse(msg);
                    if(obj.status!==200) {
                        bootbox.alert(obj.msg);
                    }
                    else {
                        location.reload();
                    }

                    $('.modal-backdrop').remove();

                }

            });
        });

    }
</script>


{% endblock %}