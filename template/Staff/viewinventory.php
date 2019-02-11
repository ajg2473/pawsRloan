<div class="card-body">
    <div class="col-md-12">

        <div class = "card text-center">
            <div class="card-header">

                {% include "Shared/errors.php" %}
                <p class="card-text">View Inventory</p>
                <table class="table table-striped">
                    <thread>
                        <tr>
                            <th scope="col">Item Type</th>
                            <th scope="col">Item Name</th>
                            <th scope="col">Available</th>
                            <th scope="col">Check Out Date</th>
                        </tr>
                    </thread>
                    <tbody class="managerDiv">
                    {% for over in overdue %}
                    <tr>
                        <td scope="col">{{over.itemType}}</td>
                        <td scope="col">{{over.itemName}}</td>
                        <td scope="col">{{over.countDate}}</td>
                    </tr>
                    {% endfor %}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>