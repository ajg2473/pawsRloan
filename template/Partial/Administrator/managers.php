
{% for manager in managers %}
<tr>
    <th scope="row">{{manager.name}}</th>
    <td><span class="btn btn-primary m-1 deleteManager" target="{{manager.username}}">Delete</span></td>
</tr>
{% endfor %}
