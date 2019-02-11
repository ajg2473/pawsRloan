
<div class="card-body">
    <div class="col-md-12">

        <div class = "card text-center">
            <div class="card-header">

                {% include "Shared/errors.php" %}
                <p class="card-text">Check In</p>
                <table class="table table-striped">
                    <thread>
                        <tr>
                            <th scope="col">Borrower Name</th>
                            <th scope="col">Category Name</th>
                            <th scope="col">Item Name</th>
                            <th scope="col">Check Out Date</th>
                            <th scope="col">Check In</th>
                        </tr>
                    </thread>
                    <tbody class="managerDiv">
                    {% for check in checkIn %}
                    <tr>
                        <th scope="row">{{check.itemType}}</th>
                    </tr>
                    {% endfor %}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>