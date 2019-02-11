<div class="card-body">
    {% include "Shared/errors.php" %}
    <h5 class="card-title">Policy</h5>
    <p class="card-text">Library Policies are goals of providing access to information and protection of resources.</p>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Name</th>
           <!-- <th scope="col">Action</th>-->
            {% if hasRole("ROLE_MANAGER") %}
            <th scope="col">Action</th>
            {% endif %}

        </tr>
        </thead>
        <tbody>
        {% for policy in policies %}
        <tr>
            <td scope="row"><a href="#" class="loadcategoryPolicy" target="{{policy.categoryId}}" name="{{policy.name}}">{{policy.name}}</a></td>
            {% if hasRole("ROLE_MANAGER") %}
                <td><span class="btn btn-primary editPolicy" target="{{policy.categoryId}}" name="{{policy.name}}">Edit</span></td>
            {% endif %}
        </tr>
        {% endfor %}
        </tbody>
    </table>

    {% if manager is defined %}
    <a href="#" class="btn btn-primary" id="addPolicy">Add New Policy</a>
    {% endif %}
</div>


<textarea class="policyReader" style="display:none"   readonly></textarea>
