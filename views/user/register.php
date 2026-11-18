{{ include('layouts/header.php', {title: 'Inscription'}) }}
    <div class="container">
        <form method="post">
            <h2>Inscription</h2>
            <label>Nom
                <input type="text" name="nom" value="{{ user.nom }}">
            </label>
            {% if errors.nom is defined %}<span class="error">{{ errors.nom }}</span>{% endif %}
            <label>Courriel
                <input type="email" name="username" placeholder="votre@courriel.com" value="{{ user.username }}">
            </label>
            {% if errors.username is defined %}<span class="error">{{ errors.username }}</span>{% endif %}
            <label>Mot de passe (8 caractères minimum)
                <input type="password" name="password">
            </label>
            {% if errors.password is defined %}<span class="error">{{ errors.password }}</span>{% endif %}
            <input type="submit" class="btn" value="Créer mon compte">
            <p>Déjà inscrit ? <a href="{{ base }}/login">Connexion</a></p>
        </form>
    </div>
{{ include('layouts/footer.php') }}
