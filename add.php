<?php
require "db.php";

session_start();

if(!isset($_SESSION["user"])) {
  header("Location: index.php");
}

$errors = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (empty($_POST["name"]) || empty($_POST["phone_number"])) {
    $errors = "Please fill all fields";
  } else {

    $name = $_POST["name"];
    $phone_number = $_POST["phone_number"];

    $statement = $conn->prepare("INSERT INTO contacts (user_id, name, phone_number) VALUES ({$_SESSION['user']['id']},:name, :phone_number)");
    $statement->bindParam(":name", $name);
    $statement->bindParam(":phone_number", $phone_number);
    $statement->execute();

    $_SESSION["flash"] = ["message" => "Contact {$_POST['name']} added."];

    header("Location: home.php");
    return;
  }
}
// if($_SERVER["REQUEST_METHOD"] == "POST") {
//   $contact = [
//     "name" => $_POST["name"],
//     "phone_number" => $_POST["phone_number"]
//   ];

//   if(file_exists("contacts.json")) {
//     $contacts = json_decode(file_get_contents("contacts.json"), true);
//   } else {
//     $contacts = [];
//   }

//   $contacts[] = $contact;

//   file_put_contents("contacts.json", json_encode($contacts));
// };
?>
<?php require "./partials/header.php" ?>

<div class="container pt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Add New Contact</div>
        <div class="card-body">
          <form action="./add.php" method="POST">
            <div class="mb-3 row">
              <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>

              <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name" autocomplete="name" autofocus>
              </div>
            </div>

            <div class="mb-3 row">
              <label for="phone_number" class="col-md-4 col-form-label text-md-end">Phone Number</label>

              <div class="col-md-6">
                <input id="phone_number" type="tel" class="form-control" name="phone_number" autocomplete="phone_number" autofocus>
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