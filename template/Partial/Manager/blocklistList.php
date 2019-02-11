{% for blocklist in blocklists %}
<tr>
    <th scope="row">{{blocklist.name}}</th>
    <td><span class="btn btn-primary deleteBlocklist" target="{{blocklist.username}}">Delete</span></td>
</tr>
{% endfor %}