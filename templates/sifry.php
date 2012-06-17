<style>
td {
width:20px;
text-align:center;
}
</style>
<h2>Trojita</h2>
<table cellpadding="0" cellspacing="0" border="1" style="table-layout:fixed;">
<tr><td>Z</td><td>M</td><td>U</td><td>CH</td><td>A</td><td>K</td><td>I</td><td>P</td><td>A</td></tr>
<tr><td>M</td><td>A</td><td>G</td><td>M</td><td>A</td><td>R</td><td>O</td><td>R</td><td>B</td></tr>
<tr><td>D</td><td>CH</td><td>E</td><td>U</td><td>P</td><td>R</td><td>B</td><td>K</td><td>A</td></tr>
<tr><td>Z</td><td>O</td><td>R</td><td>K</td><td>D</td><td>O</td><td>E</td><td>T</td><td>L</td></tr>
<tr><td>R</td><td>K</td><td>D</td><td>U</td><td>K</td><td>E</td><td>CH</td><td>E</td><td>T</td></tr>
<tr><td>I</td><td>E</td><td>E</td><td>U</td><td>A</td><td>&#9734;</td><td>E</td><td>A</td><td>P</td></tr>
<tr><td>Z</td><td>A</td><td>P</td><td>D</td><td>O</td><td>S</td><td>I</td><td>S</td><td>M</td></tr>
<tr><td>M</td><td>E</td><td>N</td><td>N</td><td>O</td><td>D</td><td>Y</td><td>H</td><td>R</td></tr>
<tr><td>R</td><td>U</td><td>A</td><td>S</td><td>U</td><td>N</td><td>E</td><td>V</td><td>O</td></tr>
<tr><td>J</td><td>Y</td><td>N</td><td>X</td><td>I</td><td>N</td><td>O</td><td>!</td><td>I</td></tr>
</table>

<form id="riesenieSifry" method="post" action="">
<input type="text" id="riesenie" />
<input type="submit" id="odosli" value="Odošli riešenie" /><br/>
<span id="result"></span>
</form>

<script type="text/javascript">
$(function(){
  
  function odosliRiesenie(nazov, riesenie) {
    var obj = {
      'nazov':nazov,
      'riesenie':riesenie,
    }
    $.post('?page=riesenieSifry', obj, function(response){
        if(response == 'ok') {
          $('#result').text('Správne').css({'color': 'green'}).fadeIn();
        } else {
          $('#result').text('Nesprávne').css({'color': 'red'}).fadeIn();
          $('#riesenie').select().focus();
        }
      });
  }

  $('#riesenie, #result').hide();
  
  $('#riesenieSifry').submit(function(event){
    if($('#riesenie').is(':visible')) {
      odosliRiesenie('trojita', $('#riesenie').val());
    } else {
      $('#riesenie').fadeIn().select().focus();
    }
    return false;
  });
});
</script>
