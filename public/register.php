<? require_once("../resources/config.php");?>

<?php include(TEMPLATE_FRONT.DS."header.php") ?>

    <!-- Page Content -->
    <div class="container">

        <header>
                <h1 class="text-center">Register</h1>
                <hr>
                <h2 class="text-center bg-warning"><?php display_message(); ?></h2>
                <div class="col-sm-4 col-sm-offset-5">         
                    <form class="" action="" method="post" enctype="multipart/form-data">

                        <?php login_user(); ?>

                        <div class="form-group">
                         <label for="username">Username</label>
                         <input type="text" name="username" class="form-control" required>
                        </div>

                        <div class="form-group">
                         <label for="fullname">Full Name</label>
                         <input type="text" name="firstname" class="form-control" required>
                        </div>

                        <div class="form-group">
                         <label for="email">Email</label>
                         <input type="text" name="email" class="form-control"   required>
                        </div>

                        <div class="form-group">
                         <label for="password">Password</label>
                         <input type="password" name="password" class="form-control"  required>
                        </div>

                         <p>By creating an account you agree to our <a href="#" style="color:dodgerblue">Terms & Privacy</a>.</p>

                        <div class="form-group">
                         <!-- <a id="user-id" class="btn btn-danger" href="">Cancel</a> -->
                         <input type="submit" name="register_user" class="btn btn-primary" value="Sign Up" >
                        </div>
                    </form>
                </div>  
        </header>
    </div>

    </div>
    <!-- /.container -->

    <?php include(TEMPLATE_FRONT.DS."footer.php") ?>
