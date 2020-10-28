<!DOCTYPE html>
<html>
  <head>
    <title>ParticaBB</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.4.6/tailwind.min.css" rel="stylesheet"/>
    <?=add_style('style')?>
  </head>
  <body>
    <header class="text-gray-500 bg-gray-900 body-font">
      <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
        <a href="https://particaartistgroup.com/" target="_blank"  class="flex title-font font-medium items-center text-white mb-4 md:mb-0 logo">
        <img class="logo" src="<?=img_url('Partica-Logo-Blanco01.png')?>">

        </a>
        <nav class="md:ml-auto flex flex-wrap items-center text-base justify-center">
           <a href="https://particaartistgroup.com/store/" target="_blank" class="mr-5 hover:text-gray">Shop</a>
          <a href="https://www.twitch.tv/partica" target="_blank" class="mr-5 hover:text-gray twitch">Twitch</a>
           <a href="https://soundcloud.com/particaartistgroup" target="_blank" class="mr-5 hover:text-gray soundcloud">Soundcloud</a>
          <!--<a  href="" target="_blank" class="mr-5 hover:text-gray">Patreon</a>-->
        </nav>
         
        <? if ($_SESSION['user_is_logged'] == false ) { ?>
          <a href="<?=base_url('main/login')?>" id='myBtn'><button class='inline-flex items-center bg-gray-800 border-0 py-1 px-3 focus:outline-none hover:bg-gray-700 rounded text-base mt-4 md:mt-0 login'>Login</button></a>
        <? } else { ?>
          <a href=<?=base_url('main/logout')?> id='myBtn'><button class='inline-flex items-center bg-gray-800 border-0 py-1 px-3 focus:outline-none hover:bg-gray-700 rounded text-base mt-4 md:mt-0 login'>Logout</button></a>
        <? } ?>
      </div>
    </header>

    <?php echo $content_for_layout;?>

    <footer id="footer" class="text-gray-500 bg-gray-900 body-font">
      <div class="container px-5 py-8 mx-auto flex items-center sm:flex-row flex-col">
        <a href="https://www.linkedin.com/in/alansnyder1999/" target="_blank" class="dev flex title-font font-medium items-center md:justify-start justify-center text-white">
                <span class="ml-2 text-xl">Developed by </span>
         <img class="footerLogo" src="<?=img_url('footerLogo.png')?>">
        </a>
        <p class="text-sm text-gray-600 sm:ml-4 sm:pl-4 sm:border-l-2 sm:border-gray-800 sm:py-2 sm:mt-0 mt-4">© 2020 Partica —
          <a href="https://twitter.com/knyttneve" class="text-gray-500 ml-1" target="_blank" rel="noopener noreferrer"></a>
        </p>

        <span class="inline-flex sm:ml-auto sm:mt-0 mt-4 justify-center sm:justify-start">
          <a class="text-gray-600">
            <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
              <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
            </svg>
          </a>
          <a class="ml-3 text-gray-600">
            <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
              <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path>
            </svg>
          </a>
          <a class="ml-3 text-gray-600">
            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
              <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
              <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"></path>
            </svg>
          </a>

        </span>
      </div>
    </footer>

    <?=add_jscript('jquery-3.2.1.min')?>
    <script type="text/javascript">
     $(document).ready(function(){  
        var modal = document.getElementById("addModal");

        // Get the button that opens the modal
        var btn = document.getElementById("addSub");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on the button, open the modal
        btn.onclick = function() {
          modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
          modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
          if (event.target == modal) {
            modal.style.display = "none";
          }
        }

        var i=1;  
        $('#add').click(function(){  
             i++;  
             $('#dynamic_field').append('<tr id="row'+i+'"><td><input class="bg-gray-800 rounded border border-gray-700 focus:outline-none focus:border-indigo-500 text-base text-white px-4 py-2 mb-4" name="nickname[]" placeholder="Artist Name" type="text"></td><td><input class="bg-gray-800 rounded border border-gray-700 focus:outline-none focus:border-indigo-500 text-base text-white px-4 py-2 mb-4" name="soundcloudLink[]" placeholder="SoundCloud Link" type="text"></td><td><button type="button" name="remove" id="'+i+'" style="color:white;" class="btn btn-danger btn_remove"> X</button></td></tr>');  
        });  
        $(document).on('click', '.btn_remove', function(){  
             var button_id = $(this).attr("id");   
             $('#row'+button_id+'').remove();  
        });  

        $('#submit').click(function(){ 

          $('#loader').addClass('activeLoader'); 

             $.ajax({  
                  url:"<?=base_url('/main/addSubmission')?>",  
                  method:"POST",  
                  data:$('#add_sub').serialize(),  
                  success:function(data)  
                  {  
                       //alert(data);  
                       $('#add_sub')[0].reset();  
                       location.reload(true); 
                  }  
             });  
        });  
     });  
     </script>
  </body>
</html>