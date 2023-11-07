<?php
require_once plugin_dir_path(__FILE__) . 'GetPets.php';
$getPets = new GetPets();
get_header(); 
?>

<div class="page-banner">
  <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg'); ?>);"></div>
  <div class="page-banner__content container container--narrow">
    <h1 class="page-banner__title">Pet Adoption</h1>
    <div class="page-banner__intro">
      <p>Providing forever homes one search at a time.</p>
    </div>
  </div>  
</div>

<div class="container container--narrow page-section">
  <h3 class="headline headline--small">Filter Your Results:</h3>
  <form>
    <div class="row">
      <input type="text" class="form-control" name="species" placeholder="Specie" value="<?= isset($_GET['species']) && !empty($_GET['species']) ? esc_attr($_GET['species']) : ''?>">
      <input type="number" class="form-control" name="minweight" placeholder="Min Weight" value="<?= isset($_GET['minweight']) && !empty($_GET['minweight']) ? esc_attr($_GET['minweight']) : ''?>">
      <input type="number" class="form-control" name="maxweight" placeholder="Max Weight" value="<?= isset($_GET['maxweight']) && !empty($_GET['maxweight']) ? esc_attr($_GET['maxweight']) : ''?>">
      <input type="number" class="form-control" name="minyear" placeholder="Min Birth Year" value="<?= isset($_GET['minyear']) && !empty($_GET['minyear']) ? esc_attr($_GET['minyear']) : ''?>">
      <input class="btn btn--blue" type="submit" value="Filter">
    </div>
    <div class="row">
      <input type="number" class="form-control" name="maxyear" placeholder="Max Birth Year" value="<?= isset($_GET['maxyear']) && !empty($_GET['maxyear']) ? esc_attr($_GET['maxyear']) : ''?>">
      <input type="text" class="form-control" name="favhobby" placeholder="Favorite Hobby" value="<?= isset($_GET['favhobby']) && !empty($_GET['favhobby']) ? esc_attr($_GET['favhobby']) : ''?>">
      <input type="text" class="form-control" name="favcolor" placeholder="Favorite Color" value="<?= isset($_GET['favcolor']) && !empty($_GET['favcolor']) ? esc_attr($_GET['favcolor']) : ''?>">
      <input type="text" class="form-control" name="favfood" placeholder="Favorite Food" value="<?= isset($_GET['favfood']) && !empty($_GET['favfood']) ? esc_attr($_GET['favfood']) : ''?>">
      <a class="btn btn--yellow" href="<?= esc_url(remove_query_arg(array_keys($_GET))) ?>">Reset</a>
    </div>
  </form>

  <p>This page took <strong><?php echo timer_stop();?></strong> seconds to prepare. Found <strong><?= number_format($getPets->count) ?></strong> results (showing the first <?= count($getPets->pets) ?>).</p>
  <table class="pet-adoption-table">
    <tr>
      <th>Name</th>
      <th>Species</th>
      <th>Weight</th>
      <th>Birth Year</th>
      <th>Hobby</th>
      <th>Favorite Color</th>
      <th>Favorite Food</th>
      <?php if(current_user_can('administrator')):?><th>Delete</th><?php endif ?>
    </tr>
    <?php foreach($getPets->pets as $pet): ?>
      <tr>
        <td><?= $pet->petname ?></td>
        <td><?= $pet->species ?></td>
        <td><?= $pet->petweight ?></td>
        <td><?= $pet->birthyear ?></td>
        <td><?= $pet->favhobby ?></td>
        <td><?= $pet->favcolor ?></td>
        <td><?= $pet->favfood ?></td>
        <?php if (current_user_can('administrator')): //show my option to delete a pet?>
          <td style="text-align: center;">
            <form action="<?= esc_url(admin_url('admin-post.php'))?>" method="POST">
              <input type="hidden" name="action" value="deletepet">
              <input type="hidden" name="idtodelete" value="<?= $pet->id ?>">
              <button class="delete-pet-button">X</button>
            </form>
          </td>
        <?php endif ?>
      </tr>
    <?php endforeach ?>
  </table>
  
  <?php 
    if(current_user_can('administrator')): //check if my user is admin to show the add animal form
    #Basically here, when we send it to 'admin-post.php', WP looks for a field with name='action' and uses the value of that field to create a hook, so we can use the hook using 'admin_post_{valueOfYourActionField}'
  ?>
    <form class="create-pet-form" action="<?= esc_url(admin_url('admin-post.php'))?>" method="POST">
      <p>Enter just the name for a pet. Other details with be randomly generated.</p>

      <input type="hidden" name="action" value="createpet"> <!-- WP looking exactly for this name   -->
      <input type="text" class="form-control" name="incomingpetname" placeholder="Name of your pet">
      <button class="btn btn--blue">Add Pet</button>
    </form>
  <?php endif ?>
</div>

<?php get_footer(); ?>