{{ include('layouts/header.php', {title: 'Développeurs'}) }}
    <h1>Développeurs</h1>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Pays d'origine</th>
                <th>Date de fondation</th>
                <th>Voir</th>
            </tr>
        </thead>
        <tbody>
            {% for d in developpeurs %}
            <tr>
                <td><a href="{{ base }}/developpeur/show?id={{ d.id }}">{{ d.nom }}</a></td>
                <td>{{ d.pays_origine }}</td>
                <td>{{ d.date_fondation }}</td>
                <td><a href="{{ base }}/developpeur/show?id={{ d.id }}" class="btn">Voir</a></td>
            </tr>
            {% endfor %}
        </tbody>
    </table>
    {% if session.user_privilege == 1 and not guest %}
    <a href="{{ base }}/developpeur/create" class="btn table-button">Nouveau développeur</a>
    {% endif %}
{{ include('layouts/footer.php') }}
