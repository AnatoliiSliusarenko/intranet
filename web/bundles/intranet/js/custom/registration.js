function showRecaptcha(element) {
           Recaptcha.create("6LdvEO8SAAAAACHjXu1Z6D2HIF9OcqMPW2yw8KOf", element, {
             theme: "clean",
             lang: "pt",
             callback: Recaptcha.focus_response_field});
         }


//jQuery time
var current_fs, next_fs, previous_fs; //fieldsets
var left, opacity, scale; //fieldset properties which we will animate
var animating; //flag to prevent quick multi-click glitches
var fieldsetn = 1;
var emailantigo;
var passval;
//reposição de texto
  //email
    $('input[name="email"]').focus(function() {
      if ($('input[name="email"]').hasClass('warning') === true && fieldsetn == 1){
        $('input[name="email"]').val(emailantigo);
      }  
    });
  

//algumas partes de css
  //select tiporegisto
$("select[name='tiporegisto']").change(function(){
  if ($("select[name='tiporegisto']").val() != "0"){
    $('select[name="tiporegisto"]').css("color","#2C3E50");
  }
});
  
//click
$(".next").click(function(){
  var erros = 0;

  if (fieldsetn == 1) {
     
    
  } //fim do primeiro fieldset
  else if (fieldsetn == 2){
      //ano escolar
        if ($('select[name="escola"] option:selected').val() ===0){
        $('select[name="escola"]').addClass('warning');
 
          erros = 1;
        }
        else {
          $('select[name="escola"]').removeClass('warning');}
    //fim
       if ($('select[name="ano"] option:selected').val() ===0){
        $('select[name="ano"]').addClass('warning');
 
          erros = 1;
        }
        else {
          $('select[name="ano"]').removeClass('warning');}
    //fim
      if ($('select[name="turma"] option:selected').val() ===0){
        $('select[name="turma"]').addClass('warning');
 
          erros = 1;
        }
        else {
          $('select[name="turma"]').removeClass('warning');}
    //fim
        if ($('select[name="numero"] option:selected').val() ===0){
          $('select[name="numero"]').addClass('warning');
 
          erros = 1;
        }
        else {
        $('select[name="numero"]').removeClass('warning');}
        //fim
  }//fim do fieldset numero 2
  else if ( fieldsetn == 3) {//fieldset numero 3
    //verificar nome
    if ($('input[name="nome"]').val().length ===0){
       $('input[name="nome"]').addClass('warning');
        $('input[name="nome"]').attr("placeholder","Preenchimento obrigatório");
        erros = 1;
    }
    else {
    $('input[name="nome"]').removeClass('warning');
    }
    //verificar apelido
    if ($('input[name="apelido"]').val().length ===0){
       $('input[name="apelido"]').addClass('warning');
        $('input[name="apelido"]').attr("placeholder","Preenchimento obrigatório");
        erros = 1;
    }
    else {
    $('input[name="apelido"]').removeClass('warning');
    }
    if ($('select[name="nascdia"] option:selected').val() ===0){
          $('select[name="nascdia"]').addClass('warning');
 
          erros = 1;
        }
        else {
        $('select[name="nascdia"]').removeClass('warning');}
        //fim
    if ($('select[name="nascmes"] option:selected').val() ===0){
          $('select[name="nascmes"]').addClass('warning');
 
          erros = 1;
        }
        else {
        $('select[name="nascmes"]').removeClass('warning');}
        //fim
     if ($('select[name="nascano"] option:selected').val() ===0){
          $('select[name="nascano"]').addClass('warning');
 
          erros = 1;
        }
        else {
        $('select[name="nascano"]').removeClass('warning');}
        //fim
  
 //Verificar data de nascimento
    if ($('select[name="nascmes"]').hasClass('warning') === false && $('select[name="nascdia"]').hasClass('warning') === false && $('select[name="nascdia"]').hasClass('warning') === false) {
      
      var dtMonth = $('select[name="nascmes"]').val();
      var dtDay= $('select[name="nascdia"]').val();
      var dtYear = $('select[name="nascano"]').val();

      if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
      {$('select[name="nascmes"]').addClass('warning');
        $('select[name="nascano"]').addClass('warning');
        erros = 1}

      else if (dtMonth == 2){

        if ((dtYear%4 !== 0 && dtDay >28) || dtDay>29)
       {$('select[name="nascmes"]').addClass('warning');
        $('select[name="nascano"]').addClass('warning');
        $('select[name="nascdia"]').addClass('warning');
        erros = 1}
      }  
    }//fim da verificação de data 
  }//fim do fieldset numero 3
  
  if (erros === 0){//inicio da animação
    if(animating) return false;
	  animating = true;
	  fieldsetn = fieldsetn +1;
	  current_fs = $(this).parent();
	  next_fs = $(this).parent().next();
    
    if (fieldsetn == 4){
    //escrever resumo
    var resemail=$('input[name="email"]').val();
    var resregisto=$('select[name="tiporegisto"]').val();
    var resescola=$('select[name="escola"] option:selected').text();
    var resano=$('select[name="ano"]').val();
    var resturma=$('select[name="turma"] option:selected').text();
    var resnumero=$('select[name="numero"]').val();
    var resnome=$('input[name="nome"]').val();
    var resapelido=$('input[name="apelido"]').val();
    var resnascdia=$('select[name="nascdia"]').val();
    var resnascmes=$('select[name="nascmes"]').val();
    var resnascano=$('select[name="nascano"]').val();
     
      //cálculo da idade
    var dataatual = new Date();//data atual
    var anoatual = dataatual.getFullYear(); 
    var mesatual = dataatual.getMonth() + 1;
    var diaatual = dataatual.getDate();

      //cálculos
      var residade;
    if ((mesatual>resnascmes) || (mesatual=resnascmes && diaatual>=resnascdia) ){
       residade = anoatual - resnascano;}
    else if(mesatual=resnascmes && diaatual<resnascdia){
      residade = anoatual - resnascano - 1;}
    else {
       residade = anoatual - resnascano - 1; }
    
     var resumo = "O teu nome é " + resnome+ " " + resapelido+ ", tens "+residade+" anos, és o número "+resnumero+" do "+resano+"º"+resturma+" da escola "+resescola+". <br>O email para o qual enviaremos a confirmação de registo é "+resemail;
    $('#resumo').html(resumo);//fim de escrever resumo
    showRecaptcha('captcha');//colocar recaptcha
      
    }//fim do fieldset numero 4
      
      
      
	  //activate next step on progressbar using the index of next_fs
	  $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
	
    	//show the next fieldset
	  next_fs.show(); 
	  //hide the current fieldset with style
	  current_fs.animate({opacity: 0}, {
	  	step: function(now, mx) {
			//as the opacity of current_fs reduces to 0 - stored in "now"
			//1. scale current_fs down to 80%
	  		scale = 1 - (1 - now) * 0.2;
			//2. bring next_fs from the right(50%)
		  	left = (now * 50)+"%";
		  	//3. increase opacity of next_fs to 1 as it moves in
			  opacity = 1 - now;
		  	current_fs.css({'transform': 'scale('+scale+')'});
		  	next_fs.css({'left': left, 'opacity': opacity});
		  }, 
		  duration: 800, 
		  complete: function(){
			  current_fs.hide();
			  animating = false;
		  }, 
		  //this comes from the custom easing plugin
		  easing: 'easeInOutBack'
	  });
  }//fim da animação  
});//fim do click

$(".previous").click(function(){
	if(animating) return false;
	animating = true;
  fieldsetn = fieldsetn - 1;
	
	current_fs = $(this).parent();
	previous_fs = $(this).parent().prev();
	
	//de-activate current step on progressbar
	$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
	
	//show the previous fieldset
	previous_fs.show(); 
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
		step: function(now, mx) {
			//as the opacity of current_fs reduces to 0 - stored in "now"
			//1. scale previous_fs from 80% to 100%
			scale = 0.8 + (1 - now) * 0.2;
			//2. take current_fs to the right(50%) - from 0%
			left = ((1-now) * 50)+"%";
			//3. increase opacity of previous_fs to 1 as it moves in
			opacity = 1 - now;
			current_fs.css({'left': left});
			previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
		}, 
		duration: 800, 
		complete: function(){
			current_fs.hide();
			animating = false;
		}, 
		//this comes from the custom easing plugin
		easing: 'easeInOutBack'
	});
});


$(".submit").click(function(){
  var captchadesafio = Recaptcha.get_challenge();
  var captcharesposta= Recaptcha.get_response();
 
 $.ajax({  
            type: "POST",
            url: '/php/verifycaptcha.php',
            data: { 'desafio=': captchadesafio, 'resposta=': captcharesposta }  
        }).done(function( msg ) {
    alert( "Data Saved: " + msg );
  });

});