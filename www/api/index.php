<?php
/*
 * index.php
 *
 * Author: Mark Hazlewood
 * © 2015 Mark Hazlewood (mhazlewood.org)
 *
 * Licensed under the MIT License.
 *
 */

   // include 'main.php';
   //
   // // Expose some bullshit via JSON
   // $bs = new Bullshit();
   // $mainTopic = rand(0, count($bs->sentencePool)-1);
   //
   // // Testing this with a Hipchat integration, which likes this format
   // $text = array
   // (
   //    "color" => "green",
   //    "message" => $bs->generateText(1, $mainTopic),
   //    "notify" => false,
   //    "message_format" => "text"
   // );
   //
   // // Output bullshit as JSON
   // header('Content-type: application/json');
   // echo json_encode($text);

   require_once 'api.php';
   require_once 'nabs/nabsAPI.php';

   // Requests from the same server don't have an HTTP_ORIGIN header
   if (!array_key_exists('HTTP_ORIGIN', $_SERVER))
   {
      $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
   }

   try
   {
      $API = new NabsAPI($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
      echo $API->processAPI();
   }
   catch (Exception $e)
   {
      echo json_encode(Array('error' => $e->getMessage()));
   }


?>
