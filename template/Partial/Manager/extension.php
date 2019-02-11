<div class="card-body">
    {% include "Shared/errors.php" %}
    <h5 class="card-title">Extension</h5>

    <div class="mt-2 col-md-4">
        <form action="" class="search-form">
            <div class="form-group has-feedback">
                <label for="search" class="sr-only">Search</label>
                <input type="text" class="form-control" name="search" id="search" placeholder="username">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div>
        </form>
    </div>

    {% if manager is defined %}
    <a href="#" class="btn btn-primary">Add Extension</a>
    {% endif %}
</div>
