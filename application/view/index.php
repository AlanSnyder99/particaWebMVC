<?php include 'header.php'  ?>

<section class="text-gray-500 bg-gray-900 body-font beatTable">
  <div class="container px-5 py-24 mx-auto">
    <div class="flex flex-col text-center w-full mb-20">
      <h1 class="sm:text-4xl text-3xl font-medium title-font mb-2 text-white title">Beat Battles</h1>
      <p class="lg:w-2/3 mx-auto leading-relaxed text-base subtitulo">Checkout the active battles. Every tuesday we realize a beat battle competition on <a style="color: #6441a5" href="https://www.twitch.tv/partica" target="_blank">Twitch</a>, were every competitor have to make a beat in 30 minutes with samples dat us give to the people.</p>
    </div>
    <div class="lg:w-4/4 w-full mx-auto overflow-auto">
      
      <table class="table-auto w-full text-left whitespace-no-wrap">
        <thead class="thead">
          <tr>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-800 rounded-tl rounded-bl">Battle</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-800">Date</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-800">Entries</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-800">State</th>
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

           
            <td class='px-4 py-3 text-lg text-white'>".$battles['state']."</td>
           ";

           if (($_SESSION['user_is_logged']) == null) {
              echo "<td class='w-10 text-center'>
              <a href='/main/login'> <button onclick='location.href='/main/login'' class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'>Login to Join</button></a>
            </td>";
           } else {
            echo "<td class='w-10 text-center'>
               <a href='/main/joinBattle?id=".$battles['idBattle']."&email=". $_SESSION["email"]."'><button onclick='location.href='battle.php'' class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'>Join</button></a>
            </td>";
           }

  
          echo "</tr>";
        }
      } else {
        echo "<tr>
            <td class='px-4 py-3'></td>
            <td class='px-4 py-3'></td>
            <td class='px-4 py-3'></td>

           
            <td class='px-4 py-3 text-lg text-white'></td>
           ";

      
              echo "<td class='w-10 text-center'>
              <a href='/main/login'> <button onclick='location.href='/main/login'' class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'></button></a>
            </td>";
          

  
          echo "</tr>";
      }
    ?>

          <?php if(mysqli_num_rows($data) >= 1){
         while($battles = mysqli_fetch_assoc($data)){ 
          echo "<tr>
            <td class='px-4 py-3'>".$battles['title']."</td>
            <td class='px-4 py-3'>".$battles['createdDate']."</td>
            <td class='px-4 py-3'>".$battles['votesCount']."</td>

           
            <td class='px-4 py-3 text-lg text-white'>".$battles['state']."</td>
           ";

           if (($_SESSION['user_is_logged']) == null) {
              echo "<td class='w-10 text-center'>
              <a href='/main/login'> <button onclick='location.href='/main/login'' class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'>Login to Join</button></a>
            </td>";
           } else {
            echo "<td class='w-10 text-center'>
               <a href='/main/joinBattle?id=".$battles['idBattle']."&email=". $_SESSION["email"]."'><button onclick='location.href='battle.php'' class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'>Join</button></a>
            </td>";
           }

  
          echo "</tr>";
        }
      } else {
        echo "<tr>
            <td class='px-4 py-3'></td>
            <td class='px-4 py-3'></td>
            <td class='px-4 py-3'></td>

           
            <td class='px-4 py-3 text-lg text-white'></td>
           ";

      
              echo "<td class='w-10 text-center'>
              <a href='/main/login'> <button onclick='location.href='/main/login'' class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'></button></a>
            </td>";
          

  
          echo "</tr>";
      }
    ?>


         


        </tbody>

      </table>

    </div>
   <!-- <div class="flex pl-4 mt-4 lg:w-2/3 w-full mx-auto">
      <a style="color: white" class="text-indigo-500 inline-flex items-center md:mb-2 lg:mb-0">Learn More
        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
          <path d="M5 12h14M12 5l7 7-7 7"></path>
        </svg>
      </a>
      <button class="login flex ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">Button</button>
    </div>-->
  </div>
</section>

<?php include 'footer.php'  ?>