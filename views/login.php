<form class="ajax" method="post" action="../ajax.php">
        <h2>Форма авторизации</h2>
        
        <p>
            <input name="userlogin" type="text" placeholder="Логин" pattern="[А-Яа-яёЁA-Za-z0-9]{6,}" autofocus> 
            <span data-field="userlogin"></span>
        </p>
        
        <p>
            <input name="password" type="password" placeholder="Пароль" pattern="(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[А-Яа-яёЁA-Za-z0-9!@#$%^&*]{6,}"  >
            <span data-field="password"></span>
        </p>
        
        <p>
            <input type="hidden" name="act" value="login">
            <button type="submit">Войти</button> 
        </p>        
        
        <p> 
            <a href="/register.php">Форма регистрации</a>
        </p> 
      </form>