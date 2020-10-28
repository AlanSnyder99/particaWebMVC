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
          <?php if(count($battlesNoVotes) >= 1){
            foreach ($battlesNoVotes as $battle) { ?>
              <tr>
                <td class='px-4 py-3'><?=$battle->title?></td>
                <td class='px-4 py-3'><?=$battle->createdDate?></td>
                <td class='px-4 py-3'>0</td>

               
                <td class='px-4 py-3 text-lg text-white'><?=$battle->state?></td>
              <?php if (($_SESSION['user_is_logged']) == null) { ?>
                  <td class='w-10 text-center'>
                  <a href='<?=base_url("/main/login")?>' class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'>Login to Join</a>
                </td>
              <?php  } else { ?>
                <td class='w-10 text-center'>
                   <a href='<?base_url("/main/joinBattle?id=".$battle->idBattle."&email=". $_SESSION["email"])?>'  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'>Join</a>
                </td>
              <?php } ?>
              </tr>
            <?php }
          } else { ?>
          <tr>
              <td class='px-4 py-3'></td>
              <td class='px-4 py-3'></td>
              <td class='px-4 py-3'></td>

             
              <td class='px-4 py-3 text-lg text-white'></td>


                <td class='w-10 text-center'>
                <a href='<?=base_url("/main/login")?>' class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'></a>
              </td></tr>
          <?php } 
          if(count($battles) >= 1){
              foreach ($battles as $battle) { ?>
                <tr>
              <td class='px-4 py-3'><?=$battle->title?></td>
              <td class='px-4 py-3'><?=$battle->createdDate?></td>
              <td class='px-4 py-3'><?=$battle->subCount?></td>

             
              <td class='px-4 py-3 text-lg text-white'><?=$battle->state?></td>
              <?php

             if (($_SESSION['user_is_logged']) == null) { ?>
                <td class='w-10 text-center'>
                <a href='<?=base_url("/main/login")?>' class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'>Login to Join</a>
              </td> <?php
             } else { ?>
              <td class='w-10 text-center'>
                 <a href='<?=base_url("/main/joinBattle?id=".$battle->idBattle."&email=". $_SESSION["email"])?>' class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'>Join</a>
              </td>
            <?php } ?>


            </tr>
          <?php }
          } else { ?>
          <tr>
              <td class='px-4 py-3'></td>
              <td class='px-4 py-3'></td>
              <td class='px-4 py-3'></td>

             
              <td class='px-4 py-3 text-lg text-white'></td>
              <td class='w-10 text-center'>
                <a href='<?=base_url("/main/login")?>' class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'></a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</section>