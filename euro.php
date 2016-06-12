<?php
  date_default_timezone_set('Europe/London');

  $uri = 'http://api.football-data.org/v1/soccerseasons/424/';
  $reqPrefs['http']['method'] = 'GET';
  $reqPrefs['http']['header'] = 'X-Auth-Token: fe33c7da872942c19b6c5f236797cd7b';
  $stream_context = stream_context_create($reqPrefs);

  $eventSource = file_get_contents($uri, false, $stream_context);
  $event = json_decode($eventSource);

  $fixtureSource = file_get_contents($event->_links->fixtures->href, false, $stream_context);
  $fixture = json_decode($fixtureSource);

  foreach($fixture->fixtures as $match){
    if($match->status !== 'FINISHED'){
      $homeTeamName = $match->homeTeamName;
      $awayTeamName = $match->awayTeamName;
      $homeTeamScore = is_null($match->result->goalsHomeTeam) ? 0 : $match->result->goalsHomeTeam;
      $awayTeamScore = is_null($match->result->goalsAwayTeam) ? 0 : $match->result->goalsAwayTeam;
      $todayDay = date("d", time());
      $startDay = date("d", strtotime($match->date));
      $start = date("ga d/m/Y", strtotime($match->date));
      if($todayDay == $startDay){
        $start = 'Today @ ' . date("ga", strtotime($match->date));
      }
      break;
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Euro 2016 Match</title>
  <link rel="stylesheet" href="app.css" media="screen" title="no title" charset="utf-8">
  <style>
    h3{
      font-size:20rem;
      color:#999;
    }
    h4{
      font-size:8rem;
    }
    h5{
      font-size:5rem;
    }
  </style>
</head>
<body>
  <div class="container" style="margin-top:15px;">
    <div class="row">
      <div class="col-xs-12" style="text-align:center;">
        <h5>Start: <span style="font-weight:normal"><?php echo $start; ?></span><h5>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-5" style="text-align:right;">
        <h3><?php echo $homeTeamScore; ?></h3>
      </div>
      <div class="col-xs-2" style="text-align:center;">
        <h3> - </h3>
      </div>
      <div class="col-xs-5" style="text-align:left;">
        <h3><?php echo $awayTeamScore; ?></h3>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-5" style="text-align:right;">
        <h4><?php echo $homeTeamName; ?></h3>
      </div>
      <div class="col-xs-2" style="text-align:center;">
         <h4 style="font-size:normal;">vs</h3>
      </div>
      <div class="col-xs-5" style="text-align:left;">
        <h4><?php echo $awayTeamName; ?></h3>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    setTimeout(function(){
      location.reload();
    }, 60000);
  </script>
</body>
</html>
