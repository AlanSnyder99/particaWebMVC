<section class="text-gray-500 bg-gray-900 body-font battleList">
  <div class="container px-5 py-24 mx-auto">
    <div class="flex flex-col text-center w-full mb-20">
    	    <p class="headline">Welcome <span style="color: #6441a5"><?=$_SESSION["username"]?></span></p>
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


    <?php if(count($battlesNoVotes) >= 1) {
        foreach ($battlesNoVotes as $battle) { ?>
          <tr>
            <td class='px-4 py-3'><?=$battle->title?></td>
            <td class='px-4 py-3'><?=$battle->createdDate?></td>
            <td class='px-4 py-3'>0</td>
            <td class='px-4 py-3 text-lg text-white'><?=$battle->state?></td>
          <?php if ($battles['idStates'] == 1 ){ ?>
              <td class='w-10 text-center'>
                <a href='<?=base_url("/main/joinBattle?id=".$battle->idBattle."&email=". $_SESSION["email"])?>'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'>Join</button><a>
              </td>
              <td class='w-10 text-center'>
                <a href='<?=base_url("/main/finishBattle?id=".$battle->idBattle)?>'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded stop'>Finish</button><a>
              </td>

          <?php } elseif ($battles['idStates'] == 2 ) { ?>
              <td class='w-10 text-center'>
                <a href='<?=base_url("/main/joinBattle?id=".$battle->idBattle."&email=". $_SESSION["email"])?>'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'>Join</button><a>
              </td>
                 <td class='w-10 text-center'>
                 </td>
          <?php } elseif($battles['idStates'] == 3){ ?>
                  <td class='w-10 text-center'>
                <a href='<?=base_url("/main/joinBattle?id=".$battle->idBattle."&email=". $_SESSION["email"])?>'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'>Join</button><a>
                 </td>
                   <td class='w-10 text-center'>
                <a href='<?=base_url("/main/publishBattle?id=".$battle->idBattle)?>'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded publish'>Publish</button><a>
                 </td>
          <?php } ?>
              <td class='w-10 text-center'>
                <a href='<?=base_url("/main/deleteBattle?id=".$battle->idBattle)?>'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded '>X</button><a>
                 </td></tr>
            <?php

        }
      }
    ?>


     <?php if(count($battles) >= 1){
          foreach ($battles as $battle) { ?>
      <tr>
        <td class='px-4 py-3 text-white'><?=$battle->title?></td>
        <td class='px-4 py-3 text-white'><?=$battle->createdDate?></td>
        <td class='px-4 py-3 text-white'><?=$battle->subCount?></td>
        <td class='px-4 py-3 text-lg text-white'><?=$battle->state?></td>
      <?php if ($battle->idStates == 1 ){ ?>
          <td class='w-10 text-center'>
            <a href='<?=base_url("/main/joinBattle?id=".$battle->idBattle."&email=". $_SESSION["email"])?>'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'>Join</button><a>
             </td>
               <td class='w-10 text-center'>
            <a href='<?=base_url("/main/finishBattle?id=".$battle->idBattle)?>'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded stop'>Finish</button><a>
             </td>
        <?php } elseif ($battle->idStates == 2 ) { ?>
           <td class='w-10 text-center'>
            <a href='<?=base_url("/main/joinBattle?id=".$battle->idBattle."&email=". $_SESSION["email"])?>'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'>Join</button><a>
             </td>
             <td class='w-10 text-center'>
           
             </td>
        <?php } elseif($battle->idStates == 3){ ?>
              <td class='w-10 text-center'>
            <a href='<?=base_url("/main/joinBattle?id=".$battle->idBattle."&email=". $_SESSION["email"])?>'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded join'>Join</button><a>
             </td>
               <td class='w-10 text-center'>
            <a href='<?=base_url("/main/publishBattle?id=".$battle->idBattle)?>'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded publish'>Publish</button><a>
             </td>
        <?php } ?>
         <td class='w-10 text-center'>
            <a href='<?=base_url("/main/deleteBattle?id=".$battle->idBattle)?>'> <button  class='login flex ml-auto text-white bg-indigo-300 border-0 px-2 focus:outline-none hover:bg-indigo-400 rounded '>X</button><a>
             </td></tr>
      <?php
    }
  }

?>


        </tbody>

      </table>

    </div>
   <div class="flex pl-4 mt-4 lg:w-2/3 w-full mx-auto">
      <a href="<?=base_url('/main/newBattle')?>" class="login flex ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">Create New Battle</a>
    </div>
  </div>
</section>