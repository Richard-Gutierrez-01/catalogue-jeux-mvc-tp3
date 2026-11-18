{{ include('layouts/header.php', {title: 'Connexion'}) }}
    <div class="container">
        <form method="post">
            <h2>Connexion</h2>
            {% if errors is defined %}
            <div class="error">
                <ul>
                    {% for error in errors %}
                    <li>{{ error }}</li>
                    {% endfor %}
                </ul>
            </div>
            {% endif %}
            <label>Courriel
                <input type="email" name="username" placeholder="votre@courriel.com" value="{{ user.username }}">
            </label>
            <label>Mot de passe
                <input type="password" name="password">
            </label>
            <input type="submit" class="btn" value="Se connecter">
            <p>Pas encore de compte ? <a href="{{ base }}/register">Inscription</a></p>
        </form>
    </div>
{{ include('layouts/footer.php') }}
