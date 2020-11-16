        <form class="ajax" method="post" action="../ajax.php">
            <input type="hidden" name="act" value="logout">
            <div class="form-actions">
                Hello <?= $_SESSION["nameUser"]?> <button class="btn btn-large btn-primary" type="submit">Выйти</button>
            </div>
        </form>