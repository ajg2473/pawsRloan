<div class="row">
    <div class="col-4">
        <select id="selectCategory" data-show-content="true" class="form-control border">
            <option>Select..</option>
            {% for category in categories %}
                <option value="{{category.categoryId}}">{{category.name}}</option>
            {% endfor %}
        </select>
    </div>
</div>