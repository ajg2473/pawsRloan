<form class="form-inline formFee">

    <label for="sel1">Select a category</label>
    <select class="form-control" id="sel1" name="category">
        {% for category in categories %}
             <option value="{{category.categoryId}}">{{category.name}}</option>
        {% endfor %}
    </select>
    <label class="mr-sm-2">Cost</label>
    <input type="text" class="form-control mb-2 mr-sm-2" name="cost">

    <label for="sel1">Rate</label>
    <select class="form-control" id="sel1" name="rate">
        <option value="day">Per Day</option>
        <option value="hour">Per Hour</option>
    </select>
</form>