<pre>
<?php
require "db.php";

$errors = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if(empty($_POST["name"]) || empty($_POST["name"]) || empty($_POST["password"])) {
    $errors = "Please fill all fields.";
  } else {
    $statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $statement->bindParam(":email", $_POST["email"]);
    $statement->execute();

    if($statement->rowCount() > 0) {
      $errors = "This email exists";
    } else {
      $conn
        ->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)")
        ->execute([
          ":name" => $_POST["name"],
          ":email" => $_POST["email"],
          ":password" => password_hash($_POST["password"], PASSWORD_BCRYPT)
        ]);

        $statement = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $statement->bindParam(":email", $_POST["email"]);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        session_start();

        unset($user["password"]);

        $_SESSION["user"] = $user;

        header("Location: home.php");
    }
  }

  
}
?>
</pre>

<?php require "./partials/header.php" ?>

<div class="container pt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Register</div>
        <div class="card-body">
          <form action="./register.php" method="POST">
            <div class="mb-3 row">
              <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>

              <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name" autocomplete="name" autofocus>
              </div>
            </div>

            <div class="mb-3 row">
              <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>

              <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" autocomplete="email" autofocus>
              </div>
            </div>

            <div class="mb-3 row">
              <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>

              <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password" autocomplete="password" autofocus>
              </div>
            </div>

            <?php if ($errors != NULL) : ?>
              <p><?php echo $errors; ?></p>
            <?php endif; ?>

            <div class="mb-3 row">
              <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require "./partials/footer.php" ?>