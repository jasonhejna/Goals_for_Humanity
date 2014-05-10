//constructors
//
var getdata = new getdata();

var convienencemethods = new convienencemethods();

//document ready methods
//
$( document ).ready(function() {

  getdata.selectplayers();

  getdata.eventlistener();

  getdata.echogoals();

});



//getdata class
//
function getdata(){

this.eventlistener = function(){

  $("player1").click(function(){
    getdata.gameresult(1);
  });

  $("player2").click(function(){
    getdata.gameresult(2);
  });

  $("tiegame").click(function(){
    getdata.gameresult(0);
  });

  $("newgoal input:submit").click(function(){
    var goal_string =   $("newgoal #new_goal").val();
    console.log("goal string:"+goal_string);
    getdata.newgoal(goal_string);
  });

  $("captchaform input:submit").click(function(){
    var captcha =       $("captchaform #captcha_response").val();
    getdata.captcharesponse(captcha);
  });

  $("echogoals navigation next").click(function(){
    getdata.echogoals('FALSE');
  });

}

this.selectplayers = function(){

  $.ajax({
    type: "GET",//could be GET or POST
    url: "http://localhost/backend/selectplayers",
    success: function(data, textStatus, text) {

      console.log(data);

      gamedata = JSON.parse(data);

      getdata.key =      gamedata.key;
      getdata.goal1 =    gamedata.goal1;
      getdata.goal2 =    gamedata.goal2;

      $("player1").html(gamedata.goal1);
      $("player2").html(gamedata.goal2);

    },
    error: function(text, textStatus, errorThrown) {
      if(errorThrown == "all games played"){
        $("player1").hide();
        $("tiegame").hide();
        $("player2").hide();
        alert("all games played!");
      }
      console.log(textStatus, errorThrown);
    }
  });

}

this.gameresult = function(goalid){

  $.ajax({
    type: "POST",
    url: "http://localhost/backend/gameresult",
    data: {"key":this.key,"player_won":goalid},
    success: function(data, textStatus, json) {
      if(data == "success"){
        console.log("gameresult");
        getdata.selectplayers();
      }

    },
    error: function(json, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });

}

this.newgoal = function(goal){

  $('newgoal').hide();

  $.ajax({
    type: "POST",
    url: "http://localhost/backend/newgoal",
    data: {"goal":goal},
    success: function(data, textStatus, json) {

      console.log(data);

      json = JSON.parse(data);

      $("captchaform").prepend('<img src="'+json.captchaUrl+'" width="160" height="30">');

      $("captchaform").show();

      //console.log(json.similarGoals[0]);

      if(json.similarGoals != 'null')
      {
        $("captchaform").append("<br/><div>Is this the goal you're looking for? If not then fill out the captcha to complete goal submission.</div>");
        
        for (var i = json.similarGoals.length - 1; i >= 0; i--) {
          $("captchaform").append('<br/><div>'+json.similarGoals[i][i]+'</div>');
        };
      }

    },
    error: function(json, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
      alert(errorThrown);
    }
  });

}

this.captcharesponse = function(captcha){
  console.log("user_captcha_response:"+captcha)
  $.ajax({
    type: "POST",
    url: "http://localhost/backend/verifycaptcha",
    data: {"userDefinedCaptcha":captcha},
    success: function(data, textStatus, json) {

      console.log(data);

      json = JSON.parse(data);

      if(json.success == 1){
        alert("congratulations! Your goal submission was successfull. An admin will now review your submission.");
        $("captchaform").remove();
      }
      else if(json.success == 0){
        $("captchaform img").remove();
        $("captchaform").prepend('<img src="'+json.captchaUrl+'" width="160" height="30">');
        alert("you have a new captcha to fill out");
      }

    },
    error: function(json, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
  });
}

this.echogoals = function(back){

  var ie        = convienencemethods.getCookie('ie');

  if (ie===""){
    console.log('cookie not set');
    ie = 0;
  }

  if(back == 'TRUE') {
    ie          = ie - 2;
  }

  if(back != 'FALSE' && back != 'TRUE') {//it was a page load event
    ie          = 0;
  }

  console.log('eachogoals_count:'+ie);

  var limit1    = ie*6;
  ie++;
  var limit2    = ie*6;



  var maxgoal   = convienencemethods.getCookie('maxgoal');

  if(maxgoal !== "" && limit1 >= maxgoal){
    console.log('error:limit1 greater than max goal');
    limit1      = maxgoal - 6;
    ie          = 0;
    $('echogoals navigation next').hide();
  }

  if(maxgoal !== "" && limit2 > maxgoal){
    limit2      = maxgoal;
    $('echogoals navigation next').hide();
  }

  if(ie > 1 && !$('echogoals navigation back').length ) {
    //add back button
    $('echogoals navigation').prepend('<back>back</back>');
    $("echogoals navigation back").click(function(){
      getdata.echogoals('TRUE');
    });
  }

  if( ie == 1 ){
    //remove back button
    $('echogoals navigation back').remove();
    $('echogoals navigation next').show();
  }

  if(limit2 < maxgoal) {
    $('echogoals navigation next').show();
  }

  console.log(limit1);
  console.log(limit2);

  $.ajax({
    type: "POST",
    url: "http://localhost/backend/echogoals",
    data: {"limit1":limit1,"limit2":limit2},
    success: function(data, textStatus, json) {

      console.log(data);

      $("echogoals searchresults").remove();

      $("echogoals").prepend('<searchresults></searchresults>');

      json = JSON.parse(data);

      convienencemethods.setCookie('ie',ie);

      convienencemethods.setCookie('maxgoal',json.maxgoal);

      if(json.success == 1)
      {
        for (var i = 0; i < json.goalslength; i++) {
          $("echogoals searchresults").append('<searchresult><rank>'+json.goals[i].rank+'</rank><goal>'+json.goals[i].goal+'</goal><date>'+json.goals[i].date+'</date></searchresult><br/>');
        };
      }

    },
    error: function(json, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
      alert(textStatus, errorThrown);
    }
  });
}

}// end of getdata class

// convienence methods class
//
function convienencemethods(){

this.setCookie = function(cname,cvalue){
  var d = new Date();
  d.setTime(d.getTime()+(50*60*1000)); //5min
  var expires = "expires="+d.toGMTString();
  document.cookie = cname + "=" + cvalue + "; " + expires;
}

this.getCookie = function(cname){
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for(var i=0; i<ca.length; i++) 
    {
    var c = ca[i].trim();
    if (c.indexOf(name)==0){ return c.substring(name.length,c.length); }
  }
  return "";
}

}