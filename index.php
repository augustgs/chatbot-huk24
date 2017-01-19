<?php
// parameters
$hubVerifyToken = 'fb_huk24';
$accessToken = "EAAQyp3kLbu0BAMyethJF3mse6jDENfDeDo0gnujTqwZC263DPaN1cYq3BDzxvvYUhUJaZARYi1t0oCZBRrjTbKQyp16ybcZBnmZC1a7X6sxirI1bhisQsKMSkRdZAFAAQs8WzKrPTGvolidstZAZC6XNNpYj8aRkJ4gyExlkFzwaxgZDZD";
// check token at setup
if ($_REQUEST['hub_verify_token'] === $hubVerifyToken) {
  echo $_REQUEST['hub_challenge'];
  exit;
}
// handle bot's anwser
$input = json_decode(file_get_contents('php://input'), true);
$senderId = $input['entry'][0]['messaging'][0]['sender']['id'];
$messageText = $input['entry'][0]['messaging'][0]['message']['text'];
//BEGINSEARCH

  $url = "https://www.googleapis.com/customsearch/v1?key=AIzaSyDRcwlS9oPIhukZcbuGGGMoyW_gA2seGcY&cx=000237076843953083475:1w-ye2np3o4&q=".urlencode($messageText)."&num=1";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $res = curl_exec($ch);
  $res = json_decode($res);
  $items = $res->items;
  if(count($items) > 0){
    $result = $res->items[0];
    $data_res = $result->link;
  }else {
    $data_res = "No result found";
  }
  curl_close($ch);


//ENDSEARCH
$response = [
    'recipient' => [ 'id' => $senderId ],
    'message' => [ 'text' => $data_res ]
];
$ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.$accessToken);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_exec($ch);
curl_close($ch);

?>