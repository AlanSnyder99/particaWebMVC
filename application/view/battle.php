<?php


 if (  $_SESSION['user_is_logged'] == false  ) {
     header("location:/main/index");
    
}

include 'header.php'; 

      $entry = null;


      while ($row = mysqli_fetch_array($data4)) {
       $entry .= "['".$row{'nickname'}."',".$row{'votes'}."],";
     };

$rows2=mysqli_fetch_assoc($data5);
$idStates =  ($rows2['idStates']);

$rows=mysqli_fetch_assoc($data);
    $title = ($rows['title']);
    $rules = ($rows['rules']);
    $createdDate = ($rows['createdDate']);
    $samplesLink = ($rows['samplesLink']);
    $tags = ($rows['tags']);
    $maxVotes = ($rows['maxVotes']);
    $idBattle = ($rows['id']);

  ?>

    <script type="text/javascript">

      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);



      function drawChart() {
        var data = google.visualization.arrayToDataTable([
        ['Nickname',  'Votes'],
        <?php echo $entry ?>
      ]);

        var options = {
        backgroundColor:'#fff',
       
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }

        
    </script> 
  
  <div id="addModal" class="modal">
 <div id="loader" class="loader"></div>
  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <h1 style=" font-size: 40px;" class="sm:text-3xl text-2xl font-medium text-center title-font text-white mb-4 subtitulo">Add Submission</h1>
    
    <form name="add_sub" id="add_sub">
      
      <input name="idBattle" <?php echo "value=".$idBattle."";  ?> type="hidden">
      
      <div style="justify-content: center;
    display: flex; margin-bottom: 2%;">
      <button type="button" name="add" id="add" class="login flex  text-white bg-indigo-400 border-0 px-2 focus:outline-none hover:bg-indigo-600 rounded">Add More</button>
      </div>
      <div>
      <table class="" id="dynamic_field">
        <tr  id="row"> 
          <td><input class="bg-gray-800 rounded border border-gray-700 focus:outline-none focus:border-indigo-500 text-base text-white px-4 py-2 mb-4" name="nickname[]" placeholder="Artist Name" type="text"></td> 
           <td><input class="bg-gray-800 rounded border border-gray-700 focus:outline-none focus:border-indigo-500 text-base text-white px-4 py-2 mb-4" name="soundcloudLink[]" placeholder="SoundCloud Link" type="text"></td>
         </tr> 
      </table>
     </div> 
       <input type="button" name="submit" id="submit" class="login flex mx-auto mt-10 text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg samples" value="Submit" />     
    </form>

  </div>

</div>

 

<section class="beat-section text-gray-500 bg-gray-900 body-font">
  <div class="container px-5 py-24 mx-auto">
     <?php

     if (($_SESSION["isAdmin"]) == 1 ) {
       echo '<a href="/main/battleList"><button   class="returnBtn text-white py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">Return</button></a>';
     } elseif(($_SESSION["isAdmin"]) == 0){
      echo '<a href="/main/index"><button   class="returnBtn text-white py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">Return</button></a>';
     }

       ?>
  

    <div class="text-center mb-20">
      <p class="headline"><?php echo  $createdDate; echo " "; ?></p>
      <p class="headline">  <?php  echo  "Max Votes ".$maxVotes;  ?> </p>
      <h1 class="sm:text-3xl text-2xl font-medium text-center title-font text-white mb-4 title"> <?php echo  $title;  ?> </h1>
      <p class="text-base leading-relaxed xl:w-2/4 lg:w-3/4 mx-auto subtitulo"><?php echo  $rules;  ?></p>
  
        <div style="display: flex; justify-content: center;" class="">
                 <a style="padding:1%;" <?php echo  'href="'.$samplesLink.'"';  ?> target="_blank"><button class="login flex mx-auto mt-10 text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg samples">Samples</button></a> 

                    <?php

                   if (($_SESSION["isAdmin"]) == 1 ) {
                     echo '<a style="padding:1%;"><button id="addSub" class="login flex mx-auto mt-10 text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">Add Submissions</button></a>';
                   } elseif(($_SESSION["isAdmin"]) == 0){
                    
                   }



                     ?>

                    

                   
                <!--<button class="login flex mx-auto mt-10 text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">Enter</button>-->
        </div>
    
    </div>

<?php 

          if ($idStates == 2)  {
            echo "
                 <section >
                <div  id='chart_div'></div>
                </section>";
          } else {
            echo "";
          }
         
       

 ?>

    <h1 style=" font-size: 40px;" class="sm:text-3xl text-2xl font-medium text-center title-font text-white mb-4 subtitulo">Entries</h1>
    <div class="flex flex-wrap lg:w-4/5 sm:mx-auto sm:mb-2 -mx-2">
      
      <?php

      $userVotes = [];


       while($votesUser = mysqli_fetch_assoc($data6)){ 
        array_push($userVotes, $votesUser['submissionsId']);
        }

      if(mysqli_num_rows($data2) >= 1){
         while($submissions = mysqli_fetch_assoc($data2)){ 
        
          if (in_array($submissions['id'], $userVotes)) {
            
             echo "   <div class='p-2 sm:w-1/2 w-full'>
        <div style='text-align: center;     background-color: #4BB543;'; class='bg-gray-800 rounded p-4 h-full items-center'>

          <span style='font-size: 20px;'' class='title-font  text-white'>".$submissions['nickname']."</span>
          ";

           echo $submissions['soundcloudLink'];
    

          if ($submissions['idStates'] == 2) {
            echo " <a style='width: 58%;'><button style='margin-top: 3%;' class='login  ml-auto text-white bg-indigo-400 border-0 px-2 focus:outline-none hover:bg-indigo-600 rounded noVote'>Vote</button></a>";
          } elseif($submissions['idStates'] == 3){
             echo " <a style='width: 58%;'><button style='margin-top: 3%;' class='login  ml-auto text-white bg-indigo-400 border-0 px-2 focus:outline-none hover:bg-indigo-600 rounded noVote'>Vote</button></a>";
          } else {
            echo " <a style='width: 58%;' href='/main/vote?email=".$_SESSION["email"]."&idSubmission=".$submissions['id']."'><button style='margin-top: 3%;'  class='login  ml-auto text-white bg-indigo-400 border-0 px-2 focus:outline-none hover:bg-indigo-600 rounded vote'>Vote</button></a>";

                        if ($data3 == "false") {
                        echo '<a style="padding:1%;" href="/main/changeVote?battleId='.$idBattle.'&email='.$_SESSION["email"].'&submissionsId='.$submissions['id'].'"><button  class="login flex mx-auto mt-2 text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">Remove my Vote</button></a>';
                     } elseif ($data3 == "true") {
                       echo '<a style="padding:1%;"><button  style=" color: currentColor; cursor: not-allowed; opacity: 0.5;
                      text-decoration: none;" class="login flex mx-auto mt-2 text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">Remove my Vote</button></a>';
                     } 

          }
             
       echo" </div>
      </div>";

          } else{
         echo "   <div class='p-2 sm:w-1/2 w-full'>
          <div style='text-align: center;'; class='bg-gray-800 rounded p-4 h-full items-center'>

            <span style='font-size: 20px;'' class='title-font  text-white'>".$submissions['nickname']."</span>
            ";

             echo $submissions['soundcloudLink'];
    

          if ($submissions['idStates'] == 2) {
            echo " <a style='width: 58%;'><button style='margin-top: 3%;' class='login  ml-auto text-white bg-indigo-400 border-0 px-2 focus:outline-none hover:bg-indigo-600 rounded noVote'>Vote</button></a>";
          } elseif($submissions['idStates'] == 3){
             echo " <a style='width: 58%;'><button style='margin-top: 3%;' class='login  ml-auto text-white bg-indigo-400 border-0 px-2 focus:outline-none hover:bg-indigo-600 rounded noVote'>Vote</button></a>";
          } else {
            echo " <a style='width: 58%;' href='/main/vote?email=".$_SESSION["email"]."&idSubmission=".$submissions['id']."'><button style='margin-top: 3%;'  class='login  ml-auto text-white bg-indigo-400 border-0 px-2 focus:outline-none hover:bg-indigo-600 rounded vote'>Vote</button></a>";


          }
             
       echo" </div>
      </div>";

          }

  
         

         }
       }

       ?>
    

  
  


    </div>



  </div>

</section>



<?php include 'footer.php'  ?>