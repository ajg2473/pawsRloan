<div class="card-body">
    {% include "Shared/errors.php" %}
    <h5 class="card-title">Staff</h5>
    <p class="card-text">You can edit to add or remove staff here.</p>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Action</th>

        </tr>
        </thead>
        <tbody class="staffSection">
            {% include "Partial/Manager/staffList.php" %}
        </tbody>
    </table>

    {% if manager is defined %}
    <a href="#" class="btn btn-primary addstaff">Add Staff</a>
    {% endif %}
</div>
