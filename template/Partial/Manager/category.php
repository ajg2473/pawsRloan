<div class="card-body">
    {% include "Shared/errors.php" %}
    <h5 class="card-title">Category</h5>
    <p class="card-text">This section allows you to manage all lending categories. You will be able to add, edit or delete a category
    from the lending system</p>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Name</th>

        </tr>
        </thead>
        <tbody>
        {% for category in categories %}
        <tr>
            <th scope="row">{{category.name}}</th>
        </tr>
        {% endfor %}
        </tbody>
    </table>

    {% if manager is defined %}
        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Add new Category</a>
    {% endif %}
</div>

<div class="fields" style="display:none">
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name[]">
    </div>
    <div class="form-group">
        <label for="sel1">Type:</label>
        <select class="form-control" id="sel1" name="type[]" required>
            <option value="integer">Integer</option>
            <option value="string">String</option>
        </select>
    </div>
    <div class="form-group">
        <label for="name">Length</label>
        <input type="number" class="form-control" id="name" value="0" name="length[]">
    </div>
    <div class="form-group">
        <div class="form-check">
            <label class="form-check-label">
                <input type="checkbox" class="form-check-input" value="" name="required[]">Required
            </label>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add a new lending category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label for="category">Category Name</label>
                    <input type="text" class="form-control" id="categoryName" name="category" required>
                </div>
                <form class="form-inline categoryForm">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name[]" required>
                    </div>
                    <div class="row attributes">
                        <div class="form-group m-1">
                            <label for="sel1">Type:</label>
                            <select class="form-control" id="sel1" name="type[]" required>
                                <option value="integer">Integer</option>
                                <option value="string">String</option>
                            </select>
                        </div>
                        <div class="form-group m-1">
                            <label for="name">Length</label>
                            <input type="number" class="form-control" value="0" name="length[]" required>
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" value="true" name="required[]">Required
                                </label>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary addField">Add More Field</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary addCategorySubmit">Save changes</button>
            </div>
        </div>
    </div>
</div>
