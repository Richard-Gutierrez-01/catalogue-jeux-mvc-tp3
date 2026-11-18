{{ include('layouts/header.php', {title: 'Jeux'}) }}
    <h1>Jeux</h1>
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Nom</th>
                <th>Genre</th>
                <th>Note</th>
                <th>Date de sortie</th>
                <th>Développeur</th>
                <th>Voir</th>
            </tr>
        </thead>
        <tbody>
            {% for jeu in jeux %}
            <tr>
                <td>{% if jeu.image %}<img src="{{ asset }}/uploads/{{ jeu.image }}" alt="" class="jeu-miniature">{% else %}—{% endif %}</td>
                <td><a href="{{ base }}/jeu/show?id={{ jeu.id }}">{{ jeu.nom }}</a></td>
                <td>{{ jeu.genre }}</td>
                <td>{{ jeu.note }}</td>
                <td>{{ jeu.date_sortie }}</td>
                <td>{{ jeu.developpeur_nom ?? '—' }}</td>
                <td><a href="{{ base }}/jeu/show?id={{ jeu.id }}" class="btn">Voir</a></td>
            </tr>
            {% endfor %}
        </tbody>
    </table>
    {% if session.user_privilege == 1 and not guest %}
    <a href="{{ base }}/jeu/create" class="btn table-button">Nouveau jeu</a>
    {% endif %}
{{ include('layouts/footer.php') }}
