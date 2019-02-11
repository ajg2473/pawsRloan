<div class="card-body">
    {% include "Shared/errors.php" %}
    <h5 class="card-title">Fee</h5>
    <p>A manager may decide to add a fee to an item whenever the item is not meet a guideline requirements and
        may choose to edit for action needed.</p>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Cost</th>
            <th scope="col">Rate</th>

        </tr>
        </thead>
        <tbody class="feeSection">
            {% include "Partial/Manager/feeList.php" %}
        </tbody>
    </table>

    {% if manager is defined %}
    <a href="#" class="btn btn-primary addFee">Add | Edit Fee</a>
    {% endif %}
</div>
