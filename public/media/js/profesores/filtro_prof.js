var accentMap = {
  "à": "a",
  "á": "a",
  "â": "a",
  "ä": "a",
  "Á": "a",
  "À": "a",
  "Â": "a",
  "Ä": "a",
  "é": "e",
  "è": "e",
  "ê": "e",
  "ë": "e",
  "É": "e",
  "È": "e",
  "Ê": "e",
  "Ë": "e",
  "ï": "i",
  "î": "i",
  "í": "i",
  "ì": "i",
  "Í": "i",
  "Ì": "i",
  "Ï": "i",
  "Î": "i",
  "ô": "o",
  "ö": "o",
  "ò": "o",
  "ó": "o",
  "Ó": "o",
  "Ò": "o",
  "Ö": "o",
  "Ô": "o",
  "û": "u",
  "ù": "u",
  "ú": "u",
  "ü": "u",
  "Ú": "u",
  "Ù": "u",
  "Ü": "u",
  "Û": "u",
  "ñ": "n" 
};

$(function(){
  $('#busca_prof').keyup(function(){
    var val = normalize($(this).val().toLowerCase());
    // var filteredValues = normalize(val);

    $(".row_prof").hide();

    $(".row_prof").each(function(){

      var text = normalize($(this).text().toLowerCase());

      if(text.indexOf(val) != -1)
      {
        $(this).show();
      }
    });
  });
});

var normalize = function( term ) {
  var ret = "";
  for ( var i = 0; i < term.length; i++ ) {
    ret += accentMap[ term.charAt(i) ] || term.charAt(i);
  }
  return ret;
};