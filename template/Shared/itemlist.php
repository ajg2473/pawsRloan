<div class="card-body">
    {% include "Shared/errors.php" %}
    <h5 class="card-title">Inventory</h5>
    <table class="table table-striped">
        <thead>
            <tr>
                {% for header in inventoryListing.headers %}
                     <th scope="col">{{header}}</th>
                {% endfor %}
            </tr>
        </thead>
        <tbody>
            {% for content in inventoryListing.contents %}
                <tr>
                {% for item in content %}
                        <td scope="row">{{item}}</td>
                {% endfor %}
                </tr>
            {% endfor %}
        </tbody>
    </table>
</div>
