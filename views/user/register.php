
<?php include ROOT.'/views/layouts/header.php'; ?>
<section>
    <div class="container">
        <div class="row">

            <div class="col-sm-4 col-sm-offset-4 padding-right">
                <ul>

                    <?php if (isset($errors) && is_array($errors)): ?>
                     <?php foreach ($errors as $error): ?>
                    <li>  <?php echo $error; ?></li>
                </ul>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="signup-form"><!--sign up form-->
                        <h2>Регистрация на сайте</h2>
                        <form action="#" method="post">
                            <input type="text" name="name" placeholder="Имя"" value='<?php echo $name; ?>'/>
                            <input type="email" name="email" placeholder="E-mail" value='<?php echo $email; ?>'"/>
                            <input type="password" name="password" placeholder="Пароль"  value='<?php echo $password; ?>'"/>
                            <input type="submit" name="submit" class="btn btn-default" value="Регистрация" />
                        </form>
                    </div><!--/sign up form-->

                <br/>
                <br/>
            </div>
        </div>
    </div>
</section>

<?php include ROOT.'/views/layouts/footer.php'; ?>
