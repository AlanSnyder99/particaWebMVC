<!DOCTYPE html>
<html>
<head>
  <title>ParticaBB</title>
  <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../application/resources/css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
</head>
<body>
<header class="text-gray-500 bg-gray-900 body-font">
  <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
    <a  class="flex title-font font-medium items-center text-white mb-4 md:mb-0 logo">
    <img class="logo" src="../application/resources/img/Partica-Logo-Blanco01.png">

    </a>
    <nav class="md:ml-auto flex flex-wrap items-center text-base justify-center">
       <a href="https://particaartistgroup.com/" target="_blank" class="mr-5 hover:text-gray">Shop</a>
  		<a href="https://www.twitch.tv/partica" target="_blank" class="mr-5 hover:text-gray twitch">Twitch</a>
       <a href="https://soundcloud.com/particaartistgroup" target="_blank" class="mr-5 hover:text-gray soundcloud">Soundcloud</a>
      <a  href="" target="_blank" class="mr-5 hover:text-gray">Patreon</a>
    </nav>
     
     <?php


      if ($_SESSION['user_is_logged'] == null ) {
       echo "  <a href='/main/login' id='myBtn'><button onclick='location.href='/main/login'' class='inline-flex items-center bg-gray-800 border-0 py-1 px-3 focus:outline-none hover:bg-gray-700 rounded text-base mt-4 md:mt-0 login'>Login
    </button></a>";
      } else {
            echo "  <a href='/main/logout' id='myBtn'><button onclick='location.href='/main/logout'' class='inline-flex items-center bg-gray-800 border-0 py-1 px-3 focus:outline-none hover:bg-gray-700 rounded text-base mt-4 md:mt-0 login'>Logout
    </button></a>";
      }



       ?>
  
     
     
<!--href='login.php?action=login'-->
  </div>
</header>


 <!-- <div id="loginModal" class="modal">

  Modal content 
  <div class="modal-content">
    <span class="close">&times;</span>
   
   <div class="min-h-screen flex items-center justify-center bg-gray-50 py-4 px-2 sm:px-2 lg:px-4">
  <div class="max-w-md w-full">
    <div>
      <img class="mx-auto h-12 w-auto" src="img/logo.png">
      <h2 class="mt-4 text-center text-3xl leading-9 font-extrabold text-gray-900 logo-title">
       Log in
      </h2>

    </div>
    <form class="mt-8" action="#" method="POST">
      <input type="hidden" name="remember" value="true">
      <div class="rounded-md shadow-sm">
        <div>
          <input aria-label="Email address" name="email" type="email" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5" placeholder="Username">
        </div>
        <div class="-mt-px">
          <input aria-label="Password" name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5" placeholder="Password">
        </div>
      </div>

      <div class="mt-6 flex items-center justify-between">
  
        <div class="text-sm leading-5">
          <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">
            Forgot your password?
          </a>
        </div>
      </div>

      <div class="mt-6">
        <button onclick="location.href='bettleList.php'" type="submit" class="singinBtn group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
          <span class="absolute left-0 inset-y-0 flex items-center pl-3">
            <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400 transition ease-in-out duration-150" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
            </svg>
          </span>
          Sign in
        </button>

               <button onclick="location.href='login.php?action=login'" type="submit" class="discordBtn group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
             <img class="DiscordLogo absolute left-0 inset-y-0 flex items-center pl-3" src="img/DiscordLogo.webp">
          Discord Login
        </button>
      </div>
    </form>
  </div>
</div>

  
  </div>

</div>-->