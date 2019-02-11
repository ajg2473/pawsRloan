<form class="submitInventoryForm">
    {% for form in forms %}
        {% if form.needLength == true %}
            <div class="form-group">
                <label>{{form.name}}</label>
                <input type="{{form.type}}" class="form-control" name="{{form.name}}" maxlength="{{form.length}}">
            </div>
        {% else %}
            <div class="form-group">
                <label>{{form.name}}</label>
                <input type="{{form.type}}" class="form-control" name="{{form.name}}">
            </div>
        {% endif %}
    {% endfor %}
</form>
