<form class="ajax" method="post" action="../ajax.php">
        <h2>Форма регистрации</h2>
        
        <p>
            <input name="userlogin" type="text" placeholder="Логин" pattern="[А-Яа-яёЁA-Za-z0-9]{6,}" autofocus>
            <span data-field="userlogin"></span>
        </p>
        
        <p>
            <input name="password" type="password" placeholder="Пароль" pattern="(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[А-Яа-яёЁA-Za-z0-9!@#$%^&*]{6,}" >
            <span data-field="password"></span>
        </p>
        
        <p>
            <input name="password2" type="password" placeholder="Подтверждение пароля" pattern="(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[А-Яа-яёЁA-Za-z0-9!@#$%^&*]{6,}" >
            <span data-field="password2"></span>
        </p>
        
        <p>
            <input name="useremail" type="email" placeholder="Email" >
            <span data-field="useremail"></span>
        </p>
        
        <p>
            <input name="username" type="text" placeholder="Имя пользователя" pattern="[А-Яа-яёЁA-Za-z0-9]{2}" >
            <span data-field="username"></span>
        </p>
        
        <p>
            <input type="hidden" name="act" value="register">
            <button class="btn btn-large btn-primary" type="submit">Зарегистрироваться</button>
        </p>
        
        <p> 
            <a href="/">Форма авторизации</a>
        </p> 
      </form>