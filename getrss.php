<?php
  //get the q parameter from URL
  $q=$_GET["q"];

  $news = false;
  //find out which feed was selected
  if($q=="Google") {
    $news = true;
    $xml=("http://news.google.com/news?ned=us&topic=h&output=rss");
    
    $xmlDoc = new DOMDocument();
    $xmlDoc->load($xml);
    //get elements from "<channel>"
    $channel=$xmlDoc->getElementsByTagName('channel')->item(0);
    $channel_title = $channel->getElementsByTagName('title')
    ->item(0)->childNodes->item(0)->nodeValue;
    $channel_link = $channel->getElementsByTagName('link')
    ->item(0)->childNodes->item(0)->nodeValue;
    $channel_desc = $channel->getElementsByTagName('description')
    ->item(0)->childNodes->item(0)->nodeValue;
      $x=$xmlDoc->getElementsByTagName('item');

    for ($i=0; $i<=2; $i++) {
      $item_title=$x->item($i)->getElementsByTagName('title')
      ->item(0)->childNodes->item(0)->nodeValue;
      $item_link=$x->item($i)->getElementsByTagName('link')
      ->item(0)->childNodes->item(0)->nodeValue;
      $item_desc=$x->item($i)->getElementsByTagName('description')
      ->item(0)->childNodes->item(0)->nodeValue;
      echo ("<li><a href='" . $item_link
      . "'>" . $item_title . "</a></li>");
      // echo ( $item_title);
      // echo ("<br>");
      // echo ($item_desc . "</p>");
    }
  } elseif($q=="NBC") {
    $news = true;
    $xml=("http://rss.msnbc.msn.com/id/3032091/device/rss/rss.xml");
  } elseif($q=="cnn") {
    $news = true;
    $xml=("http://rss.cnn.com/rss/cnn_us.rss");
  } elseif($q=="weather") {
    $xml=("http://weather.yahooapis.com/forecastrss?p=22554");
    
    $xmlSimple = simplexml_load_file($xml);

    foreach($xmlSimple->channel->item as $item) {
      $temp = $item->children('yweather',true)->condition->attributes()->temp;
      $code = $item->children('yweather',true)->condition->attributes()->code;
      $condition = $item->children('yweather',true)->condition->attributes()->text;
      echo ($temp.'&deg;,'.$code.','.$condition);
    }

  } elseif($q=="stocks") {
    $query = "https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.quotes%20where%20symbol%20in%20(%22FDSTX%22%2C%22FBTCX%22%2C%22TRREX%22%2C%22MCLOX%22)%0A%09%09&format=json&diagnostics=true&env=http%3A%2F%2Fdatatables.org%2Falltables.env&callback=";

    $json = file_get_contents($query);
    $obj = json_decode($json);

    $total = 0;
    // echo ($obj->results->quote["0"]->symbol);
    foreach($obj->query->results->quote as $quote) {
      echo ("<div class='row'><div class='col-md-6'>");
      $name = "";
      $value = 0;
      if ( $quote->symbol == "FDSTX" ) {
        $name = "Focus Dividend";
        $value = 5000;
      }
      else if ( $quote->symbol == "FBTCX" ) {
        $name = "Fidelity Biotechnology";
        $value = 12000;
      }
      else if ( $quote->symbol == "TRREX" ) {
        $name = "Real Estate Fund"; 
        $value = 5000;
      }
      else if ( $quote->symbol == "MCLOX" ) {
        $name = "Blackrock Global";
      }

      $percent = floatval(preg_replace("/[^0-9.]/","",$quote->ChangeinPercent));
      $dollar = $value *$percent * 0.01;
      $dollar_string = money_format('$%.2n', $dollar);

      echo($name."</div><div class='col-md-6'>");
      if (strpos($quote->ChangeinPercent,'+') !== false) {
        // Positive change
        echo ("<span class='pos'>" . $quote->ChangeinPercent);
        $total = $total + $dollar;
      }
      else {
        // Negative Change
        echo ("<span class='neg'>" . $quote->ChangeinPercent);
        $total = $total - $dollar;
      }
      
      if ( $dollar_string == "$0.00" ) {
         echo("</span>");
      }
      else {
         echo(" ( ".$dollar_string." )</span>");
      }
      echo ("</div></div>");
    }
    
    echo ("<div class='row'><hr><div class='col-md-6'>Total</div><div class='col-md-6'>");
    
    if ( $total >= 0 )
      echo ("<span class='pos'>".money_format('$%.2n', $total));
    else
      echo ("<span class='neg'>".money_format('$%.2n', $total));

    echo ("</span></div></div>");
  }
?>
