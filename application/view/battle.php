<?php include 'header.php';


 if (/*($_SESSION["isAdmin"]) == 0 ||*/ $_SESSION['user_is_logged'] == null ) {
     header("location:/main/index");
}


$rows=mysqli_fetch_assoc($data);
    $title = ($rows['title']);
    $rules = ($rows['rules']);
    $createdDate = ($rows['createdDate']);
    $samplesLink = ($rows['samplesLink']);
    $tags = ($rows['tags']);
    $idBattle = ($rows['id']);

  ?>
<div id="addModal" class="modal">

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
      <table id="dynamic_field">
        <tr  id="row"> 
          <td><input class="bg-gray-800 rounded border border-gray-700 focus:outline-none focus:border-indigo-500 text-base text-white px-4 py-2 mb-4" name="nickname[]" placeholder="Artist Name" type="text"></td> 
           <td><input class="bg-gray-800 rounded border border-gray-700 focus:outline-none focus:border-indigo-500 text-base text-white px-4 py-2 mb-4" name="soundcloudLink[]" placeholder="SoundCloud Link" type="text"></td>
         </tr> 
      </table>
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
      <p class="headline"><?php echo  $createdDate;  ?></p>
      <h1 class="sm:text-3xl text-2xl font-medium text-center title-font text-white mb-4 title"> <?php echo  $title;  ?> </h1>
      <p class="text-base leading-relaxed xl:w-2/4 lg:w-3/4 mx-auto subtitulo"><?php echo  $rules;  ?></p>
  
        <div class="">
                 <a <?php echo  'href="'.$samplesLink.'"';  ?> target="_blank"><button class="login flex mx-auto mt-10 text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg samples">Samples</button></a> 

                    <?php

                   if (($_SESSION["isAdmin"]) == 1 ) {
                     echo '<button id="addSub" class="login flex mx-auto mt-10 text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">Add Submissions</button>';
                   } elseif(($_SESSION["isAdmin"]) == 0){
                    
                   }

                     ?>

                   
                <!--<button class="login flex mx-auto mt-10 text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">Enter</button>-->
        </div>
    
    </div>
    <h1 style=" font-size: 40px;" class="sm:text-3xl text-2xl font-medium text-center title-font text-white mb-4 subtitulo">Entries</h1>
    <div class="flex flex-wrap lg:w-4/5 sm:mx-auto sm:mb-2 -mx-2">
      
      <?php 

      if(mysqli_num_rows($data2) >= 1){
         while($submissions = mysqli_fetch_assoc($data2)){ 
         echo "   <div class='p-2 sm:w-1/2 w-full'>
        <div class='bg-gray-800 rounded flex p-4 h-full items-center'>
          <svg fill='none' stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' class='text-indigo-500 w-6 h-6 flex-shrink-0 mr-4 icon' viewBox='0 0 24 24'>
            <path d='M22 11.08V12a10 10 0 11-5.93-9.14'></path>
            <path d='M22 4L12 14.01l-3-3'></path>
          </svg>
          <span style='font-size: 20px;'' class='title-font  text-white'>".$submissions['nickname']."</span>
          
          <a style='margin-left: 2%;'' href='".$submissions['soundcloudLink']."' target='_blank'><button class='login flex ml-auto text-white bg-indigo-400 border-0 px-2 focus:outline-none hover:bg-indigo-600 rounded listen'>Listen Beat</button></a>";

          if ($submissions['idStates'] == 2) {
            echo " <a style='width: 58%;'><button  class='login flex ml-auto text-white bg-indigo-400 border-0 px-2 focus:outline-none hover:bg-indigo-600 rounded noVote'>Vote</button></a>";
          } elseif($submissions['idStates'] == 3){
             echo " <a style='width: 58%;'><button  class='login flex ml-auto text-white bg-indigo-400 border-0 px-2 focus:outline-none hover:bg-indigo-600 rounded noVote'>Vote</button></a>";
          } else {
            echo " <a style='width: 58%;' href='/main/vote/?email=".$_SESSION["email"]."&idSubmission=".$submissions['id']."'><button  class='login flex ml-auto text-white bg-indigo-400 border-0 px-2 focus:outline-none hover:bg-indigo-600 rounded vote'>Vote</button></a>";
          }
             
       echo" </div>
      </div>";
         }
       }

       ?>
    

  
  


    </div>

  </div>
</section>

<?php include 'footer.php'  ?>