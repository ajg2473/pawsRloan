<div class="card-body">
    {% include "Shared/errors.php" %}
    <h5 class="card-title">Inventory</h5>
    <p>Manager is able to see what is available in the inventory and items that has been checked out.</p>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Item Category</th>
            <th scope="col"># items checked out</th>
            <th scope="col"># items checked in</th>
            <th scope="col">Total</th>

        </tr>
        </thead>
        <tbody>
            {% for stat in stats %}
                <tr>
                    <td scope="row"><a href="#" class="viewItem" target="{{stat.categoryId}}" name="{{stat.name}}">{{stat.name}}</a></td>
                    <td scope="row">{{stat.checkOut}}</td>
                    <td scope="row">{{(stat.total-stat.checkOut)}}</td>
                    <td scope="row">{{stat.total}}</td>
                </tr>


            {% endfor %}
        </tbody>
    </table>

    {% if manager is defined %}
    <a href="#" class="btn btn-primary addInventory">Add Inventory</a>
    {% endif %}
</div>
