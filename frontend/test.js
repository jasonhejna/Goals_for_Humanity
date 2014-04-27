//it's a constructor batman
var getdata = new getdata();

$( document ).ready(function() {

  getdata.selectplayers();

  getdata.eventlistener();

});

//getdata class
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

    //console.log(json.foundGoals[0]);

    if(json.foundGoals != 'null')
    {
      $("captchaform").append("<br/><div>Is this the goal you're looking for? If not then fill out the captcha to complete goal submission.</div>");
      
      for (var i = json.foundGoals.length - 1; i >= 0; i--) {
        $("captchaform").append('<br/><div>'+json.foundGoals[i][i]+'</div>');
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

}