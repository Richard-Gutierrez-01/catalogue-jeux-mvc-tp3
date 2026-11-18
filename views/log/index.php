{{ include('layouts/header.php', {title: 'Journal de bord'}) }}
    <h1>Journal de bord</h1>
    <table>
        <thead>
            <tr>
                <th>Date et heure</th>
                <th>Adresse IP</th>
                <th>Utilisateur</th>
                <th>Page</th>
            </tr>
        </thead>
        <tbody>
            {% for l in logs %}
            <tr>
                <td>{{ l.date_visite }}</td>
                <td>{{ l.ip }}</td>
                <td>{{ l.utilisateur }}</td>
                <td>{{ l.page }}</td>
            </tr>
            {% endfor %}
        </tbody>
    </table>
{{ include('layouts/footer.php') }}
