{% for staff in staffs %}
    <tr>
        <th scope="row">{{staff.name}}</th>
        <td><span class="btn btn-primary deleteStaff" target="{{staff.username}}">Delete</span></td>
    </tr>
{% endfor %}



