<!-- Header  -->
<?php include('header.php'); include('config.php'); ?>

<div class="container mt-5">
  <h2 class="text-center mb-4">User Information</h2>
  <?php
  session_start();
  if (isset($_SESSION['success']) && $_SESSION['success']) {
    echo $_SESSION['success'];
    unset($_SESSION['success']);
  }



$sql = "SELECT * FROM `users`";
$result = $conn->query($sql);


  ?>
  
      <!-- Replace the following rows with actual data from your form submission -->
      <?php
      if($result->num_rows == 0) {
        echo"No result Found";
      }else{

     
      
      ?>
      <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Gender</th>
        <th>Skills</th>
        <th>Country</th>
        <th>Image</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php  
      $sl = 1;
      while($row = $result->fetch_assoc()){

      
      ?>
      <tr>
        <td><?=$sl++??null;?></td>
        <td><?=$row['name']?? null;?></td>
        <td><?=$row['email']?? null;?></td>
        <td><?=$row['phone']?? null;?></td>
        <td><?=$row['gender']?? null;?></td>
        <td><?=$row['skills']?? null;?></td>
        <td><?=$row['country']?? null;?></td>
        <td><img src="uploads/<?=$row['image']??null;?>" alt="User Image" style="max-width: 100px;"></td>
        <td>
          <a class="btn btn-primary btn-sm" href="./edit.php?id=<?=$row['id']??null;?>">Edit</a>
          <button type="button" class="btn btn-danger btn-sm">Delete</button>
        </td>
      </tr>
      <?php  }} ?>
      <!-- Add more rows as needed -->
    </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>