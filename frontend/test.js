//it's a constructor batman
var getdata = new getdata();

$( document ).ready(function() {

  getdata.selectplayers();

  getdata.eventlistener();

});

//getdata class
function getdata(){

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

}