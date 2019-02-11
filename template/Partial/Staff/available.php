<table class="table table-striped" xmlns="http://www.w3.org/1999/html">
    <thead>
        {% set break = false %}
        {% set pos = 0 %}
        {% for item, user in available if not break %}
            <tr>
                {% for key, val in user%}
                    {% if pos is divisible by(2) %}
                        <th>{{key}}</th>
                    {% endif %}
                    {% set pos = pos + 1 %}

                {% endfor %}
                <th>Checkout</th>
            </tr>

        {% set break = true %}
        {% endfor %}
    </thead>
    <tbody>
        {% for item, user in available %}
        {% set pos = 0 %}
        <tr>
            {% for key, val in user%}
            {% if pos is divisible by(2) %}
                <td>{{val}}</td>
                {% endif %}
                {% set pos = pos + 1 %}
            {% endfor %}
            <td>
                <form class="checkOutForm">
                    <input type="checkbox" value="{{user.itemId}}" name="items[]"/>

                    <input type="date" name="dues[]"/>
                </form>
            </td>

        </tr>

        {% endfor %}
    </tbody>
</table>