<div class="card-body">
    {% include "Shared/errors.php" %}
    <h5 class="card-title">Checked Out items</h5>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Borrower Name</th>
            <th scope="col">Category Name</th>
            <th scope="col">Item Name</th>
            <th scope="col">Check Out Date</th>
        </tr>
        </thead>

        <tbody class="checkedOutDiv">
        {% for check in checkout %}
        <tr>
            <th scope="row">{{check.borrower}}</th>
            <th scope="row">{{check.categoryName}}</th>
            <th scope="row">{{check.itemName}}</th>
            <th scope="row">{{check.dateLoan}}</th>
        </tr>
        {% endfor %}
        </tbody>

    </table>


</div>