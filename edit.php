<?php
require "db.php";

session_start();

if(!isset($_SESSION["user"])) {
  header("Location: index.php");
}

$id = $_GET["id"];

$statement = $conn->prepare("SELECT * FROM contacts WHERE id = :id LIMIT 1");
$exe = $statement->execute([":id" => $id]);

if ($statement->rowCount() == 0) {
  http_response_code(404);
  echo ("HTTP 404 Not found");
  return;
}

$contact = $statement->fetch(PDO::FETCH_ASSOC);

if($contact["user_id"] !== $_SESSION["user"]["id"]) {
  http_response_code(403);
  echo("HTTP 403 UNATHORIZED");
  return;
}

$errors = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (empty($_POST["name"]) || empty($_POST["phone_number"])) {
    $errors = "Please fill all fields";
  } else {
    $name = $_POST["name"];
    $phone_number = $_POST["phone_number"];

    $statement = $conn->prepare("UPDATE contacts SET name = :name, phone_number = :phone_number WHERE id = :id");
    $statement->execute([
      ":id" => $id,
      ":name" => $name,
      ":phone_number" => $phone_number
    ]);
    
    $_SESSION["flash"] = ["message" => "Contact {$_POST['name']} has been modified."];

    header("Location: home.php");
    return;
  }
}
?>

<?php require "./partials/header.php" ?>

<div class="container pt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Add New Contact</div>
        <div class="card-body">
          <form action="./edit.php?id=<?= $contact["id"] ?>" method="POST">
            <div class="mb-3 row">
              <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>

              <div class="col-md-6">
                <input id="name" value="<?= $contact["name"] ?>" type="text" class="form-control" name="name" autocomplete="name" autofocus>
              </div>
            </div>

            <div class="mb-3 row">
              <label for="phone_number" class="col-md-4 col-form-label text-md-end">Phone Number</label>

              <div class="col-md-6">
                <input id="phone_number" value="<?= $contact["phone_number"] ?>" type="tel" class="form-control" name="phone_number" autocomplete="phone_number" autofocus>
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