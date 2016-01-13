<?php

require_once 'api.php';
require_once 'main.php';

class NabsAPI extends API
{
   protected $User;

   public function __construct($request, $origin)
   {
      parent::__construct($request);

      // Abstracted out for example
      // $APIKey = new Models\APIKey();
      // $User = new Models\User();
      //
      // if (!array_key_exists('apiKey', $this->request))
      // {
      //    throw new Exception('No API Key provided');
      // }
      // else if (!$APIKey->verifyKey($this->request['apiKey'], $origin))
      // {
      //    throw new Exception('Invalid API Key');
      // }
      // else if (array_key_exists('token', $this->request) &&
      //          !$User->get('token', $this->request['token']))
      // {
      //    throw new Exception('Invalid User Token');
      // }
      //
      // $this->User = $User;
   }

   // This API supports the following endpoints and arguments:
   //
   // --- /nabs/full
   //       => result contains a topic sentence along with several sentences
   //          of filler text, formatted into paragraphs with CSS classes
   // --- /nabs/full/hipchat
   //       => result contains a topic sentence along with several sentences
   //          of filler text, without any formatting, and is compatible with
   //          HipChat integration requirements
   // --- /nabs/short
   //       => result contains a single topic sentence, formatted into
   //          paragraphs with CSS classes
   // --- /nabs/short/hipchat
   //       => result contains a single topic sentence, without any formatting,
   //          and is compatible with HipChat integration requirements
   protected function nabs()
   {
      if ($this->method == 'GET')
      {
         $bs = new Bullshit();
         $sentenceTopic = rand(0, count($bs->sentencePool)-1);

         // There will always be at least one sentence, so generate
         // that one here.
         $sentenceText = $bs->generateText(1, $sentenceTopic);

         $returnText = '';
         switch ($this->verb)
         {
            // If client hits the 'full' variant, generate more filler text
            case 'full':
            {
               $fillerTopic = rand(0, count($bs->sentencePool)-1);
               $numSentences = rand(5,9);
               $fillerText = $bs->generateText($numSentences, $fillerTopic);

               // If 'hipchat' argument is present, return unformatted text
               if ($this->args[0] == 'hipchat')
               {
                  $unformattedText = $sentenceText . "<br />" . $fillerText;
                  $returnText = array
                  (
                     "color" => "green",
                     "message" => $unformattedText,
                     "notify" => false,
                     "message_format" => "text"
                  );
               }
               else
               {
                  $formattedText = "<p class='nabs_heading'>" . $sentenceText . "</p>";
                  $formattedText = $formattedText . "<p class='nabs_filler'>" . $fillerText . "</p>";

                  $returnText = array
                  (
                     "color" => "green",
                     "message" => $formattedText,
                     "notify" => false,
                     "message_format" => "text"
                  );
               }

               break;
            }
            case 'short':
            default:
            {
               // If 'hipchat' argument is present, return unformatted text
               if ($this->args[0] == 'hipchat')
               {
                  $returnText = array
                  (
                     "color" => "green",
                     "message" => $sentenceText,
                     "notify" => false,
                     "message_format" => "text"
                  );
               }
               else
               {
                  $formattedText = "<p class='nabs_heading'>" . $sentenceText . "</p>";

                  $returnText = array
                  (
                     "color" => "green",
                     "message" => $formattedText,
                     "notify" => false,
                     "message_format" => "text"
                  );
               }

               break;
            }
         }

         return $returnText;
      }
      else
      {
         return "Only accepts GET requests";
      }
   }
}


 ?>
