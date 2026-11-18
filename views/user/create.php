{{ include('layouts/header.php', {title: 'Nouvel utilisateur'}) }}
    <div class="container">
        <form method="post">
            <h2>Nouvel utilisateur</h2>
            <label>Nom
                <input type="text" name="nom" value="{{ user.nom }}">
            </label>
            {% if errors.nom is defined %}<span class="error">{{ errors.nom }}</span>{% endif %}
            <label>Courriel
                <input type="email" name="username" placeholder="utilisateur@courriel.com" value="{{ user.username }}">
            </label>
            {% if errors.username is defined %}<span class="error">{{ errors.username }}</span>{% endif %}
            <label>Mot de passe (8 caractères minimum)
                <input type="password" name="password">
            </label>
            {% if errors.password is defined %}<span class="error">{{ errors.password }}</span>{% endif %}
            <label>Privilège
                <select name="privilege_id">
                    <option value="">— Choisir un privilège —</option>
                    {% for p in privileges %}
                    <option value="{{ p.id }}" {% if p.id == user.privilege_id %}selected{% endif %}>{{ p.privilege }}</option>
                    {% endfor %}
                </select>
            </label>
            {% if errors.privilege_id is defined %}<span class="error">{{ errors.privilege_id }}</span>{% endif %}
            <input type="submit" class="btn" value="Enregistrer">
        </form>
    </div>
{{ include('layouts/footer.php') }}
