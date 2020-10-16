<?php

 if (($_SESSION["isAdmin"]) == 0 || $_SESSION['user_is_logged'] == false ) {
     header("location:/main/index");
}

 include 'header.php'; 

 $name = $_SESSION["username"];

 ?>


<section class="text-gray-500 bg-gray-900 body-font battleList">
  <div class="container px-5 py-24 mx-auto">
    <div class="flex flex-col text-center w-full mb-20">
    	    <p class="headline">Wellcome <span style="color: #6441a5"><?php echo $name;  ?></span></p>
      <h1 class="sm:text-4xl text-3xl font-medium title-font mb-2 text-white title">Battles Panel</h1>
      <p class="lg:w-2/3 mx-auto leading-relaxed text-base subtitulo">Here are all the opened and closed Battles.</p>
    </div>
    <div class="lg:w-4/4 w-full mx-auto overflow-auto">
      
      <table class="table-auto w-full text-left whitespace-no-wrap">
        <thead class="thead">
          <tr>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-800 rounded-tl rounded-bl">Battle</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-800">Date</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-800">Entries</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-800">State</th>
             <th class="w-10 title-font tracking-wider font-medium text-white text-sm bg-gray-800"></th>
            <th class="w-10 title-font tracking-wider font-medium text-white text-sm bg-gray-800 "></th>
            <th class="w-10 title-font tracking-wider font-medium text-white text-sm bg-gray-800 rounded-tr rounded-br"></th>
          </tr>
        </thead>
        
        <tbody class="tbody">


    <?php if(mysqli_num_rows($data2) >= 1){
         while($battles = mysqli_fetch_assoc($data2)){ 
          echo "<tr>
            <td class='px-4 py-3'>".$battles['title']."</td>
            <td class='px-4 py-3'>".$battles['createdDate']."</td>
            <td class='px-4 py-3'>0</td>
            <td class='px-4 py-3 text-lg text-white'>".$battles['state']."</td>";
            
            if (($battles['idStates']) == 1 ){
              echo "
                  <td class='w-10 text-center'>
                <a href='/main/joinBattle?id=".$battles['idBattle']."&email=". $_SESSION["email"]."'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'>Join</button><a>
                 </td>
                   <td class='w-10 text-center'>
                <a href='/main/finishBattle?id=".$battles['idBattle']."'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded stop'>Finish</button><a>
                 </td>

              ";
            } elseif (($battles['idStates']) == 2 ) {
               echo "  <td class='w-10 text-center'>
                <a href='/main/joinBattle?id=".$battles['idBattle']."&email=". $_SESSION["email"]."'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'>Join</button><a>
                 </td>
                 <td class='w-10 text-center'>
               
                 </td>
                 ";
            } elseif(($battles['idStates']) == 3){
               echo "
                  <td class='w-10 text-center'>
                <a href='/main/joinBattle?id=".$battles['idBattle']."&email=". $_SESSION["email"]."'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'>Join</button><a>
                 </td>
                   <td class='w-10 text-center'>
                <a href='/main/publishBattle?id=".$battles['idBattle']."'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded publish'>Publish</button><a>
                 </td>

              ";
            }
        echo "    <td class='w-10 text-center'>
                <a href='/main/deleteBattle?id=".$battles['idBattle']."'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded '>X</button><a>
                 </td>";
          echo "</tr>";

        }
      }
    ?>


         <?php if(mysqli_num_rows($data) >= 1){
         while($battles = mysqli_fetch_assoc($data)){ 
          echo "<tr>
            <td class='px-4 py-3 text-white'>".$battles['title']."</td>
            <td class='px-4 py-3 text-white'>".$battles['createdDate']."</td>
            <td class='px-4 py-3 text-white'>".$battles['subCount']."</td>
            <td class='px-4 py-3 text-lg text-white'>".$battles['state']."</td>";
            
            if (($battles['idStates']) == 1 ){
              echo "
                  <td class='w-10 text-center'>
                <a href='/main/joinBattle?id=".$battles['idBattle']."&email=". $_SESSION["email"]."'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'>Join</button><a>
                 </td>
                   <td class='w-10 text-center'>
                <a href='/main/finishBattle?id=".$battles['idBattle']."'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded stop'>Finish</button><a>
                 </td>

              ";
            } elseif (($battles['idStates']) == 2 ) {
               echo "  <td class='w-10 text-center'>
                <a href='/main/joinBattle?id=".$battles['idBattle']."&email=". $_SESSION["email"]."'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'>Join</button><a>
                 </td>
                 <td class='w-10 text-center'>
               
                 </td>
                 ";
            } elseif(($battles['idStates']) == 3){
               echo "
                  <td class='w-10 text-center'>
                <a href='/main/joinBattle?id=".$battles['idBattle']."&email=". $_SESSION["email"]."'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'>Join</button><a>
                 </td>
                   <td class='w-10 text-center'>
                <a href='/main/publishBattle?id=".$battles['idBattle']."'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded publish'>Publish</button><a>
                 </td>

              ";
            }

          echo "    <td class='w-10 text-center'>
                <a href='/main/deleteBattle?id=".$battles['idBattle']."'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded '>X</button><a>
                 </td>";
          echo "</tr>";
        }
      }
    ?>


        </tbody>

      </table>

    </div>
   <div class="flex pl-4 mt-4 lg:w-2/3 w-full mx-auto">
     <!-- <a style="color: white" class="text-indigo-500 inline-flex items-center md:mb-2 lg:mb-0">Learn More
        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
          <path d="M5 12h14M12 5l7 7-7 7"></path>
        </svg>
      </a>-->
      <button onclick="location.href='/main/newBattle'" class="login flex ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">Create New Battle</button>
    </div>
  </div>
</section>

<?php include 'footer.php'  ?>