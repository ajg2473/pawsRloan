{% for fee in fees %}
<tr>
    <th scope="row">{{fee.name}}</th>
    <th scope="row">{{fee.fee}}</th>
    <th scope="row">{{fee.rate}}</th>
</tr>
{% endfor %}