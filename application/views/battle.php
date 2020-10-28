<?php
// if (  $_SESSION['user_is_logged'] == false  ) {
//      header("location:/main/index");
// }

$entry = null;
foreach ($votesGrafic as $key => $value) {
  $entry .= "['".$value->nickname."',".$value->votes."],";
}
      
$idStates = $battleSubs[0]->idStates;

foreach ($battles as $key => $value) {
    $title = $value->title;
    $rules = $value->rules;
    $createdDate = $value->createdDate;
    $samplesLink = $value->samplesLink;
    $tags = $value->tags;
    $maxVotes = $value->maxVotes;
    $idBattle = isset($value->id) ? $value->id : $value->idBattle;
}

?>

<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Nickname',  'Votes'],
      <?=$entry ?>
    ]);

    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    chart.draw(data, {backgroundColor:'#fff'});
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
      
      <div style="justify-content: center;display: flex; margin-bottom: 2%;">
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
     <?php if ($_SESSION["isAdmin"] == 1 ) { ?>
      <a href="<?=base_url("/main/battleList")?>"><button   class="returnBtn text-white py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">Return</button></a>
    <?php  } elseif(($_SESSION["isAdmin"]) == 0){ ?>
        <a href="<?=base_url("/main/index")?>"><button   class="returnBtn text-white py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">Return</button></a>
      <?php } ?>
  

    <div class="text-center mb-20">
      <p class="headline"><?=$createdDate?></p>
      <p class="headline">Max Votes <?=$maxVotes?> </p>
      <h1 class="sm:text-3xl text-2xl font-medium text-center title-font text-white mb-4 title"> <?=$title?> </h1>
      <p class="text-base leading-relaxed xl:w-2/4 lg:w-3/4 mx-auto subtitulo"><?=$rules;  ?></p>
  
        <div style="display: flex; justify-content: center;" class="">
         <a style="padding:1%;" href="<?=$samplesLink?>" target="_blank"><button class="login flex mx-auto mt-10 text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg samples">Samples</button></a> 

        <?php if ($_SESSION["isAdmin"] == 1 ) { ?>
             <a style="padding:1%;"><button id="addSub" class="login flex mx-auto mt-10 text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">Add Submissions</button></a>
        <?php } ?>
        </div>
    </div>

    <?php if ($idStates == 2)  { ?>
      <section >
        <div  id='chart_div'></div>
      </section>
    <?php } ?>

    <h1 style=" font-size: 40px;" class="sm:text-3xl text-2xl font-medium text-center title-font text-white mb-4 subtitulo">Entries</h1>
    <div class="flex flex-wrap lg:w-4/5 sm:mx-auto sm:mb-2 -mx-2">
      
      <?php
        $userVotesArray = [];
        foreach ($userVotes as $key => $value) {
          array_push($userVotesArray, $value->submissionsId);
        }

        if(count($battleSubs) >= 1){
          foreach ($battleSubs as $key => $value) {
        
          if (in_array($value->id, $userVotesArray)) { ?>
            <div class='p-2 sm:w-1/2 w-full'> 
              <div style='text-align: center;background-color: #4BB543;' class='bg-gray-800 rounded p-4 items-center'>

                <span style='font-size: 20px;' class='title-font  text-white'><?=$value->nickname?></span>
                <?=$value->soundcloudLink?>
    
            <a style="padding:1%;" href="<?=base_url('/main/changeVote?battleId='.$idBattle.'&email='.$_SESSION["email"].'&submissionsId='.$value->id)?>"><button  class="login flex mx-auto mt-2 text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">Remove my Vote</button></a>
        </div>
      </div>
      <?php } else { ?>
         <div class='p-2 sm:w-1/2 w-full'>
          <div style='text-align: center;' class='bg-gray-800 rounded p-4 items-center'>
            <span style='font-size: 20px;' class='title-font  text-white'><?=$value->nickname?></span>
            <?=$value->soundcloudLink?>
    
          <?php if ($value->idStates == 2) { ?>
            <a style='width: 58%;'><button style='margin-top: 3%;' class='login  ml-auto text-white bg-indigo-400 border-0 px-2 focus:outline-none hover:bg-indigo-600 rounded noVote'>Vote</button></a>
          <?php } elseif($value->idStates == 3){ ?>
            <a style='width: 58%;'><button style='margin-top: 3%;' class='login  ml-auto text-white bg-indigo-400 border-0 px-2 focus:outline-none hover:bg-indigo-600 rounded noVote'>Vote</button></a>
          <?php } else { ?>
            <a style='width: 58%;' href='<?=base_url("/main/vote?email=".$_SESSION["email"]."&idSubmission=".$value->id)?>'><button style='margin-top: 3%;'  class='login  ml-auto text-white bg-indigo-400 border-0 px-2 focus:outline-none hover:bg-indigo-600 rounded vote'>Vote</button></a>
          <?php }  ?>
       </div>
      </div>
      <?php } } } ?>
    </div>
  </div>
</section>