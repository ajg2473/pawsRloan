{% extends "base.php" %}

{% block title %} Borrower {% endblock %}

{% block type %} Borrower {% endblock %}

{% block body %}
<div class="row">
    <div class="col-md-12">
        <div class="card text-center">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#history">History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#checkOutItems">Check Out Items</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#lateItems">Late Items</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#policy">Policy</a>
                    </li>
                </ul>
            </div>


            <div class="tab-content">


                <div class="tab-pane fade active show" id="history">
                    {% include "Borrower/history.php" %}
                </div>


                <div class="tab-pane fade" id="checkOutItems">
                    {% include "Borrower/checkOut.php" %}
                </div>


                <div class="tab-pane fade" id="lateItems">
                    {% include "Borrower/lateItem.php" %}
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
    <script>

        function decodeEntities(encodedString) {
            var textArea = document.createElement('textarea');
            textArea.innerHTML = encodedString;
            return textArea.value;
        }

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
    </script>

{% endblock %}