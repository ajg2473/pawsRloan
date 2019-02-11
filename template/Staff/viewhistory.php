
<div class="card-body">
    <div class="col-md-12">

        <div class = "card text-center">
            <div class="card-header">

                {% include "Shared/errors.php" %}
                <p class="card-text">View History</p>
                <table class="table table-striped">
                    <thread>
                        <tr>
                            <th scope="col">Borrower</th>
                            <th scope="col">Item Type</th>
                            <th scope="col">Item Name</th>
                            <th scope="col">Check Out Date</th>
                            <th scope="col">Due Date</th>
                            <th scope="col">Check In Date</th>
                        </tr>
                    </thread>
                    <tbody class="managerDiv">
                    {% for history in histories %}
                    <tr>
                        <td scope="row">{{history.borrower}}</td>
                        <td scope="row">{{history.categoryName}}</td>
                        <td scope="row">{{history.itemName}}</td>
                        <td scope="row">{{history.dateLoan}}</td>
                        <td scope="row">{{history.due}}</td>
                        <td scope="row">{{history.returned}}</td>
                    </tr>
                    {% endfor %}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{% block javascript %}
<script>
    history();

    function history()
    {
        let path = "{{url(getToken())}}/staff/";

    }

</script>
{% endblock %}

