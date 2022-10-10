  <?php

    use app\core\form\Field;  ?>

  <div class="d-md-flex flex-column justify-content-center  align-items-center h-75  ">
      <h1 class="mt-5 bd-highlight p-2">Create an account:</h1>

      <?php $form = app\core\form\Form::begin("", "post", $model) ?>

      <div class="row">
          <div class="col">
              <?php echo $form->field("firstName")->setLabel("First Name") ?>
          </div>
          <div class="col">
              <?php echo $form->field("lastName")->setLabel("Last Name") ?>
          </div>
      </div>
      <?php echo $form->field("email")->setLabel("Email")->setFieldType(Field::EMAIL) ?>
      <?php echo $form->field("password")->setLabel("Password")->setFieldType(Field::PASSWORD) ?>
      <?php echo $form->field("confirmPassword")->setLabel("Confirm password")->setFieldType(Field::PASSWORD) ?>

      <button type="submit" class="btn btn-primary w-25">Register</button>
      <a href="/login" class="btn btn-secondary w-25">Login</a>

      <?php app\core\form\Form::end() ?>


  </div>